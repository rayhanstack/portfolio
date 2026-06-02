# 🚀 Premium Portfolio Platform

A modern, fully dynamic portfolio website built with **Laravel 12**, **Inertia.js**, **Vue 3**, **Tailwind CSS**, and **MySQL** — featuring a complete admin dashboard for managing all content.

---

## ✨ Features

### Frontend
- **Hero Section** — Full-screen with typing animation, particle background, floating stats
- **About Me** — Profile, bio, animated counters, CV download
- **Skills** — Category tabs, progress bars, circular indicators
- **Services** — Glassmorphism cards with hover animations
- **Projects** — Masonry grid, category filter, detail modal, tech tags
- **Experience** — Animated vertical timeline
- **Education** — Academic timeline with GPA
- **Testimonials** — Swiper carousel with star ratings
- **Certifications** — Card grid with verification links
- **Contact** — Form with validation, info cards
- **Dark / Light Mode** — Toggle with localStorage persistence (no FOUC)
- **SEO Optimized** — Dynamic meta, OG tags, Twitter cards, sitemap
- **Fully Responsive** — Mobile → Ultra-wide
- **GSAP + AOS Animations** — Smooth scroll-reveal effects
- **NProgress** — Route progress bar

### Admin Panel (`/admin`)
- **Secure Auth** — Login, remember me, logout
- **Dashboard** — Stats overview, recent messages
- **CRUD** for every section: Hero, About, Skills, Services, Projects (with multi-image), Experience, Education, Testimonials, Certifications
- **Project Categories** — Full management
- **Social Links** — Platform icons, ordering
- **Contact Info** — Phone, email, address, map embed
- **Messages** — Read/unread, bulk delete, pagination
- **SEO Manager** — Meta, OG, Twitter, JSON-LD schema
- **Site Settings** — Name, logo, footer text, favicon

---

## 🛠 Tech Stack

| Layer       | Technology                            |
|-------------|---------------------------------------|
| Backend     | Laravel 12, PHP 8.2+                  |
| Frontend    | Vue 3 (Composition API), Inertia.js   |
| Styling     | Tailwind CSS 3, Custom CSS vars       |
| State       | Pinia                                 |
| Build       | Vite                                  |
| Database    | MySQL 8+                              |
| Animations  | GSAP, AOS                             |
| Carousel    | Swiper                                |
| Typing      | Typed.js                              |
| Auth        | Laravel built-in + Sanctum            |

---

## 📁 Project Structure

```
portfolio/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # All admin CRUD controllers
│   │   │   │   ├── AdminControllers.php  # Skills, Services, Experience, etc.
│   │   │   │   ├── ProjectController.php # Project + image management
│   │   │   │   └── DashboardController.php
│   │   │   ├── Auth/
│   │   │   │   └── AuthenticatedSessionController.php
│   │   │   └── PortfolioController.php  # Frontend + contact
│   │   └── Middleware/
│   │       └── HandleInertiaRequests.php
│   └── Models/
│       ├── HeroSection.php
│       ├── Project.php
│       ├── Skill.php
│       └── PortfolioModels.php   # All other models
│
├── database/
│   ├── migrations/              # 4 migration files covering all 16 tables
│   └── seeders/
│       └── DatabaseSeeder.php   # Full sample data
│
├── resources/
│   ├── css/app.css              # Tailwind + custom styles
│   └── js/
│       ├── app.js               # Inertia entry point
│       ├── Stores/
│       │   ├── themeStore.js    # Dark/light mode
│       │   └── portfolioStore.js
│       ├── Layouts/
│       │   ├── FrontendLayout.vue  # Public layout with navbar/footer
│       │   └── AdminLayout.vue     # Admin sidebar layout
│       ├── Pages/
│       │   ├── Frontend/
│       │   │   └── Home.vue     # Main portfolio page
│       │   ├── Admin/
│       │   │   ├── Dashboard.vue
│       │   │   ├── Skills/Index.vue
│       │   │   ├── Projects/Index.vue
│       │   │   ├── Projects/Form.vue
│       │   │   ├── Experiences/Index.vue
│       │   │   ├── Hero/Index.vue
│       │   │   ├── About/Index.vue
│       │   │   ├── Messages/Index.vue
│       │   │   ├── Seo/Index.vue
│       │   │   ├── Settings/Index.vue
│       │   │   ├── SocialLinks/Index.vue
│       │   │   └── ContactInfo/Index.vue
│       │   └── Auth/
│       │       └── Login.vue
│       └── Components/
│           └── Frontend/
│               ├── HeroSection.vue
│               ├── AboutSection.vue
│               ├── SkillsSection.vue
│               ├── ServicesSection.vue
│               ├── ProjectsSection.vue
│               ├── ExperienceSection.vue
│               ├── EducationSection.vue
│               ├── TestimonialsSection.vue
│               ├── CertificationsSection.vue
│               └── ContactSection.vue
│
└── routes/
    └── web.php                  # All frontend + admin routes
```

---

## ⚡ Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+ & npm
- MySQL 8+

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/your-username/portfolio.git
cd portfolio

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Configure environment
cp .env.example .env
php artisan key:generate

# 5. Set up database in .env
DB_DATABASE=portfolio_db
DB_USERNAME=your_user
DB_PASSWORD=your_password

# 6. Run migrations and seed sample data
php artisan migrate --seed

# 7. Create storage link
php artisan storage:link

# 8. Build frontend assets
npm run build
# OR for development with hot reload:
npm run dev

# 9. Start the server
php artisan serve
```

### Access
| URL | Description |
|-----|-------------|
| `http://localhost:8000` | Portfolio frontend |
| `http://localhost:8000/admin` | Admin dashboard |
| `http://localhost:8000/admin/login` | Admin login |

### Default Admin Credentials
```
Email:    admin@portfolio.com
Password: password
```
> ⚠️ Change these immediately after first login in production!

---

## 🗃 Database Tables

| Table               | Description                        |
|---------------------|------------------------------------|
| `users`             | Admin users                        |
| `settings`          | Key-value site config              |
| `hero_sections`     | Hero content + media               |
| `abouts`            | About me info + counters           |
| `skills`            | Skills with categories             |
| `services`          | Service offerings                  |
| `project_categories`| Project category taxonomy          |
| `projects`          | Portfolio projects                 |
| `project_images`    | Multiple images per project        |
| `experiences`       | Work history timeline              |
| `educations`        | Academic history                   |
| `testimonials`      | Client reviews                     |
| `certifications`    | Professional certificates          |
| `social_links`      | Social media profiles              |
| `contact_infos`     | Contact details                    |
| `contact_messages`  | Form submissions inbox             |
| `seo_settings`      | SEO meta configuration             |

---

## 🔐 Security
- CSRF protection on all forms
- Auth middleware on all `/admin/*` routes
- Server-side validation on every input
- SQL injection prevention via Eloquent ORM
- File upload validation (type, size)
- XSS protection via Inertia's built-in escaping

---

## 🚀 Production Deployment

```bash
# Optimize for production
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build

# Set APP_ENV=production, APP_DEBUG=false in .env
```

---

## 📄 License
MIT — free to use for personal and commercial portfolios.
