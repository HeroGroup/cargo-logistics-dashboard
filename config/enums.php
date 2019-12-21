<?php
use CargoLogisticsModels\Setting;
return [
    'user_types' => [
        'admin' => 'Admin',
        'vendor' => 'Vendor',
        'vendor_branch' => 'Vendor Branch',
        'driver' => 'Driver',
    ],

    'transport_mode' => [
        'Car' => 'Car',
        'Walker' => 'Walker',
        'Subway' => 'Subway',
        'Bicycle' => 'Bicycle',
        'Uber' => 'Uber',
        'Truck' => 'Truck',
        'Taxi' => 'Taxi',
        'Van' => 'Van',
        'Drone' => 'Drone',
        'Motorbike' => 'Motorbike',
        'Scooter' => 'Scooter',
    ],

    'driver_types' => [
        'salary_based' => 'Salary Based',
        'commission_based' => 'Commission Based',
    ],

    'place_type' => [
        'shop' => 'Shop',
        'apartment' => 'Apartment',
        'house' => 'House',
    ],

    'account_status' => [
        'unapproved' => 'Unapproved',
        'approved' => 'Approved',
        'inactive' => 'Inactive',
    ],

    'job_status' => [
        'canceled' => 'Canceled',
        'new' => 'New',
        'accepted' => 'Accepted',
        'completed' => 'Completed'
    ],


    'job_history_bg' => [
        'create' => 'bg-primary',
        'accepted' => 'bg-secondary',
        'assigned' => 'bg-secondary',
        'started' => 'bg-success',
        'completed' => 'bg-info',
        'abandoned' => 'bg-warning',
        'canceled' => 'bg-danger',
    ],


    'job_history_icon' => [
        'create' => 'icon-pencil-alt',
        'accepted' => 'icon-image',
        'assigned' => 'icon-image',
        'started' => 'icon-video-camera',
        'completed' => 'icon-pulse',
        'abandoned' => 'icon-image',
        'canceled' => 'icon-pencil-alt',
    ],

];
