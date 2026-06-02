<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\Service;
use App\Models\Experience;
use App\Models\Education;
use App\Models\Testimonial;
use App\Models\Certification;
use App\Models\SocialLink;
use App\Models\ContactMessage;
use App\Models\SeoSetting;
use App\Models\Setting;
use App\Models\HeroSection;
use App\Models\About;
use App\Models\ContactInfo;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

// ─── Skills ──────────────────────────────────────────────────────────────────
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

// ─── Services ────────────────────────────────────────────────────────────────
class ServiceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Services/Index', [
            'services' => Service::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'icon_svg'    => ['nullable', 'string'],
            'color'       => ['nullable', 'string'],
            'is_active'   => ['boolean'],
            'sort_order'  => ['integer'],
        ]);

        Service::create($data);
        Cache::forget('portfolio_home');

        return back()->with('success', 'Service created.');
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'icon_svg'    => ['nullable', 'string'],
            'color'       => ['nullable', 'string'],
            'is_active'   => ['boolean'],
            'sort_order'  => ['integer'],
        ]);

        $service->update($data);
        Cache::forget('portfolio_home');

        return back()->with('success', 'Service updated.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();
        Cache::forget('portfolio_home');

        return back()->with('success', 'Service deleted.');
    }
}

// ─── Experiences ─────────────────────────────────────────────────────────────
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

// ─── Education ───────────────────────────────────────────────────────────────
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

// ─── Testimonials ─────────────────────────────────────────────────────────────
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

// ─── Certifications ───────────────────────────────────────────────────────────
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

// ─── Contact Messages ─────────────────────────────────────────────────────────
class MessageController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Messages/Index', [
            'messages' => ContactMessage::latest()->paginate(20),
        ]);
    }

    public function show(ContactMessage $message): Response
    {
        if (!$message->is_read) $message->markAsRead();

        return Inertia::render('Admin/Messages/Show', [
            'message' => $message,
        ]);
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return back()->with('success', 'Message deleted.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        ContactMessage::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Messages deleted.');
    }
}

// ─── SEO Settings ─────────────────────────────────────────────────────────────
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

// ─── Site Settings ────────────────────────────────────────────────────────────
class SettingController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Settings/Index', [
            'settings' => Setting::all()->keyBy('key'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $fields = $request->validate([
            'site_name'   => ['nullable', 'string', 'max:100'],
            'logo_text'   => ['nullable', 'string', 'max:5'],
            'footer_text' => ['nullable', 'string', 'max:500'],
        ]);

        foreach ($fields as $key => $value) {
            Setting::set($key, $value);
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            Setting::set('logo_url', asset('storage/' . $path));
        }
        if ($request->hasFile('favicon')) {
            $request->file('favicon')->storeAs('', 'favicon.ico', 'public');
        }

        Cache::forget('portfolio_home');

        return back()->with('success', 'Settings updated.');
    }
}

// ─── Social Links ─────────────────────────────────────────────────────────────
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

// ─── Contact Info ─────────────────────────────────────────────────────────────
class ContactInfoController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/ContactInfo/Index', [
            'contactInfo' => ContactInfo::first() ?? new ContactInfo(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email'         => ['nullable', 'email'],
            'phone'         => ['nullable', 'string', 'max:30'],
            'address'       => ['nullable', 'string'],
            'map_embed_url' => ['nullable', 'string'],
        ]);

        ContactInfo::updateOrCreate(['id' => 1], [...$data, 'is_active' => true]);
        Cache::forget('portfolio_home');

        return back()->with('success', 'Contact info updated.');
    }
}

// ─── Hero Management ─────────────────────────────────────────────────────────
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

// ─── About Management ─────────────────────────────────────────────────────────
class AboutController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/About/Index', [
            'about' => About::first() ?? new About(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name'       => ['required', 'string', 'max:200'],
            'bio'             => ['required', 'string'],
            'email'           => ['nullable', 'email'],
            'phone'           => ['nullable', 'string', 'max:30'],
            'location'        => ['nullable', 'string', 'max:200'],
            'nationality'     => ['nullable', 'string', 'max:100'],
            'date_of_birth'   => ['nullable', 'string'],
            'languages'       => ['nullable', 'string'],
            'freelance_status' => ['nullable', 'string'],
            'counters'        => ['nullable', 'array'],
            'is_active'       => ['boolean'],
        ]);

        $about = About::firstOrNew(['id' => 1]);

        if ($request->hasFile('profile_image')) {
            if ($about->getRawOriginal('profile_image')) {
                Storage::disk('public')->delete($about->getRawOriginal('profile_image'));
            }
            $data['profile_image'] = $request->file('profile_image')->store('about', 'public');
        }

        if ($request->hasFile('resume_file')) {
            if ($about->getRawOriginal('resume_file')) {
                Storage::disk('public')->delete($about->getRawOriginal('resume_file'));
            }
            $data['resume_file'] = $request->file('resume_file')->store('resumes', 'public');
        }

        $about->fill($data)->save();
        Cache::forget('portfolio_home');

        return back()->with('success', 'About section updated.');
    }
}
