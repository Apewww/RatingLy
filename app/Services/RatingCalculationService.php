<?php

namespace App\Services;

use App\Models\Place;

class RatingCalculationService
{
    /**
     * Hitung ulang skor agregat tempat berdasarkan pembobotan ulasan:
     * - Verified Review: bobot 2
     * - Unverified Review: bobot 1
     * Ulasan yang di-take down (soft-deleted) otomatis diabaikan oleh Eloquent.
     */
    public function recalculatePlaceRating(Place $place): void
    {
        $reviews = $place->reviews()
            ->whereIn('status', ['verified', 'unverified'])
            ->get();

        if ($reviews->isEmpty()) {
            $place->update([
                'avg_rating' => 0.00,
                'total_reviews' => 0,
            ]);
            return;
        }

        $totalWeightedScore = 0.0;
        $totalWeight = 0;

        foreach ($reviews as $review) {
            $weight = ($review->status === 'verified') ? 2 : 1;
            $totalWeightedScore += ((float) $review->overall_rating * $weight);
            $totalWeight += $weight;
        }

        $avgRating = $totalWeight > 0 ? round($totalWeightedScore / $totalWeight, 2) : 0.00;

        $place->update([
            'avg_rating' => $avgRating,
            'total_reviews' => $reviews->count(),
        ]);
    }
}
