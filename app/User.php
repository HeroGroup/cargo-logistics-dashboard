<?php

namespace App;

use CargoLogisticsModels\Driver;
use CargoLogisticsModels\Vendor;
use CargoLogisticsModels\VendorBranchAccount;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'mobile', 'password', 'user_type', 'vendor_id', 'driver_id', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function vendorBranches()
    {
        return $this->hasMany(VendorBranchAccount::class);
    }

}
