<?php

namespace App\Http\Controllers;

use App\User;
use CargoLogisticsModels\VendorBranch;
use CargoLogisticsModels\VendorBranchAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class VendorAccountController extends Controller
{
    public function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:191'],
            'mobile' => ['required', 'string', 'size:8'],
            'user_type' => ['required'],
            'vendor_id' => ['required'],
        ]);
    }

    public function createValidator(array $data)
    {
        return Validator::make($data, [
            'email' => ['nullable', 'unique:users'],
            'mobile' => ['unique:users'],
        ]);
    }

    public function passwordValidator(array $data)
    {
        return Validator::make($data, [
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);
    }

    public function customValidator($userId, array $data)
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

    public function index($vendorId)
    {
        $accounts = User::where([['user_type', 'LIKE', 'branch'], ['vendor_id', '=', $vendorId]])->get();
        $branches = VendorBranch::where('vendor_id', '=', $vendorId)->get();
        return view('vendors.accounts.index', compact('vendorId', 'accounts', 'branches'));
    }

    public function create($vendorId)
    {
        return view('vendors.accounts.create', compact('vendorId'));
    }

    public function store(Request $request)
    {
        $this->validator($request->all())->validate();
        $this->createValidator($request->all())->validate();
        $this->passwordValidator($request->all())->validate();

        $data = $request->toArray();
        $data['password'] = Hash::make($request->password);
        User::create($data);
        return redirect(route('vendors.accounts', $request->vendor_id));
    }

    public function edit($vendorId, User $account)
    {
        return view('vendors.accounts.edit', compact('vendorId', 'account'));
    }

    public function update(Request $request, $vendorId, User $account)
    {
        $this->validator($request->all())->validate();

        // check email and mobile uniqueness
        $errors = $this->customValidator($account->id, $request->toArray());
        if (count($errors) > 0) {
            return redirect(route('vendors.accounts.edit', ['vendor' => $vendorId, 'account' => $account->id]))
                ->withErrors($errors)
                ->withInput();
        }

        $account->update($request->toArray());
        return redirect(route('vendors.accounts', $vendorId));
    }

    public function destroy($vendorId, User $account)
    {
        $account->delete();
        return redirect(route('vendors.accounts', $vendorId));
    }

    public function changePassword($vendorId, User $account)
    {
        return view('vendors.accounts.changePassword', compact('vendorId', 'account'));
    }

    public function updatePassword(Request $request, $vendorId, User $account)
    {
        $this->passwordValidator($request->all())->validate();
        $account->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect(route('vendors.accounts', $vendorId));
    }

    public function getAssignBranches($vendorId, User $account)
    {
        return view('vendors.accounts.assignBranches', compact('vendorId', 'account'));
    }

    public function postAssignBranches($vendorId, Request $request)
    {
        try {
            VendorBranchAccount::create([
                'vendor_branch_id' => $request->branch,
                'user_id' => $request->user,
                'is_active' => 1
            ]);

            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'fail',
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function postRevokeBranches($vendorId, Request $request)
    {
        try {
            VendorBranchAccount::where([
                ['vendor_branch_id', '=', $request->branch],
                ['user_id', '=', $request->user]
            ])->delete();

            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'fail',
                'message' => $exception->getMessage()
            ]);
        }
    }

}
