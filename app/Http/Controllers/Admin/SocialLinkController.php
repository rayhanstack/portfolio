<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class SocialLinkController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/SocialLinks/Index', [
            'links' => SocialLink::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'platform'   => ['required', 'string', 'max:50'],
            'url'        => ['required', 'url'],
            'is_active'  => ['boolean'],
            'sort_order' => ['integer'],
        ]);

        SocialLink::create($request->all());
        Cache::forget('portfolio_home');

        return back()->with('success', 'Social link created.');
    }

    public function update(Request $request, SocialLink $socialLink): RedirectResponse
    {
        $request->validate([
            'platform'   => ['required', 'string', 'max:50'],
            'url'        => ['required', 'url'],
            'is_active'  => ['boolean'],
            'sort_order' => ['integer'],
        ]);

        $socialLink->update($request->all());
        Cache::forget('portfolio_home');

        return back()->with('success', 'Social link updated.');
    }

    public function destroy(SocialLink $socialLink): RedirectResponse
    {
        $socialLink->delete();
        Cache::forget('portfolio_home');

        return back()->with('success', 'Social link deleted.');
    }
}