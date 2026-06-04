<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class SeoController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Seo/Index', [
            'seo' => SeoSetting::first() ?? new SeoSetting(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'meta_title'          => ['nullable', 'string', 'max:70'],
            'meta_description'    => ['nullable', 'string', 'max:160'],
            'keywords'            => ['nullable', 'string'],
            'og_title'            => ['nullable', 'string', 'max:70'],
            'og_description'      => ['nullable', 'string', 'max:200'],
            'twitter_card'        => ['nullable', 'string'],
            'twitter_title'       => ['nullable', 'string'],
            'twitter_description' => ['nullable', 'string'],
            'schema_markup'       => ['nullable', 'string'],
        ]);

        if ($request->hasFile('og_image')) {
            $data['og_image'] = $request->file('og_image')->store('seo', 'public');
        }

        SeoSetting::updateOrCreate(['id' => 1], [...$data, 'is_active' => true]);
        Cache::forget('portfolio_home');

        return back()->with('success', 'SEO settings updated.');
    }
}