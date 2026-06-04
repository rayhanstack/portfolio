<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class EducationController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Educations/Index', [
            'educations' => Education::orderBy('sort_order')->orderByDesc('start_year')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'institution'    => ['required', 'string', 'max:200'],
            'degree'         => ['required', 'string', 'max:200'],
            'field_of_study' => ['nullable', 'string', 'max:200'],
            'location'       => ['nullable', 'string', 'max:200'],
            'start_year'     => ['required', 'integer', 'min:1950', 'max:2100'],
            'end_year'       => ['nullable', 'integer'],
            'is_current'     => ['boolean'],
            'result_gpa'     => ['nullable', 'string', 'max:50'],
            'description'    => ['nullable', 'string'],
            'is_active'      => ['boolean'],
            'sort_order'     => ['integer'],
        ]);

        if ($request->hasFile('institution_logo')) {
            $data['institution_logo'] = $request->file('institution_logo')->store('educations', 'public');
        }

        Education::create($data);
        Cache::forget('portfolio_home');

        return back()->with('success', 'Education created.');
    }

    public function update(Request $request, Education $education): RedirectResponse
    {
        $data = $request->validate([
            'institution'    => ['required', 'string', 'max:200'],
            'degree'         => ['required', 'string', 'max:200'],
            'field_of_study' => ['nullable', 'string', 'max:200'],
            'location'       => ['nullable', 'string', 'max:200'],
            'start_year'     => ['required', 'integer'],
            'end_year'       => ['nullable', 'integer'],
            'is_current'     => ['boolean'],
            'result_gpa'     => ['nullable', 'string'],
            'description'    => ['nullable', 'string'],
            'is_active'      => ['boolean'],
            'sort_order'     => ['integer'],
        ]);

        if ($request->hasFile('institution_logo')) {
            if ($education->getRawOriginal('institution_logo')) {
                Storage::disk('public')->delete($education->getRawOriginal('institution_logo'));
            }
            $data['institution_logo'] = $request->file('institution_logo')->store('educations', 'public');
        }

        $education->update($data);
        Cache::forget('portfolio_home');

        return back()->with('success', 'Education updated.');
    }

    public function destroy(Education $education): RedirectResponse
    {
        $education->delete();
        Cache::forget('portfolio_home');

        return back()->with('success', 'Education deleted.');
    }
}