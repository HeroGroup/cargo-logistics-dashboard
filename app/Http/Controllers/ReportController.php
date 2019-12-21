<?php

namespace App\Http\Controllers;

use App\Exports\DeliveryFeesExport;
use App\Exports\JobsExport;
use App\Exports\DriversExport;
use App\Exports\DriverJobsExport;
use App\Exports\VendorsExport;
use CargoLogisticsModels\Driver;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportController extends Controller
{
    public function index()
    {
        $driversList = Driver::pluck('name', 'id')->toArray();

        $reportsList = [
            'jobs.export' => 'All Jobs'
        ];

        if (auth()->user()->user_type == 'admin') {

            $reportsList['vendors.export'] = 'All Vendors';
            $reportsList['drivers.export'] = 'All Drivers';
            $reportsList['drivers.jobs.export'] = 'Driver Jobs';
        } else {
            //
        }

        $reportsList['vendors.deliveryFees'] = 'Delivery Fees';
        return view('reports.index', compact('reportsList', 'driversList'));
    }

    public function getReport(Request $request)
    {
        return route($request->reportType, [$request->fromDate, $request->toDate, $request->driver]);
    }

    public function exportJobs($fromDate, $toDate)
    {
        $reportName = auth()->user()->user_type == 'admin' ? "jobs_admin_".$fromDate.'_to_'.$toDate : "jobs_".auth()->user()->vendor->name.'_'.$fromDate.'_to_'.$toDate;
        return Excel::download(new JobsExport($fromDate, $toDate), $reportName.'.xlsx');
    }

    public function exportDrivers()
    {
        return Excel::download(new DriversExport(), 'drivers_'.today()->toDateString().'.xlsx');
    }

    public function exportDriverJobs($fromDate, $toDate, $driver)
    {
        return Excel::download(new DriverJobsExport($fromDate, $toDate, $driver), 'driver_jobs_'.$fromDate.'_to_'.$toDate.'.xlsx');
    }

    public function exportVendors()
    {
        return Excel::download(new VendorsExport(), 'vendors_'.today()->toDateString().'.xlsx');
    }

    public function exportDeliveryFees($fromDate, $toDate)
    {
        $reportName = auth()->user()->user_type == 'admin' ? "vendors_delivery_fees_".$fromDate.'_to_'.$toDate : auth()->user()->vendor->name."_delivery_fees_".$fromDate.'_to_'.$toDate;
        return Excel::download(new DeliveryFeesExport($fromDate, $toDate), $reportName.'.xlsx');
    }
}
