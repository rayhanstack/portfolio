<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class TestimonialController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Testimonials/Index', [
            'testimonials' => Testimonial::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'client_name'  => ['required', 'string', 'max:200'],
            'designation'  => ['required', 'string', 'max:200'],
            'company'      => ['nullable', 'string', 'max:200'],
            'review'       => ['required', 'string'],
            'rating'       => ['required', 'integer', 'min:1', 'max:5'],
            'is_active'    => ['boolean'],
            'sort_order'   => ['integer'],
        ]);

        if ($request->hasFile('client_image')) {
            $data['client_image'] = $request->file('client_image')->store('testimonials', 'public');
        }

        Testimonial::create($data);
        Cache::forget('portfolio_home');

        return back()->with('success', 'Testimonial created.');
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $data = $request->validate([
            'client_name'  => ['required', 'string', 'max:200'],
            'designation'  => ['required', 'string', 'max:200'],
            'company'      => ['nullable', 'string', 'max:200'],
            'review'       => ['required', 'string'],
            'rating'       => ['required', 'integer', 'min:1', 'max:5'],
            'is_active'    => ['boolean'],
            'sort_order'   => ['integer'],
        ]);

        if ($request->hasFile('client_image')) {
            if ($testimonial->getRawOriginal('client_image')) {
                Storage::disk('public')->delete($testimonial->getRawOriginal('client_image'));
            }
            $data['client_image'] = $request->file('client_image')->store('testimonials', 'public');
        }

        $testimonial->update($data);
        Cache::forget('portfolio_home');

        return back()->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        if ($testimonial->getRawOriginal('client_image')) {
            Storage::disk('public')->delete($testimonial->getRawOriginal('client_image'));
        }
        $testimonial->delete();
        Cache::forget('portfolio_home');

        return back()->with('success', 'Testimonial deleted.');
    }
}