<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ModerationService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected ModerationService $moderationService
    ) {}

    public function index(): View
    {
        $stats = $this->moderationService->getDashboardStats();

        return view('admin.dashboard', compact('stats'));
    }
}
