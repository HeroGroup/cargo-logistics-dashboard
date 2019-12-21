<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Vendor
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()->user_type == 'vendor') {
            $vendor = \CargoLogisticsModels\Vendor::find(Auth::user()->vendor_id);
            if ($vendor) {
                if ($vendor->account_status == 'approved') {
                    if (request('vendor')) {
                        $type = gettype(request('vendor'));

                        if ($type == 'string' && (int)request('vendor') != Auth::user()->vendor_id)
                            return abort(404);
                        elseif ($type == 'object' && (int)request('vendor')->id != Auth::user()->vendor_id)
                            return abort(404);
                        else
                            return $next($request);
                    } else {
                        return $next($request);
                    }
                } else {
                    return redirect('notApproved');
                }
            } else {
                return abort(404);
            }
        } elseif (Auth::user()->user_type != 'admin') {
            return $next($request);
        } else {
            return $next($request);
        }
    }
}
