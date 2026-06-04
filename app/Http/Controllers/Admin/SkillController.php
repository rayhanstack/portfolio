<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class SkillController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Skills/Index', [
            'skills' => Skill::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'category'    => ['required', 'string', 'max:100'],
            'percentage'  => ['required', 'integer', 'min:1', 'max:100'],
            'emoji'       => ['nullable', 'string'],
            'color'       => ['nullable', 'string'],
            'is_featured' => ['boolean'],
            'sort_order'  => ['integer'],
        ]);

        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon')->store('skills', 'public');
        }

        Skill::create($data);
        Cache::forget('portfolio_home');

        return back()->with('success', 'Skill created.');
    }

    public function update(Request $request, Skill $skill): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'category'    => ['required', 'string', 'max:100'],
            'percentage'  => ['required', 'integer', 'min:1', 'max:100'],
            'emoji'       => ['nullable', 'string'],
            'color'       => ['nullable', 'string'],
            'is_featured' => ['boolean'],
            'sort_order'  => ['integer'],
        ]);

        if ($request->hasFile('icon')) {
            if ($skill->getRawOriginal('icon')) Storage::disk('public')->delete($skill->getRawOriginal('icon'));
            $data['icon'] = $request->file('icon')->store('skills', 'public');
        }

        $skill->update($data);
        Cache::forget('portfolio_home');

        return back()->with('success', 'Skill updated.');
    }

    public function destroy(Skill $skill): RedirectResponse
    {
        if ($skill->getRawOriginal('icon')) Storage::disk('public')->delete($skill->getRawOriginal('icon'));
        $skill->delete();
        Cache::forget('portfolio_home');

        return back()->with('success', 'Skill deleted.');
    }
}