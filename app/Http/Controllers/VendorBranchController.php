<?php

namespace App\Http\Controllers;

use App\User;
use CargoLogisticsModels\Area;
use CargoLogisticsModels\Country;
use CargoLogisticsModels\Vendor;
use CargoLogisticsModels\VendorBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\customValidators as customValidator;

class VendorBranchController extends Controller
{
    public function validator(array $data)
    {
        return Validator::make($data, [
            'vendor_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:50'],
            'mobile' => ['required', 'string', 'size:8'],
            'country_id' => ['required'],
            'area_id' => ['required'],
            'block' => ['required', 'string', 'max:50'],
            'street' => ['required', 'string', 'max:50'],
            'avenue' => ['nullable', 'max:50'],
            'building_number' => ['required', 'string', 'max:50'],
            'place_type' => ['nullable', 'string', 'max:50']
        ]);
    }

    public function createValidator(array $data)
    {
        return Validator::make($data, [
            'mobile' => ['unique:vendor_branches']
        ]);
    }

    public function customValidator($userId, array $data)
    {
        $errors = [];
        $branch = VendorBranch::find($userId);
        if ($branch->mobile != $data['mobile']) {
            if (VendorBranch::where('mobile', 'LIKE', $data['mobile'])->count() > 0) {
                $errors['mobile'] = 'This mobile number is already taken.';
            }
        }

        return $errors;
    }

    public function index($vendorId)
    {
        $branches = VendorBranch::where('vendor_id', '=', $vendorId)->get();
        return view('vendors.branches', compact('vendorId', 'branches'));
    }

    public function create($vendorId)
    {
        $countries = Country::pluck('name', 'id')->toArray();
        return view('vendors.branches.create', compact('vendorId', 'countries'));
    }

    public function store(Request $request)
    {
        $this->validator($request->all())->validate();
        $this->createValidator($request->all())->validate();
        customValidator::passwordValidator($request->only(['password', 'password_confirmation']))->validate();

        $branch = new VendorBranch($request->toArray());
        $branch->save();

        return redirect(route('vendors.branches', $request->vendor_id));
    }

    public function edit($vendorId, VendorBranch $branch)
    {
        $countries = Country::pluck('name', 'id')->toArray();
        $areas = Area::where('country_id', '=', $branch->country_id)->pluck('name', 'id')->toArray();

        return view('vendors.branches.edit', compact('vendorId', 'branch', 'countries', 'areas'));
    }

    public function update(Request $request, $vendorId, VendorBranch $branch)
    {
        // CHECK MOBILE UNIQUENESS
        $errors = $this->customValidator($branch->id, $request->toArray());
        if (count($errors) > 0) {
            return redirect(route('vendors.branches.edit', ['vendor' => $vendorId, 'branch' => $branch]))
                ->withErrors($errors)
                ->withInput();
        }

        $branch->update($request->toArray());

        return redirect(route('vendors.branches', $vendorId));
    }

    public function destroy($vendorId, VendorBranch $branch)
    {
        $branch->delete();

        return redirect(route('vendors.branches', $vendorId));
    }

    public function getBranches($vendor)
    {
        $branches = VendorBranch::where('vendor_id', '=', $vendor)->get();
        return view('components.branchesList', compact('branches'));
    }

}
