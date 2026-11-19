<?php

namespace App\Services;

use App\Models\Place;
use App\Models\Report;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ModerationService
{
    public function __construct(
        protected RatingCalculationService $ratingService
    ) {}

    public function getDashboardStats(): array
    {
        return [
            'total_places' => Place::count(),
            'pending_reviews' => Review::where('status', 'pending')->count(),
            'active_reports' => Report::where('status', 'pending')->count(),
            'verified_reviews' => Review::where('status', 'verified')->count(),
            'unverified_reviews' => Review::where('status', 'unverified')->count(),
            'recent_pending' => Review::with(['user', 'place', 'receipt'])
                ->pending()
                ->latest()
                ->take(5)
                ->get(),
            'recent_reports' => Report::with(['reporter', 'review.place', 'review.user'])
                ->pending()
                ->latest()
                ->take(5)
                ->get(),
        ];
    }

    public function approveReview(Review $review, int $adminId): void
    {
        if (! $review->isPending()) {
            throw new InvalidArgumentException('Hanya ulasan berstatus pending yang dapat disetujui.');
        }

        if (! $review->receipt()->exists()) {
            throw new InvalidArgumentException('Ulasan tidak memiliki bukti struk pembayaran.');
        }

        DB::transaction(function () use ($review, $adminId) {
            $review->update([
                'status' => 'verified',
                'verified_by' => $adminId,
                'verified_at' => now(),
                'rejection_reason' => null,
            ]);

            $review->media()->where('media_type', 'receipt')->update([
                'status' => 'verified',
            ]);

            $this->ratingService->recalculatePlaceRating($review->place);
        });
    }

    public function rejectReview(Review $review, int $adminId, ?string $reason = null): void
    {
        if (! $review->isPending()) {
            throw new InvalidArgumentException('Hanya ulasan berstatus pending yang dapat ditolak.');
        }

        DB::transaction(function () use ($review, $adminId, $reason) {
            $review->update([
                'status' => 'unverified',
                'verified_by' => $adminId,
                'verified_at' => now(),
                'rejection_reason' => $reason ?? 'Bukti struk pembayaran tidak valid atau tidak terbaca.',
            ]);

            $review->media()->where('media_type', 'receipt')->update([
                'status' => 'unverified',
            ]);

            $this->ratingService->recalculatePlaceRating($review->place);
        });
    }

    public function takeDownReview(Review $review, int $adminId, ?string $reason = null): void
    {
        DB::transaction(function () use ($review, $adminId, $reason) {
            $note = $reason ?? 'Pelanggaran pedoman komunitas ulasan RatingLy.';

            $review->reports()->where('status', 'pending')->update([
                'status' => 'resolved',
                'action_taken' => 'Ulasan di-takedown oleh Admin. Alasan: ' . $note,
                'moderated_by' => $adminId,
                'moderated_at' => now(),
            ]);

            $place = $review->place;
            $review->delete();

            $this->ratingService->recalculatePlaceRating($place);
        });
    }

    public function dismissReport(Report $report, int $adminId, ?string $reason = null): void
    {
        $report->update([
            'status' => 'dismissed',
            'action_taken' => $reason ?? 'Laporan ulasan ditolak setelah verifikasi Admin.',
            'moderated_by' => $adminId,
            'moderated_at' => now(),
        ]);
    }
}
