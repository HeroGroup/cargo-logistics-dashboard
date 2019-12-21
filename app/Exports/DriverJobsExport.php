<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DriverJobsExport implements FromCollection, WithHeadings
{
    protected $driverId, $fromDate, $toDate;

    public function __construct($from, $to, $driver)
    {
        $this->driverId = $driver;
        $this->fromDate = $from;
        $this->toDate = $to;
    }

    public function collection()
    {
        return DB::table('jobs')
            ->join('drivers', 'jobs.driver_id', '=', 'drivers.id')
            ->where('driver_id', '=', $this->driverId)
            ->whereBetween('jobs.created_at', [$this->fromDate, date('Y-m-d', strtotime($this->toDate . "+1 days"))])
            ->select(
                'drivers.name',
                'jobs.unique_number',
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
            ->orderBy('jobs.id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'DRIVER',
            'ID',
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
