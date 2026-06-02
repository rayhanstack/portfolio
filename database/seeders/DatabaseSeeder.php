<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\HeroSection;
use App\Models\About;
use App\Models\Skill;
use App\Models\Service;
use App\Models\ProjectCategory;
use App\Models\Project;
use App\Models\Experience;
use App\Models\Education;
use App\Models\Testimonial;
use App\Models\Certification;
use App\Models\SocialLink;
use App\Models\ContactInfo;
use App\Models\SeoSetting;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin User ───────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@portfolio.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        // ─── Site Settings ────────────────────────────────────────────────────
        Setting::set('site_name',   'John Doe Portfolio');
        Setting::set('logo_text',   'JD');
        Setting::set('footer_text', '© 2025 John Doe. Built with ❤️ using Laravel & Vue.');

        // ─── Hero Section ─────────────────────────────────────────────────────
        HeroSection::create([
            'name'             => 'John Doe',
            'designation'      => 'Full Stack Developer',
            'designations'     => [
                'Full Stack Developer',
                'Laravel Expert',
                'Vue.js Enthusiast',
                'UI/UX Designer',
                'Problem Solver',
            ],
            'tagline'          => 'I craft elegant, scalable web applications that deliver exceptional user experiences. Passionate about clean code and modern technologies.',
            'years_experience' => 5,
            'projects_count'   => 50,
            'clients_count'    => 30,
            'is_active'        => true,
        ]);

        // ─── About ────────────────────────────────────────────────────────────
        About::create([
            'full_name'       => 'John Doe',
            'bio'             => "I'm a passionate Full Stack Developer with 5+ years of experience building modern web applications. I specialize in Laravel, Vue.js, and cloud technologies. My journey started with a simple curiosity about how websites work, and has grown into a deep passion for crafting exceptional digital experiences.\n\nI believe in writing clean, maintainable code and following best practices. When I'm not coding, you'll find me exploring new technologies, contributing to open source, or mentoring aspiring developers.",
            'email'           => 'john@example.com',
            'phone'           => '+1 (555) 123-4567',
            'location'        => 'San Francisco, CA',
            'nationality'     => 'American',
            'date_of_birth'   => 'January 15, 1993',
            'languages'       => 'English (Native), Spanish (Intermediate)',
            'freelance_status'=> 'Available',
            'counters'        => [
                ['label' => 'Projects Completed', 'value' => 50,  'suffix' => '+'],
                ['label' => 'Happy Clients',       'value' => 30,  'suffix' => '+'],
                ['label' => 'Years Experience',    'value' => 5,   'suffix' => '+'],
                ['label' => 'Awards Won',          'value' => 10,  'suffix' => '+'],
            ],
            'is_active'       => true,
        ]);

        // ─── Skills ───────────────────────────────────────────────────────────
        $skills = [
            ['name' => 'Laravel',       'category' => 'Backend',     'percentage' => 95, 'emoji' => '🔴', 'is_featured' => true,  'sort_order' => 1],
            ['name' => 'Vue.js',        'category' => 'Frontend',    'percentage' => 90, 'emoji' => '💚', 'is_featured' => true,  'sort_order' => 2],
            ['name' => 'PHP',           'category' => 'Backend',     'percentage' => 92, 'emoji' => '🐘', 'is_featured' => false, 'sort_order' => 3],
            ['name' => 'JavaScript',    'category' => 'Frontend',    'percentage' => 88, 'emoji' => '💛', 'is_featured' => true,  'sort_order' => 4],
            ['name' => 'MySQL',         'category' => 'Database',    'percentage' => 85, 'emoji' => '🗄️', 'is_featured' => false, 'sort_order' => 5],
            ['name' => 'React.js',      'category' => 'Frontend',    'percentage' => 75, 'emoji' => '⚛️', 'is_featured' => false, 'sort_order' => 6],
            ['name' => 'Tailwind CSS',  'category' => 'Frontend',    'percentage' => 92, 'emoji' => '🎨', 'is_featured' => false, 'sort_order' => 7],
            ['name' => 'Docker',        'category' => 'DevOps',      'percentage' => 70, 'emoji' => '🐳', 'is_featured' => false, 'sort_order' => 8],
            ['name' => 'AWS',           'category' => 'DevOps',      'percentage' => 65, 'emoji' => '☁️', 'is_featured' => false, 'sort_order' => 9],
            ['name' => 'Git',           'category' => 'Tools',       'percentage' => 90, 'emoji' => '📦', 'is_featured' => false, 'sort_order' => 10],
            ['name' => 'Communication', 'category' => 'Soft Skills', 'percentage' => 88, 'emoji' => '💬', 'is_featured' => true,  'sort_order' => 11],
            ['name' => 'Problem Solving','category' => 'Soft Skills','percentage' => 92, 'emoji' => '🧩', 'is_featured' => true,  'sort_order' => 12],
        ];

        foreach ($skills as $s) Skill::create($s);

        // ─── Services ─────────────────────────────────────────────────────────
        $services = [
            ['title' => 'Web Development',     'description' => 'Full-stack web applications built with Laravel, Vue.js, and modern technologies. Scalable, secure, and performant.', 'icon_svg' => '🌐', 'sort_order' => 1],
            ['title' => 'API Development',     'description' => 'RESTful and GraphQL APIs designed for performance and scalability. Comprehensive documentation and testing included.', 'icon_svg' => '🔌', 'sort_order' => 2],
            ['title' => 'UI/UX Design',        'description' => 'Beautiful, intuitive interfaces that users love. From wireframes to pixel-perfect implementations with Figma and Tailwind.', 'icon_svg' => '🎨', 'sort_order' => 3],
            ['title' => 'Database Design',     'description' => 'Optimized database schemas, migrations, and query optimization for MySQL, PostgreSQL, and NoSQL databases.', 'icon_svg' => '🗄️', 'sort_order' => 4],
            ['title' => 'Performance Optimization', 'description' => 'Speed up your existing applications with caching strategies, query optimization, and CDN configuration.', 'icon_svg' => '⚡', 'sort_order' => 5],
            ['title' => 'Consulting',          'description' => 'Technical consultation for your project — architecture planning, code reviews, and best practice guidance.', 'icon_svg' => '💼', 'sort_order' => 6],
        ];

        foreach ($services as $s) Service::create(array_merge($s, ['is_active' => true]));

        // ─── Project Categories ────────────────────────────────────────────────
        $webCat = ProjectCategory::create(['name' => 'Web Apps',   'slug' => 'web-apps',   'color' => '#4466f3']);
        $apiCat = ProjectCategory::create(['name' => 'APIs',       'slug' => 'apis',       'color' => '#8b5cf6']);
        $uiCat  = ProjectCategory::create(['name' => 'UI/UX',      'slug' => 'ui-ux',      'color' => '#00d4ff']);

        // ─── Projects ─────────────────────────────────────────────────────────
        $projects = [
            [
                'category_id'      => $webCat->id,
                'title'            => 'E-Commerce Platform',
                'slug'             => 'e-commerce-platform',
                'description'      => 'A full-featured e-commerce platform with real-time inventory, payment processing, and advanced analytics.',
                'full_description' => 'Built a comprehensive e-commerce solution handling 10,000+ products with real-time inventory management. Features include Stripe payment processing, advanced product filtering, order tracking, and a powerful admin dashboard with sales analytics.',
                'technologies'     => ['Laravel', 'Vue.js', 'MySQL', 'Redis', 'Stripe', 'Tailwind CSS'],
                'features'         => ['Real-time inventory management', 'Stripe payment integration', 'Advanced search and filtering', 'Order tracking system', 'Sales analytics dashboard'],
                'live_url'         => 'https://example.com',
                'github_url'       => 'https://github.com',
                'client'           => 'TechCorp Inc.',
                'duration'         => '3 months',
                'emoji'            => '🛒',
                'is_featured'      => true,
                'sort_order'       => 1,
            ],
            [
                'category_id'      => $apiCat->id,
                'title'            => 'RESTful API Service',
                'slug'             => 'restful-api-service',
                'description'      => 'High-performance REST API serving 1M+ requests per day with comprehensive documentation.',
                'full_description' => 'Designed and implemented a highly scalable RESTful API built with Laravel Sanctum authentication, rate limiting, and comprehensive Swagger documentation. Handles millions of requests daily with 99.9% uptime.',
                'technologies'     => ['Laravel', 'PHP 8.2', 'MySQL', 'Redis', 'Docker', 'Swagger'],
                'features'         => ['JWT & Sanctum authentication', 'Rate limiting', 'Swagger documentation', 'Comprehensive testing', 'Redis caching'],
                'live_url'         => null,
                'github_url'       => 'https://github.com',
                'emoji'            => '🔌',
                'is_featured'      => true,
                'sort_order'       => 2,
            ],
            [
                'category_id'      => $uiCat->id,
                'title'            => 'Portfolio CMS',
                'slug'             => 'portfolio-cms',
                'description'      => 'This very portfolio platform — a dynamic content management system built with Laravel + Inertia + Vue 3.',
                'full_description' => 'A premium portfolio CMS featuring a fully dynamic admin panel, dark/light mode, GSAP animations, SEO optimization, and a beautiful modern design.',
                'technologies'     => ['Laravel 12', 'Vue 3', 'Inertia.js', 'Tailwind CSS', 'Pinia', 'MySQL'],
                'features'         => ['Dynamic admin panel', 'Dark/light mode', 'GSAP animations', 'SEO management', 'Responsive design'],
                'live_url'         => 'https://example.com',
                'github_url'       => 'https://github.com',
                'emoji'            => '🚀',
                'is_featured'      => true,
                'sort_order'       => 3,
            ],
        ];

        foreach ($projects as $p) Project::create(array_merge($p, ['is_active' => true]));

        // ─── Experiences ──────────────────────────────────────────────────────
        Experience::create([
            'company'          => 'TechCorp Inc.',
            'position'         => 'Senior Full Stack Developer',
            'location'         => 'San Francisco, CA',
            'start_date'       => '2022-01-01',
            'is_current'       => true,
            'description'      => 'Leading development of enterprise web applications serving 50,000+ users.',
            'responsibilities' => [
                'Architected and built scalable Laravel APIs',
                'Led a team of 4 developers',
                'Implemented CI/CD pipelines with GitHub Actions',
                'Reduced page load time by 60% through optimization',
            ],
            'technologies'     => ['Laravel', 'Vue.js', 'MySQL', 'Docker', 'AWS'],
            'is_active'        => true,
            'sort_order'       => 1,
        ]);

        Experience::create([
            'company'          => 'StartupXYZ',
            'position'         => 'Full Stack Developer',
            'location'         => 'Remote',
            'start_date'       => '2019-06-01',
            'end_date'         => '2021-12-31',
            'is_current'       => false,
            'description'      => 'Built the entire product from scratch — a SaaS platform for project management.',
            'responsibilities' => [
                'Developed RESTful APIs with Laravel',
                'Built Vue.js frontend from scratch',
                'Integrated payment gateways (Stripe)',
                'Managed AWS infrastructure',
            ],
            'technologies'     => ['Laravel', 'Vue.js', 'PostgreSQL', 'Stripe'],
            'is_active'        => true,
            'sort_order'       => 2,
        ]);

        // ─── Educations ───────────────────────────────────────────────────────
        Education::create([
            'institution'    => 'University of California, Berkeley',
            'degree'         => 'Bachelor of Science',
            'field_of_study' => 'Computer Science',
            'location'       => 'Berkeley, CA',
            'start_year'     => 2011,
            'end_year'       => 2015,
            'result_gpa'     => '3.8 / 4.0',
            'description'    => 'Graduated with honors. Focused on software engineering, algorithms, and distributed systems.',
            'is_active'      => true,
            'sort_order'     => 1,
        ]);

        // ─── Testimonials ─────────────────────────────────────────────────────
        Testimonial::create([
            'client_name' => 'Sarah Johnson',
            'designation' => 'CEO',
            'company'     => 'TechCorp Inc.',
            'review'      => 'John delivered an exceptional e-commerce platform that exceeded all our expectations. His attention to detail and technical expertise is unmatched. Highly recommended!',
            'rating'      => 5,
            'is_active'   => true,
            'sort_order'  => 1,
        ]);

        Testimonial::create([
            'client_name' => 'Michael Chen',
            'designation' => 'CTO',
            'company'     => 'StartupXYZ',
            'review'      => 'Working with John was a pleasure. He built our entire SaaS platform from scratch and consistently delivered high-quality code on time. A true professional.',
            'rating'      => 5,
            'is_active'   => true,
            'sort_order'  => 2,
        ]);

        Testimonial::create([
            'client_name' => 'Emma Williams',
            'designation' => 'Product Manager',
            'company'     => 'DigitalWave',
            'review'      => 'Exceptional developer! John transformed our outdated system into a modern, performant application. The results have been phenomenal — 60% faster load times!',
            'rating'      => 5,
            'is_active'   => true,
            'sort_order'  => 3,
        ]);

        // ─── Certifications ────────────────────────────────────────────────────
        Certification::create([
            'title'            => 'AWS Certified Developer',
            'organization'     => 'Amazon Web Services',
            'issue_date'       => '2023-06-15',
            'credential_id'    => 'AWS-DEV-2023-001',
            'verification_url' => 'https://aws.amazon.com/verify',
            'is_active'        => true,
            'sort_order'       => 1,
        ]);

        Certification::create([
            'title'            => 'Laravel Certified Developer',
            'organization'     => 'Laravel LLC',
            'issue_date'       => '2022-03-10',
            'verification_url' => 'https://laravel.com/verify',
            'is_active'        => true,
            'sort_order'       => 2,
        ]);

        // ─── Social Links ─────────────────────────────────────────────────────
        $socials = [
            ['platform' => 'github',    'url' => 'https://github.com/johndoe',     'sort_order' => 1],
            ['platform' => 'linkedin',  'url' => 'https://linkedin.com/in/johndoe','sort_order' => 2],
            ['platform' => 'twitter',   'url' => 'https://twitter.com/johndoe',    'sort_order' => 3],
            ['platform' => 'instagram', 'url' => 'https://instagram.com/johndoe',  'sort_order' => 4],
        ];

        foreach ($socials as $s) SocialLink::create(array_merge($s, ['is_active' => true]));

        // ─── Contact Info ──────────────────────────────────────────────────────
        ContactInfo::create([
            'email'     => 'john@example.com',
            'phone'     => '+1 (555) 123-4567',
            'address'   => '123 Developer Street, San Francisco, CA 94102, USA',
            'is_active' => true,
        ]);

        // ─── SEO Settings ─────────────────────────────────────────────────────
        SeoSetting::create([
            'meta_title'       => 'John Doe | Full Stack Developer — Laravel & Vue.js Expert',
            'meta_description' => 'Experienced full stack developer specializing in Laravel, Vue.js, and modern web technologies. Available for freelance and full-time opportunities.',
            'keywords'         => 'full stack developer, laravel developer, vue.js developer, web development, PHP developer',
            'og_title'         => 'John Doe — Full Stack Developer',
            'og_description'   => 'Building exceptional web applications with Laravel & Vue.js. 5+ years of experience.',
            'twitter_card'     => 'summary_large_image',
            'is_active'        => true,
        ]);
    }
}
