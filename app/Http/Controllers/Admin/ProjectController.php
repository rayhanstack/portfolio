<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Projects/Index', [
            'projects'   => Project::with('category')->orderBy('sort_order')->get(),
            'categories' => ProjectCategory::all(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Projects/Form', [
            'categories' => ProjectCategory::all(),
            'project'    => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'            => ['required', 'string', 'max:200'],
            'category_id'      => ['nullable', 'exists:project_categories,id'],
            'description'      => ['required', 'string'],
            'full_description' => ['nullable', 'string'],
            'technologies'     => ['nullable', 'array'],
            'features'         => ['nullable', 'array'],
            'live_url'         => ['nullable', 'url'],
            'github_url'       => ['nullable', 'url'],
            'client'           => ['nullable', 'string', 'max:200'],
            'duration'         => ['nullable', 'string', 'max:100'],
            'emoji'            => ['nullable', 'string'],
            'is_featured'      => ['boolean'],
            'is_active'        => ['boolean'],
            'sort_order'       => ['integer'],
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);

        DB::transaction(function () use ($request, &$data) {
            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
            }

            $project = Project::create($data);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $i => $image) {
                    $path = $image->store('projects/images', 'public');
                    $project->images()->create(['image' => $path, 'sort_order' => $i]);
                }
            }
        });

        Cache::forget('portfolio_home');

        return redirect()->route('admin.projects.index')->with('success', 'Project created.');
    }

    public function edit(Project $project): Response
    {
        return Inertia::render('Admin/Projects/Form', [
            'project'    => $project->load('images'),
            'categories' => ProjectCategory::all(),
        ]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $request->validate([
            'title'            => ['required', 'string', 'max:200'],
            'category_id'      => ['nullable', 'exists:project_categories,id'],
            'description'      => ['required', 'string'],
            'full_description' => ['nullable', 'string'],
            'technologies'     => ['nullable', 'array'],
            'features'         => ['nullable', 'array'],
            'live_url'         => ['nullable', 'url'],
            'github_url'       => ['nullable', 'url'],
            'client'           => ['nullable', 'string', 'max:200'],
            'duration'         => ['nullable', 'string', 'max:100'],
            'emoji'            => ['nullable', 'string'],
            'is_featured'      => ['boolean'],
            'is_active'        => ['boolean'],
            'sort_order'       => ['integer'],
        ]);

        DB::transaction(function () use ($request, $project, &$data) {
            if ($request->hasFile('thumbnail')) {
                if ($project->getRawOriginal('thumbnail')) {
                    Storage::disk('public')->delete($project->getRawOriginal('thumbnail'));
                }
                $data['thumbnail'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
            }

            $project->update($data);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $i => $image) {
                    $path = $image->store('projects/images', 'public');
                    $project->images()->create(['image' => $path, 'sort_order' => $project->images()->count() + $i]);
                }
            }
        });

        Cache::forget('portfolio_home');

        return back()->with('success', 'Project updated.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        DB::transaction(function () use ($project) {
            // Delete images from storage
            if ($project->getRawOriginal('thumbnail')) {
                Storage::disk('public')->delete($project->getRawOriginal('thumbnail'));
            }
            foreach ($project->images as $img) {
                Storage::disk('public')->delete($img->getRawOriginal('image'));
            }
            $project->images()->delete();
            $project->delete();
        });

        Cache::forget('portfolio_home');

        return back()->with('success', 'Project deleted.');
    }

    public function destroyImage(ProjectImage $image): RedirectResponse
    {
        Storage::disk('public')->delete($image->getRawOriginal('image'));
        $image->delete();

        return back()->with('success', 'Image removed.');
    }

    // ─── Category CRUD ────────────────────────────────────────────────────────
    public function storeCategory(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);
        $data['slug'] = Str::slug($data['name']);

        ProjectCategory::create($data);

        return back()->with('success', 'Category created.');
    }

    public function destroyCategory(ProjectCategory $category): RedirectResponse
    {
        $category->delete();

        return back()->with('success', 'Category deleted.');
    }
}
