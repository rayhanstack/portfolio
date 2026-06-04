<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class CertificationController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Certifications/Index', [
            'certifications' => Certification::orderBy('sort_order')->orderByDesc('issue_date')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'            => ['required', 'string', 'max:200'],
            'organization'     => ['required', 'string', 'max:200'],
            'issue_date'       => ['required', 'date'],
            'expiry_date'      => ['nullable', 'date'],
            'credential_id'    => ['nullable', 'string', 'max:200'],
            'verification_url' => ['nullable', 'url'],
            'is_active'        => ['boolean'],
            'sort_order'       => ['integer'],
        ]);

        if ($request->hasFile('certificate_image')) {
            $data['certificate_image'] = $request->file('certificate_image')->store('certifications', 'public');
        }

        Certification::create($data);
        Cache::forget('portfolio_home');

        return back()->with('success', 'Certification created.');
    }

    public function update(Request $request, Certification $certification): RedirectResponse
    {
        $data = $request->validate([
            'title'            => ['required', 'string', 'max:200'],
            'organization'     => ['required', 'string', 'max:200'],
            'issue_date'       => ['required', 'date'],
            'expiry_date'      => ['nullable', 'date'],
            'credential_id'    => ['nullable', 'string', 'max:200'],
            'verification_url' => ['nullable', 'url'],
            'is_active'        => ['boolean'],
            'sort_order'       => ['integer'],
        ]);

        if ($request->hasFile('certificate_image')) {
            if ($certification->getRawOriginal('certificate_image')) {
                Storage::disk('public')->delete($certification->getRawOriginal('certificate_image'));
            }
            $data['certificate_image'] = $request->file('certificate_image')->store('certifications', 'public');
        }

        $certification->update($data);
        Cache::forget('portfolio_home');

        return back()->with('success', 'Certification updated.');
    }

    public function destroy(Certification $certification): RedirectResponse
    {
        if ($certification->getRawOriginal('certificate_image')) {
            Storage::disk('public')->delete($certification->getRawOriginal('certificate_image'));
        }
        $certification->delete();
        Cache::forget('portfolio_home');

        return back()->with('success', 'Certification deleted.');
    }
}