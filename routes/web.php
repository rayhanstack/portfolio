<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\CertificationController;
use App\Http\Controllers\Admin\ContactInfoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EducationController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\HeroController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\PushSubscriptionController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

// ─── Frontend Routes ─────────────────────────────────────────────────────────
Route::get('/', [PortfolioController::class, 'index'])->name('home');
Route::post('/contact', [PortfolioController::class, 'contact'])->name('contact.store');
Route::get('/sitemap.xml', [PortfolioController::class, 'sitemap'])->name('sitemap');

// ─── Auth Routes (Breeze/Jetstream or custom) ────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store']);
});

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified'])
    ->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Hero
    Route::get('/hero', [HeroController::class, 'index'])->name('hero.index');
    Route::post('/hero', [HeroController::class, 'update'])->name('hero.update');

    // About
    Route::get('/about', [AboutController::class, 'index'])->name('about.index');
    Route::post('/about', [AboutController::class, 'update'])->name('about.update');

    // Skills
    Route::get('/skills', [SkillController::class, 'index'])->name('skills.index');
    Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');
    Route::put('/skills/{skill}', [SkillController::class, 'update'])->name('skills.update');
    Route::delete('/skills/{skill}', [SkillController::class, 'destroy'])->name('skills.destroy');

    // Services
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::delete('/project-images/{image}', [ProjectController::class, 'destroyImage'])->name('projects.images.destroy');
    Route::post('/project-categories', [ProjectController::class, 'storeCategory'])->name('projects.categories.store');
    Route::delete('/project-categories/{category}', [ProjectController::class, 'destroyCategory'])->name('projects.categories.destroy');

    // Experience
    Route::get('/experiences', [ExperienceController::class, 'index'])->name('experiences.index');
    Route::post('/experiences', [ExperienceController::class, 'store'])->name('experiences.store');
    Route::put('/experiences/{experience}', [ExperienceController::class, 'update'])->name('experiences.update');
    Route::delete('/experiences/{experience}', [ExperienceController::class, 'destroy'])->name('experiences.destroy');

    // Education
    Route::get('/educations', [EducationController::class, 'index'])->name('educations.index');
    Route::post('/educations', [EducationController::class, 'store'])->name('educations.store');
    Route::put('/educations/{education}', [EducationController::class, 'update'])->name('educations.update');
    Route::delete('/educations/{education}', [EducationController::class, 'destroy'])->name('educations.destroy');

    // Testimonials
    Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
    Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
    Route::put('/testimonials/{testimonial}', [TestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

    // Certifications
    Route::get('/certifications', [CertificationController::class, 'index'])->name('certifications.index');
    Route::post('/certifications', [CertificationController::class, 'store'])->name('certifications.store');
    Route::put('/certifications/{certification}', [CertificationController::class, 'update'])->name('certifications.update');
    Route::delete('/certifications/{certification}', [CertificationController::class, 'destroy'])->name('certifications.destroy');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{message}', [MessageController::class, 'show'])->name('messages.show');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/messages/bulk-delete', [MessageController::class, 'bulkDestroy'])->name('messages.bulk-destroy');

    // Social Links
    Route::get('/social-links', [SocialLinkController::class, 'index'])->name('social-links.index');
    Route::post('/social-links', [SocialLinkController::class, 'store'])->name('social-links.store');
    Route::put('/social-links/{socialLink}', [SocialLinkController::class, 'update'])->name('social-links.update');
    Route::delete('/social-links/{socialLink}', [SocialLinkController::class, 'destroy'])->name('social-links.destroy');

    // Contact Info
    Route::get('/contact-info', [ContactInfoController::class, 'index'])->name('contact-info.index');
    Route::post('/contact-info', [ContactInfoController::class, 'update'])->name('contact-info.update');

    // SEO
    Route::get('/seo', [SeoController::class, 'index'])->name('seo.index');
    Route::post('/seo', [SeoController::class, 'update'])->name('seo.update');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // ── Push Notifications (NEW) ──────────────────────────────────────────────
    Route::prefix('push')->name('push.')->group(function () {
        // Returns VAPID public key for PushManager.subscribe()
        Route::get('vapid-public-key',        [PushSubscriptionController::class, 'vapidPublicKey'])->name('vapid-key');
        // Save browser push subscription
        Route::post('subscribe',              [PushSubscriptionController::class, 'subscribe'])->name('subscribe');
        // Remove browser push subscription
        Route::post('unsubscribe',            [PushSubscriptionController::class, 'unsubscribe'])->name('unsubscribe');
        // Check if current browser is subscribed
        Route::get('status',                  [PushSubscriptionController::class, 'status'])->name('status');
        // Send test push notification to self
        Route::post('test',                   [PushSubscriptionController::class, 'test'])->name('test');
        // In-app bell: list database notifications
        Route::get('notifications',           [PushSubscriptionController::class, 'notifications'])->name('notifications');
        // Mark one notification read
        Route::post('notifications/{id}/read',[PushSubscriptionController::class, 'markRead'])->name('notifications.read');
        // Mark all read
        Route::post('notifications/mark-all-read', [PushSubscriptionController::class, 'markAllRead'])->name('notifications.mark-all-read');
    });
});