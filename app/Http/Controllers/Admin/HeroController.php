<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class HeroController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Hero/Index', [
            'hero' => HeroSection::first() ?? new HeroSection(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'             => ['required', 'string', 'max:200'],
            'designation'      => ['nullable', 'string', 'max:200'],
            'designations'     => ['nullable', 'array'],
            'tagline'          => ['nullable', 'string', 'max:500'],
            'years_experience' => ['nullable', 'integer'],
            'projects_count'   => ['nullable', 'integer'],
            'clients_count'    => ['nullable', 'integer'],
            'is_active'        => ['boolean'],
        ]);

        $hero = HeroSection::firstOrNew(['id' => 1]);

        if ($request->hasFile('profile_image')) {
            if ($hero->getRawOriginal('profile_image')) {
                Storage::disk('public')->delete($hero->getRawOriginal('profile_image'));
            }
            $data['profile_image'] = $request->file('profile_image')->store('hero', 'public');
        }

        if ($request->hasFile('resume')) {
            if ($hero->getRawOriginal('resume_url')) {
                Storage::disk('public')->delete($hero->getRawOriginal('resume_url'));
            }
            $data['resume_url'] = $request->file('resume')->store('resumes', 'public');
        }

        $hero->fill($data)->save();
        Cache::forget('portfolio_home');

        return back()->with('success', 'Hero section updated.');
    }
}