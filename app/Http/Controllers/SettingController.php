<?php

namespace App\Http\Controllers;

use CargoLogisticsModels\Area;
use CargoLogisticsModels\Country;
use CargoLogisticsModels\Setting;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Setting::orderBy('id', 'asc')->get();
        return view('settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \CargoLogisticsModels\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \CargoLogisticsModels\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \CargoLogisticsModels\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }

    public function createAjax(Request $request)
    {
        try {
            Setting::create([
                'setting_key' => $request->key,
                'setting_value' => $request->value,
                'setting_placeholder' => $request->placeholder
            ]);

            return response()->json(["success" => true, "message" => "$request->key created successfully!"]);
        } catch (\Exception $exception) {
            return response()->json(["success" => false, "message" => $exception->getMessage()]);
        }
    }

    public function updateAjax(Request $request)
    {
        try {
            $setting = Setting::find($request->key);
            $setting->update([
                'setting_value' => $request->value,
                'setting_placeholder' => $request->placeholder
            ]);
            return response()->json(["success" => true, "message" => "$setting->setting_key updated successfully!"]);
        } catch (\Exception $exception) {
            return response()->json(["success" => false, "message" => $exception->getMessage()]);
        }
    }

    public function removeAjax(Request $request)
    {
        try {
            $setting = Setting::find($request->key);
            $setting_key = $setting->setting_key;
            $setting->delete();

            return response()->json(["success" => true, "message" => "$setting_key removed successfully!"]);
        } catch (\Exception $exception) {
            return response()->json(["success" => false, "message" => $exception->getMessage()]);
        }
    }

    public function areas()
    {
        $countries = Country::all();
        $areas = Area::all();
        return view('settings.areas', compact('countries', 'areas'));
    }

    public function storeCountry(Request $request)
    {
        Country::create(['name' => $request->country]);
        return redirect(route('areas'));
    }

    public function updateCountry(Request $request)
    {
        try {
            Country::find($request->country)->update(['name' => $request->name]);
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function destroyCountry($country)
    {
        $item = Country::find($country);
        $item->delete();

        return redirect(route('areas'));
    }

    public function storeArea(Request $request)
    {
        Area::create(['country_id' => $request->countries,'name' => $request->area]);
        return redirect(route('areas'));
    }

    public function updateArea(Request $request)
    {
        try {
            Area::find($request->area)->update(['name' => $request->name]);
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function destroyArea($area)
    {
        $item = Area::find($area);
        $item->delete();

        return redirect(route('areas'));
    }

    public function getAreas($country)
    {
        $areas = Area::where('country_id', '=', $country)->get();
        return view('components.areas.areasList', compact('areas'));
    }
}
