<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Service;
use App\Models\Experience;
use App\Models\ContactMessage;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'projects'     => Project::count(),
                'skills'       => Skill::count(),
                'services'     => Service::count(),
                'experiences'  => Experience::count(),
                'messages'     => ContactMessage::count(),
                'unread'       => ContactMessage::where('is_read', false)->count(),
            ],
            'recentMessages' => ContactMessage::latest()
                ->take(5)
                ->get(['id', 'name', 'email', 'subject', 'is_read', 'created_at']),
        ]);
    }
}