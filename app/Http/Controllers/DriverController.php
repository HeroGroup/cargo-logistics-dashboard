<?php

namespace App\Http\Controllers;

use App\User;
use CargoLogisticsModels\Driver;
use CargoLogisticsModels\Job;
use CargoLogisticsModels\JobHistory;
use CargoLogisticsModels\VendorDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    public function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:191'],
            'email' => ['nullable', 'string', 'email', 'max:191', 'unique:drivers'],
            'phone' => ['required', 'string', 'size:8', 'unique:drivers', 'unique:users,mobile'],
        ]);
    }

    public function passwordValidator(array $data)
    {
        return Validator::make($data, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function manualValidation($driverId, array $data)
    {
        $errors = [];
        $driver = Driver::find($driverId);
        if ($driver->phone != $data['phone']) {
            if (Driver::where('phone', 'LIKE', $data['phone'])->count() > 0) {
                $errors['phone'] = 'This mobile number is already taken.';
            }
        }

        $user = User::where('driver_id', '=', $driverId)->first();
        if ($user->mobile != $data['phone']) {
            if (User::where('mobile', 'LIKE', $data['phone'])->count() > 0) {
                $errors['phone'] = 'This mobile number is already taken.';
            }
        }

        if ($driver->email != $data['email']) {
            if (Driver::where('email', 'LIKE', $data['email'])->count() > 0) {
                $errors['email'] = 'This email address is already taken.';
            }
        }

        return $errors;
    }

    public function index($status)
    {
        if (auth()->user()->user_type == 'admin') {
            $drivers = $status == "all" ? Driver::all() : Driver::where('account_status', '=', $status)->get();
        } elseif (auth()->user()->user_type == 'vendor' || auth()->user()->user_type == 'branch') {
            $vendorId = auth()->user()->vendor_id;
            $driverIds = VendorDriver::where('vendor_id', '=', $vendorId)->pluck('driver_id')->toArray();
            $drivers = Driver::whereIn('id', $driverIds);
            $drivers = $status == "all" ? $drivers->get() : $drivers->where('account_status', '=', $status)->get();
        }

        return view('drivers.index', compact('status', 'drivers'));
    }

    public function create()
    {
        return view('drivers.create');
    }

    public function store(Request $request)
    {
        if (\auth()->user()->user_type == 'vendor' && $request->vendor_id != Auth::user()->vendor_id)
            return abort(401);

        $this->validator($request->all())->validate();
        $this->passwordValidator($request->all())->validate();

        $driver = new Driver([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'profile_photo' => $request->hasFile('profile_photo') ? $this->saveDriversFile($request->profile_photo) : null,
            'driver_type' => $request->driver_type,
            'fixed_commission' => $request->fixed_commission,
            'commission_percent' => $request->commission_percent,
            'transport_mode' => $request->transport_mode,
            'licence_file' => $request->hasFile('licence_file') ? $this->saveDriversFile($request->licence_file) : null,
            'licence_expiration_date' => $request->licence_expiration_date,
            'vendor_id' => $request->vendor_id,
//            'vendor_id' => Auth::user()->user_type == 'vendor' ? Auth::user()->vendor_id : null
        ]);

        $driver->save();

        if(\auth()->user()->user_type == 'vendor')
            VendorDriver::create([
                'vendor_id' => $request->vendor_id,
                'driver_id' => $driver->id
            ]);

        // CREATE USER
        User::create([
            'name' => $driver->name,
            'mobile' => $driver->phone,
            'password' => Hash::make($request->password),
            'user_type' => 'driver',
            'driver_id' => $driver->id
        ]);

        return redirect(route('drivers.index', 'all'));
    }

    public function edit(Driver $driver)
    {
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $errors = $this->manualValidation($driver->id, $request->toArray());
        if (count($errors) > 0) {
            return redirect(route('drivers.edit', $driver->id))
                ->withErrors($errors)
                ->withInput();
        }

        $data = $request->toArray();

        if ($request->availability == "on")
            $data['availability'] = 1;
        else
            $data['availability'] = 0;

        if ($request->hasFile('licence_file'))
            $data['licence_file'] = $this->saveDriversFile($request->licence_file);

        if ($request->hasFile('profile_photo'))
            $data['profile_photo'] = $this->saveDriversFile($request->profile_photo);

        $driver->update($data);

        // UPDATE USER
        $user = User::where('driver_id', '=', $driver->id)->first();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->phone,
        ]);

        return redirect(route('drivers.index', 'all'));
    }

    public function destroy($driverId)
    {
        if (Job::where('driver_id', '=', $driverId)->count() == 0) {
            VendorDriver::where('driver_id', '=', $driverId)->delete();
            Driver::find($driverId)->delete();
            User::where('driver_id', '=', $driverId)->first()->delete();

            return redirect(route('drivers.index', 'all'));
        } else {
            $errors = ['Can NOT delete driver, because driver has done jobs.'];
            return redirect(route('drivers.index', 'all'))->withErrors($errors);
        }
    }

    public function changePassword(Driver $driver)
    {
        return view('drivers.changePassword', compact('driver'));
    }

    public function updatePassword(Request $request, Driver $driver)
    {
        // if (Hash::check($request->old_password, $driver->password)) {
            $this->passwordValidator($request->all())->validate();
            $driver->update([
                'password' => Hash::make($request->password)
            ]);

        // UPDATE USER PASSWORD
        $user = User::where('driver_id', '=', $driver->id)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        // } else {
            // return redirect(route('drivers.changePassword', $driver->id))->with('messageOldPassword', 'current password is incorrect');
        // }

        return redirect(route('drivers.index', 'all'));
    }

    public function showDriversLog(Driver $driver)
    {
        return view('drivers.showLogs', compact('driver'));
    }

    public function saveDriversFile($file)
    {
        $fileName = time().'.'.$file->getClientOriginalName();
        $file->move('resources/assets/images/driver_images/', $fileName);
        return 'https://getin.cargologisticskw.com/resources/assets/images/driver_images/'.$fileName;
    }

    public function approve($driverId)
    {
        $driver = Driver::find($driverId);
        $driver->update(['account_status' => 'approved']);
        return redirect(route('drivers.index', 'all'));
    }

    public function jobHistory(Driver $driver, $filter='all')
    {
        if ($filter == 'all')
            $histories = JobHistory::where('driver_id', '=', $driver->id)->orderBy('id')->get();
        else
            $histories = JobHistory::where([['driver_id', '=', $driver->id],['status', 'LIKE', $filter]])->orderBy('id')->get();

        $drivers = Driver::pluck('name', 'id')->toArray();
        return view('drivers.jobHistory', compact('driver', 'histories', 'filter', 'drivers'));
    }
}
