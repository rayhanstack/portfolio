<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class ContactInfoController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/ContactInfo/Index', [
            'contactInfo' => ContactInfo::first() ?? new ContactInfo(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email'         => ['nullable', 'email'],
            'phone'         => ['nullable', 'string', 'max:30'],
            'address'       => ['nullable', 'string'],
            'map_embed_url' => ['nullable', 'string'],
        ]);

        ContactInfo::updateOrCreate(['id' => 1], [...$data, 'is_active' => true]);
        Cache::forget('portfolio_home');

        return back()->with('success', 'Contact info updated.');
    }
}