<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\ModerationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ReportController extends Controller
{
    public function __construct(
        protected ModerationService $moderationService
    ) {}

    public function index(Request $request): View
    {
        $status = $request->query('status', 'pending');

        $query = Report::with(['reporter', 'moderator', 'review' => fn ($q) => $q->withTrashed()->with(['user', 'place'])]);

        if (in_array($status, ['pending', 'resolved', 'dismissed'])) {
            $query->where('status', $status);
        }

        $reports = $query->latest()->paginate(10)->withQueryString();

        return view('admin.reports.index', compact('reports', 'status'));
    }

    public function dismiss(Request $request, Report $report): RedirectResponse
    {
        try {
            $reason = $request->input('reason');
            $this->moderationService->dismissReport($report, auth()->id(), $reason);

            return back()->with('success', "Laporan #{$report->id} berhasil diabaikan/ditutup.");
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
