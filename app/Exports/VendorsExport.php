<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VendorsExport implements FromCollection, WithHeadings
{

    public function collection()
    {
        $export = DB::table('vendors')
            ->join('vendor_subscriptions', 'vendors.id', '=', 'vendor_subscriptions.vendor_id')
            ->select(
                'vendors.name',
                'vendors.email',
                'vendors.phone',
                'vendors.mobile',
                'vendors.contact_person',
                'vendors.block',
                'vendors.street',
                'vendors.avenue',
                'vendors.building_number',
                'vendors.place_type',
                'vendors.delivery_fee',
                'vendors.service_charge',
                'vendors.account_status',
                'vendor_subscriptions.from_date',
                'vendor_subscriptions.to_date'
            );

        return $export->get();
    }

    public function headings(): array
    {
        return [
            'NAME',
            'EMAIL',
            'PHONE',
            'MOBILE',
            'CONTACT PERSON',
            'BLOCK',
            'STREET',
            'AVENUE',
            'BUILDING NUMBER',
            'PLACE TYPE',
            'DELIVERY FEE',
            'SERVICE CHARGE',
            'ACCOUNT STATUS',
            'SUBSCRIPTION FROM',
            'SUBSCRIPTION TO',
        ];
    }

}
