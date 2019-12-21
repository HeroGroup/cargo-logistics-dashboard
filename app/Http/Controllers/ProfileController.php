<?php

namespace App\Http\Controllers;

use App\User;
use CargoLogisticsModels\Vendor;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function getProfile()
    {
        $user = User::find(auth()->user()->id);

        switch ($user->user_type) {
            case 'admin':
                return redirect(route('administrators.edit', $user->id));
                break;
            case 'vendor':
                return view('editUser', compact('user'));
                break;
            case 'branch':
                return view('editUser', compact('user'));
                break;
            case 'driver':
                return '';
                break;
            default:
                break;
        }
    }

    public function updateProfile(Request $request, User $user)
    {
        $data = $request->toArray();
        if (! $request->password)
            unset($data['password']);

        $user->update($data);
        return redirect('/dashboard');
    }
}
