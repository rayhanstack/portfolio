<?php

namespace App\Http\Controllers;

use App\Models\HeroSection;
use App\Models\About;
use App\Models\Skill;
use App\Models\Service;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Experience;
use App\Models\Education;
use App\Models\Testimonial;
use App\Models\Certification;
use App\Models\SocialLink;
use App\Models\ContactInfo;
use App\Models\SeoSetting;
use App\Models\Setting;
use App\Models\ContactMessage;
use App\Events\NewContactMessage;
use App\Notifications\NewMessageNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioController extends Controller
{
    public function index(): Response
    {
        $data = Cache::remember('portfolio_home', 300, function () {
            return [
                'hero' => HeroSection::active(),

                'about' => About::where('is_active', true)->first(),

                'skills' => Skill::orderBy('sort_order')->get()->map(fn($s) => [
                    'id'          => $s->id,
                    'name'        => $s->name,
                    'category'    => $s->category,
                    'percentage'  => $s->percentage,
                    'icon'        => $s->icon,
                    'emoji'       => $s->emoji,
                    'is_featured' => $s->is_featured,
                ]),

                'services' => Service::where('is_active', true)
                    ->orderBy('sort_order')->get(),

                'projects' => Project::with(['category', 'images'])
                    ->active()
                    ->orderBy('sort_order')
                    ->orderByDesc('is_featured')
                    ->get()
                    ->map(fn($p) => [
                        'id'               => $p->id,
                        'title'            => $p->title,
                        'slug'             => $p->slug,
                        'description'      => $p->description,
                        'full_description' => $p->full_description,
                        'thumbnail'        => $p->thumbnail,
                        'technologies'     => $p->technologies,
                        'features'         => $p->features,
                        'live_url'         => $p->live_url,
                        'github_url'       => $p->github_url,
                        'client'           => $p->client,
                        'duration'         => $p->duration,
                        'emoji'            => $p->emoji,
                        'is_featured'      => $p->is_featured,
                        'category_id'      => $p->category_id,
                        'category'         => $p->category?->name,
                    ]),

                'projectCategories' => ProjectCategory::withCount('projects')
                    ->having('projects_count', '>', 0)
                    ->get(['id', 'name', 'slug', 'color']),

                'experiences' => Experience::where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderByDesc('start_date')
                    ->get()
                    ->map(fn($e) => [
                        'id'               => $e->id,
                        'company'          => $e->company,
                        'position'         => $e->position,
                        'location'         => $e->location,
                        'company_logo'     => $e->company_logo,
                        'start_date'       => $e->start_date?->format('M Y'),
                        'end_date'         => $e->is_current ? 'Present' : $e->end_date?->format('M Y'),
                        'is_current'       => $e->is_current,
                        'description'      => $e->description,
                        'responsibilities' => $e->responsibilities,
                        'technologies'     => $e->technologies,
                    ]),

                'educations' => Education::where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderByDesc('start_year')
                    ->get(),

                'testimonials' => Testimonial::where('is_active', true)
                    ->orderBy('sort_order')
                    ->get(),

                'certifications' => Certification::where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderByDesc('issue_date')
                    ->get(),

                'socialLinks' => SocialLink::where('is_active', true)
                    ->orderBy('sort_order')
                    ->get(['id', 'platform', 'url']),

                'contactInfo' => ContactInfo::where('is_active', true)->first(),

                'settings' => [
                    'site_name'    => Setting::get('site_name', config('app.name')),
                    'logo_text'    => Setting::get('logo_text', 'P'),
                    'footer_text'  => Setting::get('footer_text'),
                    'logo_url'     => Setting::get('logo_url'),
                ],

                'seo' => SeoSetting::active(),
            ];
        });

        return Inertia::render('Frontend/Home', $data);
    }

    public function contact(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:500'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

       $message = ContactMessage::create([
            ...$validated,
            'ip_address' => $request->ip(),
        ]);

        // Optionally: send notification email to admin
        // Mail::to(config('mail.admin_email'))->send(new ContactNotification($validated));

        // ── 1. Real-time broadcast via Laravel Echo (Pusher/Reverb) ──────────
        // Instantly updates the admin bell badge if the tab is open.
        // Requires: BROADCAST_DRIVER=pusher or reverb in .env
        broadcast(new NewContactMessage($message))->toOthers();

        // ── 2. Web Push + Database notification to all admin users ────────────
        // Sends a browser push notification even when the admin tab is closed.
        // Queued so it doesn't block the HTTP response.
        $admins = User::all(); // or: User::where('is_admin', true)->get()
        Notification::send($admins, new NewMessageNotification($message));

        return response()->json(['message' => 'Message sent successfully.'], 201);
    }

    public function sitemap()
    {
        $projects = Project::active()->get(['slug', 'updated_at']);

        $content = view('sitemap', compact('projects'))->render();

        return response($content, 200, ['Content-Type' => 'application/xml']);
    }
}