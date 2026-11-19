<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectReviewRequest;
use App\Http\Requests\Admin\TakeDownReviewRequest;
use App\Models\Review;
use App\Services\ModerationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ModerationController extends Controller
{
    public function __construct(
        protected ModerationService $moderationService
    ) {}

    public function index(Request $request): View
    {
        $status = $request->query('status', 'pending');
        $search = $request->query('q');

        $query = Review::with(['user', 'place', 'receipt'])
            ->withReceipt();

        if (in_array($status, ['pending', 'verified', 'unverified'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('place', fn ($p) => $p->where('name', 'like', "%{$search}%"));
            });
        }

        $reviews = $query->latest()->paginate(10)->withQueryString();

        return view('admin.moderation.index', compact('reviews', 'status', 'search'));
    }

    public function show(Review $review): View
    {
        $review->load(['user', 'place.category', 'criteriaScore', 'media', 'verifier']);

        return view('admin.moderation.show', compact('review'));
    }

    public function approve(Review $review): RedirectResponse
    {
        try {
            $this->moderationService->approveReview($review, auth()->id());

            return redirect()->route('admin.moderation.index')
                ->with('success', "Bukti struk ulasan #{$review->id} berhasil diverifikasi (Status: Verified).");
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(RejectReviewRequest $request, Review $review): RedirectResponse
    {
        try {
            $this->moderationService->rejectReview($review, auth()->id(), $request->validated('reason'));

            return redirect()->route('admin.moderation.index')
                ->with('success', "Bukti struk ulasan #{$review->id} ditolak (Status: Unverified).");
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function takeDown(TakeDownReviewRequest $request, Review $review): RedirectResponse
    {
        try {
            $this->moderationService->takeDownReview($review, auth()->id(), $request->validated('reason'));

            return redirect()->back()
                ->with('success', "Ulasan #{$review->id} berhasil di-take down dari publik.");
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
