<?php

namespace App\Http\Controllers;

use App\User;
use CargoLogisticsModels\Area;
use CargoLogisticsModels\Country;
use CargoLogisticsModels\Vendor;
use CargoLogisticsModels\VendorSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\Count;

class VendorController extends Controller
{
    public function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:50'],
            'logo' => ['nullable', 'file', 'max:512000'],
            'email' => ['nullable', 'email', 'max:191'],
            'phone' => ['nullable', 'size:8'],
            'menu' => ['nullable', 'file', 'max:1024000'],
            'contact_person' => ['required', 'string', 'max:50'],
            'country_id' => ['required'],
            'area_id' => ['required'],
            'block' => ['required', 'string', 'max:50'],
            'street' => ['required', 'string', 'max:50'],
            'avenue' => ['nullable', 'max:50'],
            'building_number' => ['required', 'string', 'max:50'],
            'place_type' => ['nullable', 'string', 'max:50'],
        ]);
    }

    public function createValidator(array $data)
    {
        return Validator::make($data, [
            'logo' => ['required', 'file', 'max:512000'],
            'email' => ['nullable', 'unique:vendors'],
            'mobile' => ['required', 'string', 'size:8', 'unique:vendors'],
//            'subscribe_from' => ['required', 'date'],
//            'subscribe_to' => ['required', 'date'],
        ]);
    }

    public function passwordValidator(array $data)
    {
        return Validator::make($data, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function index($filter)
    {
        if (Auth::user()->user_type == 'admin') {
            $vendors = $filter == 'all' ? Vendor::all() : Vendor::where('account_status', '=', $filter)->get();
            return view('vendors.index', compact('filter', 'vendors'));
        } else {
            return redirect(route('dashboard'));
        }
    }

    public function create()
    {
        $countries = Country::pluck('name', 'id')->toArray();
        return view('vendors.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $this->validator($request->all())->validate();
        $this->createValidator($request->all())->validate();
        $this->passwordValidator($request->all())->validate();

        $data = $request->toArray();
        $data['password'] = Hash::make($request->password);
        $data['logo'] = $request->hasFile('logo') ? $this->saveVendorFile($request->logo) : null;
        $data['menu'] = $request->hasFile('menu') ? $this->saveVendorFile($request->menu) : null;
        $data['access_admin_drivers'] = $request->access_admin_drivers == "on" ? 1 : 0;
        $data['has_own_drivers'] = $request->has_own_drivers == "on" ? 1 : 0;

        $vendor = new Vendor($data);
        $vendor->save();

        if ($request->subscribe_from && $request->subscribe_to) {
            VendorSubscription::create([
                'vendor_id' => $vendor->id,
                'from_date' => $request->subscribe_from,
                'to_date' => $request->subscribe_to,
            ]);
        }

        // CREATE USER
        User::create([
            'name' => $vendor->name,
            'mobile' => $vendor->mobile,
            'password' => Hash::make($request->password),
            'user_type' => 'vendor',
            'vendor_id' => $vendor->id
        ]);

        return redirect(route('vendors.index', 'all'));
    }

    public function edit(Vendor $vendor)
    {
        $countries = Country::pluck('name', 'id')->toArray();
        $areas = Area::where('country_id', '=', $vendor->country_id)->pluck('name', 'id')->toArray();

        return view('vendors.edit', compact('vendor', 'countries', 'areas'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $this->validator($request->all())->validate();
        $data = $request->toArray();

        if ($request->hasFile('logo'))
            $data['logo'] = $this->saveVendorFile($request->logo);

        if ($request->hasFile('menu'))
            $data['menu'] = $this->saveVendorFile($request->menu);

        if ($request->access_admin_drivers == "on")
            $data['access_admin_drivers'] = 1;
        else
            $data['access_admin_drivers'] = 0;

        if ($request->has_own_drivers == "on")
            $data['has_own_drivers'] = 1;
        else
            $data['has_own_drivers'] = 0;

        $vendor->update($data);

        return redirect(route('vendors.index', 'all'));
    }

    public function destroy(Vendor $vendor)
    {
        return redirect(route('vendors.index', 'all'));
    }

    public function changePassword(Vendor $vendor)
    {
        return view('vendors.changePassword', compact('vendor'));
    }

    public function updatePassword(Request $request, Vendor $vendor)
    {
        // if (Hash::check($request->old_password, $vendor->password)) {

        $this->passwordValidator($request->all())->validate();
        $newPassword = Hash::make($request->password);
        $vendor->update([
            'password' => $newPassword
        ]);

        $user = User::where('vendor_id', '=', $vendor->id)->where('user_type', 'LIKE', 'vendor')->first();
        $user->update([
            'password' => $newPassword
        ]);

        // } else {
        // return redirect(route('vendors.changePassword', $vendor->id))->with('messageOldPassword', 'current password is incorrect');
        // }

        return redirect(route('vendors.index', 'all'));
    }

    public function saveVendorFile($file)
    {
        $fileName = time() . '.' . $file->getClientOriginalName();
        $file->move('resources/assets/images/vendor_images/', $fileName);
        return '/resources/assets/images/vendor_images/'.$fileName;
    }

    public function showVendorsLog(Vendor $vendor)
    {
        return view('vendors.showLogs', compact('vendor'));
    }

    public function subscription(Vendor $vendor)
    {
        $subscriptionId = VendorSubscription::where('vendor_id', '=', $vendor->id)->max('id');
        $subscription = VendorSubscription::find($subscriptionId);
        $subscriptions = VendorSubscription::where('id', '!=', $subscriptionId)->get();
        return view('vendors.subscription', compact('vendor', 'subscription', 'subscriptions'));
    }

    public function updateSubscription(Request $request, Vendor $vendor, VendorSubscription $subscription = null)
    {
        if ($subscription)
            $subscription->update($request->toArray());
        else
            VendorSubscription::create([
                'vendor_id' => $vendor->id,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
            ]);

        return redirect(route('vendors.index', 'all'));
    }

    public function approve($vendorId)
    {
        $vendor = Vendor::find($vendorId);
        $vendor->update(['account_status' => 'approved']);
        return redirect(route('vendors.index', 'all'));
    }

}
