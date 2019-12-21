<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdministratorController extends Controller
{

    public function createValidator(array $data)
    {
        return Validator::make($data, [
            'email' => ['unique:users'],
            'mobile' => ['unique:users'],
        ]);
    }

    public function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:191'],
            'email' => ['nullable', 'string', 'email', 'max:191'],
            'mobile' => ['required', 'string', 'min:11', 'max:13'],
        ]);
    }

    public function passwordValidator(array $data)
    {
        return Validator::make($data, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function customValidator(array $data, $userId)
    {
        $errors = [];
        $user = User::find($userId);
        if ($user->mobile != $data['mobile']) {
            if (User::where('mobile', 'LIKE', $data['mobile'])->count() > 0) {
                $errors['mobile'] = 'This mobile number is already taken.';
            }
        }

        if ($user->email != $data['email']) {
            if (User::where('email', 'LIKE', $data['email'])->count() > 0) {
                $errors['email'] = 'This email address is already taken.';
            }
        }

        return $errors;
    }

    public function index()
    {
        if (Auth::user()->user_type == 'admin') {
            $administrators = User::where('user_type', 'LIKE', 'admin')->get();
            return view('administrators.index', compact('administrators'));
        } else {
            return '/dashboard';
        }
    }

    public function create()
    {
        return view('administrators.create');
    }

    public function store(Request $request)
    {
        $this->createValidator($request->only('email', 'mobile'))->validate();
        $this->validator($request->only(['name', 'email', 'mobile']))->validate();
        $this->passwordValidator($request->only(['password', 'password_confirmation']))->validate();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'user_type' => 'admin'
        ]);

        return redirect(route('administrators.index'));
    }

    public function edit($user)
    {
        $user = User::find($user);
        return view('administrators.edit', compact('user'));
    }

    public function update(Request $request, $userId)
    {
        $user = User::find($userId);
        $this->validator($request->only(['name', 'email', 'mobile']))->validate();
        $errors = $this->customValidator($request->only(['email', 'mobile']), $user->id);

        if (count($errors) > 0)
            return redirect(route('administrators.edit', $userId))
                ->withErrors($errors)
                ->withInput();

        if ($request->password)
            $this->passwordValidator($request->only(['password', 'password_confirmation']))->validate();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect(route('administrators.index'));
    }

    public function destroy($user)
    {
        User::find($user)->delete();
        return redirect(route('administrators.index'));
    }

}
