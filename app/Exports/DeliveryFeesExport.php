<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DeliveryFeesExport implements FromCollection, WithHeadings
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
        $export = DB::table('vendors')
            ->join('jobs', 'vendors.id', '=', 'jobs.vendor_id')
            ->whereBetween('jobs.created_at', [$this->fromDate, date('Y-m-d', strtotime($this->toDate . "+1 days"))])
            ->select(
                'vendors.name',
                'jobs.unique_number',
                'vendors.delivery_fee',
                'vendors.service_charge'
            );

        if (auth()->user()->user_type == 'vendor')
            $export->where('jobs.vendor_id', '=', auth()->user()->vendor_id);

        if (auth()->user()->user_type == 'branch')
            $export->where('jobs.vendor_branch_id', '=', session('branch'));

        return $export->get();
    }

    public function headings(): array
    {
        return [
            'VENDOR NAME',
            'JOB ID',
            'DELIVERY FEE',
            'SERVICE_CHARGE'
        ];
    }

}
