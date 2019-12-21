<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JobsExport implements FromCollection, WithHeadings
{
    protected $fromDate;
    protected $toDate;
    public function __construct($fromDate, $toDate)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function collection()
    {
        $export = DB::table('jobs')
            ->join('drivers', 'jobs.driver_id', '=', 'drivers.id')
            ->whereBetween('jobs.created_at', [$this->fromDate, date('Y-m-d', strtotime($this->toDate . "+1 days"))])
            ->select(
                'jobs.unique_number',
                'drivers.name',
                'jobs.status',
                'jobs.pickup_address',
                'jobs.pickup_description',
                'jobs.pickup_date',
                'jobs.pickup_time',
                'jobs.pickup_contact_person',
                'jobs.dropoff_address',
                'jobs.dropoff_description',
                'jobs.dropoff_date',
                'jobs.dropoff_time',
                'jobs.dropoff_contact_person',
                'jobs.distance',
                'jobs.created_at'
            )
            ->orderBy('jobs.id');

        $userType = auth()->user()->user_type;
        if ($userType == 'vendor')
            $export->where('vendor_id', '=', auth()->user()->vendor_id);
        elseif ($userType == 'branch')
            $export->where('vendor_branch_id', '=', session('branch'));

        return $export->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'DRIVER',
            'STATUS',
            'PICKUP ADDRESS',
            'PICKUP DESCRIPTION',
            'PICKUP DATE',
            'PICKUP TIME',
            'PICKUP CONTACT PERSON',
            'DROP OFF ADDRESS',
            'DROP OFF DESCRIPTION',
            'DROP OFF DATE',
            'DROP OFF TIME',
            'DROP OFF CONTACT PERSON',
            'DISTANCE',
            'CREATED AT'
        ];
    }

}
