<?php

namespace App\Http\Middleware;

use CargoLogisticsModels\VendorBranchAccount;
use Closure;

class Branch
{
    public function handle($request, Closure $next)
    {
        if (auth()->user()->user_type == 'branch' &&
            VendorBranchAccount::where('user_id', '=', auth()->user()->id)->first() &&
            !session('branch')) {
            session()->put('branch', VendorBranchAccount::where('user_id', '=', auth()->user()->id)->first()->vendor_branch_id);
        }

        return $next($request);
    }
}
