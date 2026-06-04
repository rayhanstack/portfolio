<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
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
        ]);

        foreach ($fields as $key => $value) {
            Setting::set($key, $value);
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            Setting::set('logo_url', asset('storage/' . $path));
        }
        if ($request->hasFile('favicon')) {
            $request->file('favicon')->storeAs('', 'favicon.ico', 'public');
        }

        Cache::forget('portfolio_home');

        return back()->with('success', 'Settings updated.');
    }
}