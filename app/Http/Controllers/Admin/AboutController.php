<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/About/Index', [
            'about' => About::first() ?? new About(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name'       => ['required', 'string', 'max:200'],
            'bio'             => ['required', 'string'],
            'email'           => ['nullable', 'email'],
            'phone'           => ['nullable', 'string', 'max:30'],
            'location'        => ['nullable', 'string', 'max:200'],
            'nationality'     => ['nullable', 'string', 'max:100'],
            'date_of_birth'   => ['nullable', 'string'],
            'languages'       => ['nullable', 'string'],
            'freelance_status' => ['nullable', 'string'],
            'counters'        => ['nullable', 'array'],
            'is_active'       => ['boolean'],
        ]);

        $about = About::firstOrNew(['id' => 1]);

        if ($request->hasFile('profile_image')) {
            if ($about->getRawOriginal('profile_image')) {
                Storage::disk('public')->delete($about->getRawOriginal('profile_image'));
            }
            $data['profile_image'] = $request->file('profile_image')->store('about', 'public');
        }

        if ($request->hasFile('resume_file')) {
            if ($about->getRawOriginal('resume_file')) {
                Storage::disk('public')->delete($about->getRawOriginal('resume_file'));
            }
            $data['resume_file'] = $request->file('resume_file')->store('resumes', 'public');
        }

        $about->fill($data)->save();
        Cache::forget('portfolio_home');

        return back()->with('success', 'About section updated.');
    }
}