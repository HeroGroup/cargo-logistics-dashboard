<?php

namespace App\Http\Controllers;

use CargoLogisticsModels\Driver;
use CargoLogisticsModels\DriverJobRate;
use CargoLogisticsModels\DriverLocation;
use CargoLogisticsModels\Job;
use CargoLogisticsModels\JobHistory;
use CargoLogisticsModels\JobItem;
use CargoLogisticsModels\NotifyDriver;
use CargoLogisticsModels\Setting;
use CargoLogisticsModels\Vendor;
use CargoLogisticsModels\VendorBranch;
use CargoLogisticsModels\VendorDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function validator(array $data)
    {
        return Validator::make($data, [
            'vendor_id' => ['required', 'max:120'],
            'pickup_address' => ['required'],
            'pickup_description' => ['nullable', 'max:50'],
            'pickup_contact_person' => ['required'],
            'pickup_contact_phone' => ['required', 'size:8'],
            'dropoff_address' => ['required', 'max:120'],
            'dropoff_description' => ['nullable', 'max:50'],
            'dropoff_contact_person' => ['required'],
            'dropoff_contact_phone' => ['required', 'size:8'],
        ]);
    }

    public function allJobs($status='all', $date='thirty', $fromDate=null, $toDate=null, $v=null, $branch=null)
    {
        switch ($date) {
            case 'today':
                $jobs = Job::where('created_at', '>=', date('Y-m-d'));
                break;
            case 'seven':
                $jobs = Job::where('created_at', '>=', date('Y-m-d', strtotime('-7 days')));
                break;
            case 'thirty':
                $jobs = Job::where('created_at', '>=', date('Y-m-d', strtotime('-30 days')));
                break;
            case 'custom':
                $jobs = Job::whereBetween('created_at', [$fromDate, date('Y-m-d', strtotime($toDate . "+1 days"))]);
                break;
            default:
                break;
        }

        $userType = \auth()->user()->user_type;
        $branches_list = [];

        if ($status != 'all')
            $jobs->where('status', '=', $status);

        if ($userType == 'vendor') {
            $v = \auth()->user()->vendor_id;
            $jobs->where('vendor_id', '=', $v);
            if ($branch)
                $jobs->where('vendor_branch_id', '=', $branch);

            $branches_list = VendorBranch::where('vendor_id', '=', $v)->pluck('name', 'id')->toArray();
        } elseif ($userType == 'branch') {
            $jobs->where('vendor_branch_id', '=', session('branch'));
        } else {
            if ($v)
                $jobs->where('vendor_id', '=', $v);
            if ($branch)
                $jobs->where('vendor_branch_id', '=', $branch);
        }

        $jobs = $jobs->orderBy('id', 'DESC')->get();

        $vendors = Vendor::pluck('name', 'id')->toArray();

        $drivers = Driver::whereAvailability(1)->where('account_status', 'LIKE', 'approved'); // retrieve all drivers

        if ($userType == 'vendor' || $userType == 'branch') {
            $vendorId = \auth()->user()->vendor_id;
            $vendor = Vendor::find($vendorId);
            if ($vendor->has_own_drivers) {
                $driverIds = VendorDriver::where('vendor_id', '=', $vendorId)->get('driver_id')->toArray();
                $drivers->whereIn('id', $driverIds);
            } else {
                $driverIds = VendorDriver::get('driver_id')->toArray();
                $drivers->whereNotIn('id', $driverIds);
            }
        } else {
            $driverIds = VendorDriver::get('driver_id')->toArray();
            $drivers->whereNotIn('id', $driverIds);
        }

        $drivers = $drivers->get();


        foreach ($jobs as $job) {
            $arr = [];
            foreach ($drivers as $driver) {
                $item = $driver->toArray();
                $latestId = DriverLocation::where('driver_id', '=', $driver->id)->max('id');
                $latestLocation = DriverLocation::find($latestId);
                $distance = $this->calculateDistance($job->pickup_latitude, $job->pickup_longitude, $latestLocation->latitude, $latestLocation->longitude);
                $item['distance_to_pickup'] = round($distance/1000) . ' Km';
                array_push($arr, $item);
            }
            $job->drivers = $arr;
        }

        return view('jobs.index', compact('jobs', 'vendors', 'branches_list', 'status', 'date', 'fromDate', 'toDate', 'v', 'branch'));
    }

    public function liveJobs()
    {
        $jobs = Job::whereIn('status', ['accepted', 'started']);

        if (Auth::user()->user_type == 'vendor')
            $jobs->where('vendor_id', '=', Auth::user()->vendor_id);

        if (Auth::user()->user_type == 'branch')
            $jobs->where('vendor_id', '=', session('branch'));

        $jobs = $jobs->orderBy('id', 'DESC')->get();
        return view('jobs.liveJobs', compact('jobs'));
    }

    public function create($type = 'simple')
    {
        $owner = '';
        if (\auth()->user()->user_type == 'vendor')
            $owner = Vendor::find(auth()->user()->vendor_id);
        elseif (\auth()->user()->user_type == 'branch')
            $owner = VendorBranch::find(session('branch'));

        switch ($type) {
            case "simple":
                return view('jobs.createSimple', compact('owner'));
                break;
            case "advanced":
                return view('jobs.createAdvanced', compact('owner'));
                break;
            default:
                break;
        }
    }

    public function store(Request $request)
    {
        // validation
        $this->validator($request->all())->validate();

        $data = $request->toArray();

        $data['distance'] = $this->calculateDistance($data['pickup_latitude'], $data['pickup_longitude'], $data['dropoff_latitude'], $data['dropoff_longitude']);
        $data['unique_number'] = strtotime('now');
        if (!isset($data['pickup_date'])) $data['pickup_date'] = date('Y-m-d');
        $data['pickup_date'] = isset($data['pickup_date']) ? $data['pickup_date'] : date('Y-m-d');

        if (!isset($data['pickup_time'])) $data['pickup_time'] = date("h:i:s");
        if (!isset($data['dropoff_date'])) $data['dropoff_date'] = date('Y-m-d');
        if (!isset($data['dropoff_time'])) $data['dropoff_time'] = date("h:i:s");

        $job = Job::create($data);
        $this->saveJobHistory($job->id, 'create');

        if ($request->quantity) {
            for ($i=0; $i<count($request->quantity); $i++) {
                if ($request->quantity[$i] > 0 && strlen($request->item_description[$i]) > 0) {
                    JobItem::create([
                        'job_id' => $job->id,
                        'item_description' => $request->item_description[$i],
                        'item_price' => $request->item_price[$i],
                        'quantity' => $request->quantity[$i]
                    ]);
                }
            }
        }

        if ($this->notifyDrivers($job))
            return redirect(route('jobs.index'));
        else {
            $errors['no_driver'] = __('Unfortunately there are no nearby drivers right now!');
            return redirect(route('jobs.index'))->withErrors($errors);
        }
    }

    public function edit(Job $job)
    {
        $items = JobItem::where('job_id', '=', $job->id)->get();
        $rating = DriverJobRate::where('job_id', '=', $job->id)->first();
        return view('jobs.edit', compact('job', 'items', 'rating'));
    }

    public function update(Request $request, Job $job)
    {
        if ($request->instructions) {
            $job->update(['instructions' => $request->instructions]);
        } elseif ($request->rating) {
            if (DriverJobRate::where('job_id', '=', $job->id)->count() == 0) {
                DriverJobRate::create([
                    'driver_id' => $job->driver_id,
                    'job_id' => $job->id,
                    'rate' => $request->rating,
                    'comment' => $request->comment
                ]);
            } else {
                return redirect(route('jobs.index'))->withErrors([__('You have already rated this job.')]);
            }
        }

        return redirect(route('jobs.index'));
    }

    public function destroy(Job $job)
    {
        if ($job->status == 'new') {
            JobItem::where('job_id', '=', $job->id)->delete();
            $job->delete();
            $this->saveJobHistory($job->id, 'deleted');

            return redirect(route('jobs.index'));
        } else {
            return redirect(route('jobs.index'))->withErrors([__('You can not delete this job!')]);
        }
    }

    public function notifyDriversApi($jobId)
    {
        $job = Job::find($jobId);
        if ($this->notifyDrivers($job))
            return "success";
        else
            return "fail";
    }

    public function notifyDrivers($job)
    {
        $range = Setting::where('setting_key', 'LIKE' ,'notify_range')->first()->setting_value;
        $onlineDrivers = Driver::where('availability', '=', 1)->get();

        // find close drivers and put device_tokens in an array
        $data = array("title" => __("New Job Alert!"), "message" => __("Pickup on ") . $job->pickup_address, "jobId" => $job->id);
        $to = []; // array for pushy_device_tokens
        foreach ($onlineDrivers as $onlineDriver) {
            $latestId = DriverLocation::where('driver_id', '=', $onlineDriver->id)->max('id');
            $latestLocation = DriverLocation::find($latestId);

            if ($latestLocation) {
                $distance = $this->calculateDistance($job->pickup_latitude, $job->pickup_longitude, $latestLocation->latitude, $latestLocation->longitude);

                if ($distance >= 0 && $distance <= (int)$range*1000) { // closer than 5 kilometers
                    if ($onlineDriver->pushy_device_token) {
                        array_push($to, $onlineDriver->pushy_device_token);
                        NotifyDriver::create([
                            'driver_id' => $onlineDriver->id,
                            'job_id' => $job->id,
                            'driver_distance' => $distance,
                            'status' => 'notified'
                        ]);
                    }
                }
            }
        }

        // send notification to drivers
        if(count($to) > 0) {
            NotificationController::sendPushNotification($data, $to, null);
            return true;
        } else
            return false;

    }

    public function calculateDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) // $earthRadius in meters
    {
        if (!$latitudeFrom || !$longitudeFrom || !$latitudeTo || !$longitudeTo)
            return -1;

        try {
            $apiKey = 'AIzaSyDnzOm5LfUi_94uz3nPjW-LExL14iqfLmU';
            $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$latitudeFrom,$longitudeFrom&destinations=$latitudeTo,$longitudeTo&key=$apiKey";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($output);
            if ($result)
                return $result->rows[0]->elements[0]->distance->value;
            else
                return -2;

        } catch (\Exception $exception) {
            return -3;
        }

/*
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lngFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lngTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lngTo - $lngFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
*/

    }

    public function saveJobHistory($jobId, $status, $driver=null, $driverLatitude=null, $driverLongitude=null)
    {
        $job = Job::find($jobId);

        JobHistory::create([
            'job_id' => $jobId,
            'driver_id' => $driver,
            'status' => $status,
            'driver_latitude' => $driverLatitude,
            'driver_longitude' => $driverLongitude,
            'distance_to_pickup' => $this->calculateDistance($job->pickup_latitude, $job->pickup_longitude, $driverLatitude, $driverLongitude),
            'distance_to_dropoff' => $this->calculateDistance($driverLatitude, $driverLongitude, $job->dropoff_latitude, $job->dropoff_longitude),
        ]);
    }

    public function jobHistory(Job $job)
    {
        $histories = JobHistory::where('job_id', '=', $job->id)->orderBy('id', 'asc')->get();

        return view('jobs.history', compact('job', 'histories'));
    }

    public function jobNotifications(Job $job)
    {
        $notifications = NotifyDriver::where('job_id', '=', $job->id)->orderBy('id', 'asc')->get();
        return view('jobs.notifications', compact('job', 'notifications'));
    }

    public function assignDriver(Request $request)
    {
        $job = Job::find($request->job);
        if ($job->driver_id == null) {
            if (Driver::find($request->driver)) {
                $job->update(['driver_id' => $request->driver, 'status' => 'accepted']);

                $driverLatestLocationId = DriverLocation::where('driver_id', '=', $request->driver)->max('id');
                $driverLatestLocation = $driverLatestLocationId ? DriverLocation::find($driverLatestLocationId) : null;
                $lat = $driverLatestLocation ? $driverLatestLocation->latitude : null;
                $lng = $driverLatestLocation ? $driverLatestLocation->longitude : null;

                $this->saveJobHistory($job->id, 'assigned', $request->driver, $lat, $lng);

                return [
                    'status' => true,
                    'url' => route('jobs.index')
                ];
            } else {
                return [
                    'status' => false,
                    'message' => __('Invalid driver.')
                ];
            }
        } else {
            return [
                'status' => false,
                'message' => __('A driver is already on this job.')
            ];
        }

    }

    public function notifySingleDriver(Request $request)
    {
        // $range = Setting::where('setting_key', 'LIKE', 'notify_range')->first()->setting_value;
        $job = Job::find($request->job);
        $driver = Driver::find($request->driver);

        $data = array("title" => __("New Job Alert!"), "message" => __("Pickup on ") . $job->pickup_address, "jobId" => $job->id);

        if (!$driver->pushy_device_token)
            return [
                'status' => false,
                'message' => __('Unable to send notification to driver because driver has not logged in the application yet!')
            ];

        $to = [$driver->pushy_device_token];
        $latestId = DriverLocation::where('driver_id', '=', $driver->id)->max('id');
        $latestLocation = DriverLocation::find($latestId);

        NotifyDriver::create([
            'driver_id' => $driver->id,
            'job_id' => $job->id,
            'driver_distance' => $latestLocation ? $this->calculateDistance($latestLocation->latitude, $latestLocation->longitude, $job->pickup_latitude, $job->pickup_longitude) : null,
            'status' => 'notified'
        ]);
        NotificationController::sendPushNotification($data, $to, null);
        return [
            'status' => true,
            'url' => route('jobs.index')
        ];
    }

    public function map()
    {
        $userType = \auth()->user()->user_type;

        $vendorDriverIds = VendorDriver::get(['driver_id'])->toArray();
        $onlineDrivers = Driver::whereNotIn('id', $vendorDriverIds)->where('availability', '=', 1)->count();
        $allDrivers = Driver::whereNotIn('id', $vendorDriverIds)->where('account_status', '=', 'approved')->get()->count();

        if (($userType == 'vendor' || $userType == 'branch') && \auth()->user()->vendor->has_own_drivers) {
            $vendorDriverIds = VendorDriver::where('vendor_id', '=', \auth()->user()->vendor_id)->get(['driver_id'])->toArray();
            $onlineDrivers = Driver::whereIn('id', $vendorDriverIds)->where('availability', '=', 1)->count();
            $allDrivers = Driver::whereIn('id', $vendorDriverIds)->where('account_status', '=', 'approved')->get()->count();
        }

        if ($userType == 'admin') {
            $newJobs = Job::where('status', 'LIKE', 'new')->count();
            $acceptedJobs = Job::where('status', 'LIKE', 'accepted')->count();
            $startedJobs = Job::where('status', 'LIKE', 'started')->count();
            $completedJobs = Job::where('status', 'LIKE', 'completed')->count();
            $canceledJobs = Job::where('status', 'LIKE', 'canceled')->count();
            $avgDeliveryTime = round(JobHistory::where('status', 'LIKE', 'completed')->avg('duration_minutes'), 0);
        } elseif ($userType == 'vendor') {
            $newJobs = Job::where('status', 'LIKE', 'new')->where('vendor_id', '=', \auth()->user()->vendor_id)->count();
            $acceptedJobs = Job::where('status', 'LIKE', 'accepted')->where('vendor_id', '=', \auth()->user()->vendor_id)->count();
            $startedJobs = Job::where('status', 'LIKE', 'started')->where('vendor_id', '=', \auth()->user()->vendor_id)->count();
            $completedJobs = Job::where('status', 'LIKE', 'completed')->where('vendor_id', '=', \auth()->user()->vendor_id)->count();
            $canceledJobs = Job::where('status', 'LIKE', 'canceled')->where('vendor_id', '=', \auth()->user()->vendor_id)->count();
            $jobIds = Job::where('vendor_id', '=', \auth()->user()->vendor_id)->get(['id'])->toArray();
            $avgDeliveryTime = round(JobHistory::where('status', 'LIKE', 'completed')->whereIn('job_id', $jobIds)->avg('duration_minutes'), 0);
        } else {
            $newJobs = Job::where('status', 'LIKE', 'new')->where('vendor_branch_id', '=', session('branch'))->count();
            $acceptedJobs = Job::where('status', 'LIKE', 'accepted')->where('vendor_branch_id', '=', session('branch'))->count();
            $startedJobs = Job::where('status', 'LIKE', 'started')->where('vendor_branch_id', '=', session('branch'))->count();
            $completedJobs = Job::where('status', 'LIKE', 'completed')->where('vendor_branch_id', '=', session('branch'))->count();
            $canceledJobs = Job::where('status', 'LIKE', 'canceled')->where('vendor_branch_id', '=', session('branch'))->count();$avgDeliveryTime = 0;
            $jobIds = Job::where('vendor_branch_id', '=', session('branch'))->get(['id'])->toArray();
            $avgDeliveryTime = round(JobHistory::where('status', 'LIKE', 'completed')->whereIn('job_id', $jobIds)->avg('duration_minutes'), 0);
        }

        return view('jobs.map', compact('onlineDrivers', 'allDrivers', 'newJobs', 'acceptedJobs', 'startedJobs', 'completedJobs', 'canceledJobs', 'avgDeliveryTime'));
    }

    public function getJobs()
    {
        try {
            $jobs = Job::whereNotNull('dropoff_latitude')->whereNotNull('dropoff_longitude');

            if (\auth()->user()->user_type == 'vendor')
                $jobs->where('vendor_id', '=', \auth()->user()->vendor_id);
            elseif (\auth()->user()->user_type == 'branch')
                $jobs->where('vendor_branch_id', '=', session('branch'));

            $jobs = $jobs->get(["id", "pickup_address", "pickup_latitude", "pickup_longitude", "dropoff_address", "dropoff_latitude", "dropoff_longitude", "status", "unique_number", "vendor_id", "vendor_branch_id", "driver_id", "distance", "created_at"]);

            $items = [];
            foreach ($jobs as $job) {
                $item = $job->toArray();
                $item['vendor_name'] = $job->vendor ? $job->vendor->name : '-';
                $item['branch_name'] = $job->branch ? $job->branch->name : 'Main Branch';
                $item['driver_name'] = $job->driver ? $job->driver->name : 'No Driver';

                if (in_array($item['status'], ['accepted', 'assigned', 'started']) && $job->driver_id) {
                    $driverLatestLocationId = DriverLocation::where('driver_id', '=', $job->driver_id)->max('id');
                    $driverLocation = DriverLocation::find($driverLatestLocationId);
                    $item['driver_latitude'] = $driverLocation->latitude;
                    $item['driver_longitude'] = $driverLocation->longitude;

                    $started = JobHistory::where('job_id', '=', $job->id)->where('status', 'LIKE', 'started')->first(['driver_latitude', 'driver_longitude']);
                    if ($started) {
                        $item["pickup_latitude"] = $started->driver_latitude;
                        $item["pickup_longitude"] = $started->driver_longitude;
                    }
                }

                array_push($items, $item);
            }

            return response()->json($items);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    public function getOnlineDrivers()
    {
        $userType = \auth()->user()->user_type;
        if ($userType == 'admin') {
            $vendorDriverIds = VendorDriver::get(['driver_id'])->toArray();
            $driverIds = Driver::where('availability', 1)->whereNotIn('id', $vendorDriverIds)->get(['id'])->toArray();
        } elseif (($userType == 'vendor' || $userType == 'branch') && \auth()->user()->vendor->has_own_drivers) {
            $driverIds = VendorDriver::where('vendor_id', '=', \auth()->user()->vendor_id)->get(['driver_id'])->toArray();
        } else {
            return response()->json([]);
        }

        $drivers = [];
        foreach ($driverIds as $driverId) {
            $maxId = DriverLocation::where('driver_id', '=', $driverId)->max('id');
            if ($maxId) {
                $driverLocation = DriverLocation::find($maxId);
                $item = [
                    'name' => $driverLocation->driver->name,
                    'phone' => $driverLocation->driver->phone,
                    'jobs' => Job::where('driver_id' ,'=', $driverLocation->driver_id)->whereIn('status', ['accepted'])->count(),
                    'latitude' => $driverLocation->latitude,
                    'longitude' => $driverLocation->longitude
                ];
                array_push($drivers, $item);
            }
        }

        return response()->json($drivers);
    }

}
