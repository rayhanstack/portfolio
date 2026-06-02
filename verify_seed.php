<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(\Illuminate\Http\Request::capture());

echo "✓ Users: " . \App\Models\User::count() . "\n";
echo "✓ HeroSections: " . \App\Models\HeroSection::count() . "\n";
echo "✓ Skills: " . \App\Models\Skill::count() . "\n";
echo "✓ Services: " . \App\Models\Service::count() . "\n";
echo "✓ ProjectCategories: " . \App\Models\ProjectCategory::count() . "\n";
echo "✓ Projects: " . \App\Models\Project::count() . "\n";
echo "✓ Experiences: " . \App\Models\Experience::count() . "\n";
echo "✓ Educations: " . \App\Models\Education::count() . "\n";
echo "✓ Testimonials: " . \App\Models\Testimonial::count() . "\n";
echo "✓ Certifications: " . \App\Models\Certification::count() . "\n";
echo "✓ SocialLinks: " . \App\Models\SocialLink::count() . "\n";
echo "✓ ContactInfo: " . \App\Models\ContactInfo::count() . "\n";
echo "✓ SeoSettings: " . \App\Models\SeoSetting::count() . "\n";
echo "✓ Settings: " . \App\Models\Setting::count() . "\n";
