<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class SettingController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Settings/Index', [
            'settings' => Setting::all()->keyBy('key'),
        ]);
    }

    public function update(Request $request): RedirectResponse
{
    $fields = $request->validate([
        'site_name'   => ['nullable', 'string', 'max:100'],
        'logo_text'   => ['nullable', 'string', 'max:5'],
        'footer_text' => ['nullable', 'string', 'max:500'],
        'logo'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:2048'],
        'favicon'     => ['nullable', 'image', 'mimes:ico,png', 'max:1024'],
    ]);

    foreach ([
        'site_name',
        'logo_text',
        'footer_text',
    ] as $key) {
        if (array_key_exists($key, $fields)) {
            Setting::set($key, $fields[$key]);
        }
    }

    // Upload Logo
    if ($request->hasFile('logo')) {

        // Delete old logo if exists
        $oldLogo = Setting::get('logo_path');

        if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
            Storage::disk('public')->delete($oldLogo);
        }

        $path = $request->file('logo')->store('settings', 'public');

        // Save relative path instead of full URL
        Setting::set('logo_path', $path);
    }

    // Upload Favicon
    if ($request->hasFile('favicon')) {
        $request->file('favicon')->storeAs(
            'settings',
            'favicon.ico',
            'public'
        );

        Setting::set('favicon_path', 'settings/favicon.ico');
    }

    Cache::forget('portfolio_home');

    return redirect()
        ->back()
        ->with('success', 'Settings updated successfully.');
}
}