<?php

namespace App\Http\Controllers;

use CargoLogisticsModels\Job;
use CargoLogisticsModels\JobHistory;
use CargoLogisticsModels\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function dashboard()
    {
        $totalDeliveries = ['day' => 0, 'week' => 0, 'month' => 0];
        $deliveriesTimes = ['day' => 0, 'week' => 0, 'month' => 0];
        $distance = ['day' => 0, 'week' => 0, 'month' => 0];

        $userType = \auth()->user()->user_type;
        if ($userType == 'admin') {
            $totalDeliveries = [
                'day' => Job::where('created_at', '>=', date('Y-m-d'))->where('status', 'LIKE', 'completed')->count(),
                'week' => Job::where('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->where('status', 'LIKE', 'completed')->count(),
                'month' => Job::where('created_at', '>=', date('Y-m-d', strtotime('-30 days')))->where('status', 'LIKE', 'completed')->count()
            ];
            $deliveriesTimes = [
                'day' => round(JobHistory::where('created_at', '>=', date('Y-m-d'))->where('status', 'LIKE', 'completed')->avg('duration_minutes'), 0),
                'week' => round(JobHistory::where('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->where('status', 'LIKE', 'completed')->avg('duration_minutes') ,0),
                'month' => round(JobHistory::where('created_at', '>=', date('Y-m-d', strtotime('-30 days')))->where('status', 'LIKE', 'completed')->avg('duration_minutes'), 0)
            ];
            $distance = [
                'day' => round(Job::where('created_at', '>=', date('Y-m-d'))->where('status', 'LIKE', 'completed')->sum('distance')/1000, 2),
                'week' => round(Job::where('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->where('status', 'LIKE', 'completed')->sum('distance')/1000, 2),
                'month' => round(Job::where('created_at', '>=', date('Y-m-d', strtotime('-30 days')))->where('status', 'LIKE', 'completed')->sum('distance')/1000, 2)
            ];

        }
        elseif ($userType == 'vendor') {
            $totalDeliveries = [
                'day' => Job::where('created_at', '>=', date('Y-m-d'))->where('status', 'LIKE', 'completed')->where('vendor_id', '=', \auth()->user()->vendor_id)->count(),
                'week' => Job::where('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->where('status', 'LIKE', 'completed')->where('vendor_id', '=', \auth()->user()->vendor_id)->count(),
                'month' => Job::where('created_at', '>=', date('Y-m-d', strtotime('-30 days')))->where('status', 'LIKE', 'completed')->where('vendor_id', '=', \auth()->user()->vendor_id)->count()
            ];

            $jobIds = Job::where('vendor_id', '=', \auth()->user()->vendor_id)->get(['id'])->toArray();
            $deliveriesTimes = [
                'day' => round(JobHistory::where('created_at', '>=', date('Y-m-d'))->where('status', 'LIKE', 'completed')->whereIn('job_id', $jobIds)->avg('duration_minutes'), 0),
                'week' => round(JobHistory::where('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->where('status', 'LIKE', 'completed')->whereIn('job_id', $jobIds)->avg('duration_minutes') ,0),
                'month' => round(JobHistory::where('created_at', '>=', date('Y-m-d', strtotime('-30 days')))->where('status', 'LIKE', 'completed')->whereIn('job_id', $jobIds)->avg('duration_minutes'), 0)
            ];

            $distance = [
                'day' => round(Job::where('created_at', '>=', date('Y-m-d'))->where('status', 'LIKE', 'completed')->where('vendor_id', '=', \auth()->user()->vendor_id)->sum('distance')/1000, 2),
                'week' => round(Job::where('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->where('status', 'LIKE', 'completed')->where('vendor_id', '=', \auth()->user()->vendor_id)->sum('distance')/1000, 2),
                'month' => round(Job::where('created_at', '>=', date('Y-m-d', strtotime('-30 days')))->where('status', 'LIKE', 'completed')->where('vendor_id', '=', \auth()->user()->vendor_id)->sum('distance')/1000, 2)
            ];
        } elseif ($userType == 'branch') {
            $totalDeliveries = [
                'day' => Job::where('created_at', '>=', date('Y-m-d'))->where('status', 'LIKE', 'completed')->where('vendor_branch_id', '=', session('branch'))->count(),
                'week' => Job::where('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->where('status', 'LIKE', 'completed')->where('vendor_branch_id', '=', session('branch'))->count(),
                'month' => Job::where('created_at', '>=', date('Y-m-d', strtotime('-30 days')))->where('status', 'LIKE', 'completed')->where('vendor_branch_id', '=', session('branch'))->count()
            ];

            $jobIds = Job::where('vendor_branch_id', '=', session('branch'))->get(['id'])->toArray();
            $deliveriesTimes = [
                'day' => round(JobHistory::where('created_at', '>=', date('Y-m-d'))->where('status', 'LIKE', 'completed')->whereIn('job_id', $jobIds)->avg('duration_minutes'), 0),
                'week' => round(JobHistory::where('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->where('status', 'LIKE', 'completed')->whereIn('job_id', $jobIds)->avg('duration_minutes') ,0),
                'month' => round(JobHistory::where('created_at', '>=', date('Y-m-d', strtotime('-30 days')))->where('status', 'LIKE', 'completed')->whereIn('job_id', $jobIds)->avg('duration_minutes'), 0)
            ];

            $distance = [
                'day' => round(Job::where('created_at', '>=', date('Y-m-d'))->where('status', 'LIKE', 'completed')->where('vendor_branch_id', '=', session('branch'))->sum('distance')/1000, 2),
                'week' => round(Job::where('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->where('status', 'LIKE', 'completed')->where('vendor_branch_id', '=', session('branch'))->sum('distance')/1000, 2),
                'month' => round(Job::where('created_at', '>=', date('Y-m-d', strtotime('-30 days')))->where('status', 'LIKE', 'completed')->where('vendor_branch_id', '=', session('branch'))->sum('distance')/1000, 2)
            ];
        }

        return view('dashboard', compact('totalDeliveries', 'deliveriesTimes', 'distance'));
    }

    public function charts()
    {
        try {
            $dailyChart = [
                'labels' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                'chartData' => [45, 10, 5, 20, 8, 0, 30]
            ];

            $totalChart = [
                'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August'],
                'chartData' => [300, 50, 150, 600, 240, 1350, 900, 1200]
            ];

            return response()->json([
                'status' => 'success',
                'data' => ['dailyChart' => $dailyChart, 'totalChart' => $totalChart]
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'failed',
                'data' => []
            ]);
        }
    }

    public function home()
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'vendor' && Vendor::find(Auth::user()->vendor_id)->account_status != 'approved') {
                return redirect(route('notApproved'));
            } else {
                return redirect(route('dashboard'));
            }
        } else {
            return view('auth.login');
        }
    }

    public function changeLocale($locale)
    {
        App::setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }

    public function changeBranch($branch)
    {
        session()->put('branch', $branch);
        return redirect()->back();
    }

    public function translation()
    {
        $jsonString = file_get_contents(resource_path('lang/ar.json'));
        $lang = json_decode($jsonString);

        return view('settings.translations', compact('lang'));
    }

    public function updateLanguage(Request $request)
    {
        if ($request->result) {
            $data = json_encode($request->result);
            file_put_contents(resource_path('lang/ar.json'), $data);
        }

        return '/dashboard';
    }

    public function notApproved()
    {
        return view('notApproved');
    }

    public function chooseBranch()
    {
        return view('chooseBranch');
    }

}
