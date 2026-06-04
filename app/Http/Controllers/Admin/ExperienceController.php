<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ExperienceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Experiences/Index', [
            'experiences' => Experience::orderBy('sort_order')->orderByDesc('start_date')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company'          => ['required', 'string', 'max:200'],
            'position'         => ['required', 'string', 'max:200'],
            'location'         => ['nullable', 'string', 'max:200'],
            'start_date'       => ['required', 'date'],
            'end_date'         => ['nullable', 'date'],
            'is_current'       => ['boolean'],
            'description'      => ['required', 'string'],
            'responsibilities' => ['nullable', 'array'],
            'technologies'     => ['nullable', 'array'],
            'is_active'        => ['boolean'],
            'sort_order'       => ['integer'],
        ]);

        if ($request->hasFile('company_logo')) {
            $data['company_logo'] = $request->file('company_logo')->store('experiences', 'public');
        }

        Experience::create($data);
        Cache::forget('portfolio_home');

        return back()->with('success', 'Experience created.');
    }

    public function update(Request $request, Experience $experience): RedirectResponse
    {
        $data = $request->validate([
            'company'          => ['required', 'string', 'max:200'],
            'position'         => ['required', 'string', 'max:200'],
            'location'         => ['nullable', 'string', 'max:200'],
            'start_date'       => ['required', 'date'],
            'end_date'         => ['nullable', 'date'],
            'is_current'       => ['boolean'],
            'description'      => ['required', 'string'],
            'responsibilities' => ['nullable', 'array'],
            'technologies'     => ['nullable', 'array'],
            'is_active'        => ['boolean'],
            'sort_order'       => ['integer'],
        ]);

        if ($request->hasFile('company_logo')) {
            if ($experience->getRawOriginal('company_logo')) {
                Storage::disk('public')->delete($experience->getRawOriginal('company_logo'));
            }
            $data['company_logo'] = $request->file('company_logo')->store('experiences', 'public');
        }

        $experience->update($data);
        Cache::forget('portfolio_home');

        return back()->with('success', 'Experience updated.');
    }

    public function destroy(Experience $experience): RedirectResponse
    {
        if ($experience->getRawOriginal('company_logo')) {
            Storage::disk('public')->delete($experience->getRawOriginal('company_logo'));
        }
        $experience->delete();
        Cache::forget('portfolio_home');

        return back()->with('success', 'Experience deleted.');
    }
}