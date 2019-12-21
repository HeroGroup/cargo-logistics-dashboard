<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DriversExport implements FromCollection, WithHeadings
{

    public function collection()
    {
        return DB::table('drivers')
            ->select(
                'name',
                'email',
                'phone',
                'transport_mode',
                'driver_type',
                'fixed_commission',
                'commission_percent',
                'availability',
                'account_status',
                'last_active'
            )
            ->orderBy('id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'NAME',
            'EMAIL',
            'PHONE',
            'VEHICLE TYPE',
            'CONTRACT TYPE',
            'FIXED COMMISSION',
            'COMMISSION PERCENT',
            'LATEST AVAILABILITY',
            'ACCOUNT STATUS',
            'LAST ACTIVE'
        ];
    }

}
