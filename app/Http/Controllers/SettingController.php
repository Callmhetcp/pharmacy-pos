<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        $setting = Setting::first();

        return view('settings.index', compact('setting'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([

        'pharmacy_name' => 'required|string|max:255',

        'phone' => 'nullable|string|max:30',

        'email' => 'nullable|email',

        'address' => 'nullable',

        'currency' => 'required|string|max:10',

        'tax' => 'required|numeric|min:0',

        'receipt_footer' => 'nullable',

        'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

    ]);

    $setting = Setting::first();

    if (!$setting) {
        $setting = new Setting();
    }

    $setting->pharmacy_name = $request->pharmacy_name;
    $setting->phone = $request->phone;
    $setting->email = $request->email;
    $setting->address = $request->address;
    $setting->currency = $request->currency;
    $setting->tax = $request->tax;
    $setting->receipt_footer = $request->receipt_footer;

    if ($request->hasFile('logo')) {

        // Delete old logo
        if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
            Storage::disk('public')->delete($setting->logo);
        }

        // Save new logo
        $setting->logo = $request->file('logo')->store('settings', 'public');
    }

    $setting->save();

    return redirect()
        ->route('settings.index')
        ->with('success', 'Settings updated successfully.');
}
    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
