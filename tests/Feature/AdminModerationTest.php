<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\MultiCriteriaScore;
use App\Models\Place;
use App\Models\Report;
use App\Models\Review;
use App\Models\ReviewMedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminModerationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $member;
    protected Place $place;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Reqi',
            'email' => 'admin@ratingly.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->member = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'member',
        ]);

        $category = Category::create([
            'name' => 'Cafe Cimahi',
            'slug' => 'cafe-cimahi',
        ]);

        $this->place = Place::create([
            'category_id' => $category->id,
            'name' => 'Kopi Selasar',
            'slug' => 'kopi-selasar',
            'address' => 'Jl. Gandawijaya No. 88',
            'city' => 'Cimahi',
            'avg_rating' => 0.00,
            'total_reviews' => 0,
        ]);
    }

    public function test_guest_is_redirected_to_login_when_accessing_admin(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_member_is_forbidden_from_accessing_admin(): void
    {
        $response = $this->actingAs($this->member)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_dashboard_and_see_accurate_stats(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Ringkasan &amp; Dashboard Moderasi', false);
        $response->assertSee('Tempat Kuliner');
        $response->assertSee('Validasi Pending');
    }

    public function test_admin_can_approve_pending_review_and_recalculate_weighted_rating(): void
    {
        // Review A: 5.0 (pending with receipt)
        $reviewA = Review::create([
            'user_id' => $this->member->id,
            'place_id' => $this->place->id,
            'content' => 'Kopi mantap sekali',
            'overall_rating' => 5.0,
            'status' => 'pending',
        ]);
        MultiCriteriaScore::create([
            'review_id' => $reviewA->id,
            'taste_score' => 5.0,
            'ambience_score' => 5.0,
            'wifi_socket_score' => 5.0,
            'price_value_score' => 5.0,
        ]);
        ReviewMedia::create([
            'review_id' => $reviewA->id,
            'media_type' => 'receipt',
            'file_path' => 'receipts/sample-receipt-1.svg',
            'status' => 'pending',
        ]);

        // Review B: 3.0 (already unverified, weight 1)
        Review::create([
            'user_id' => $this->member->id,
            'place_id' => $this->place->id,
            'content' => 'Biasa saja',
            'overall_rating' => 3.0,
            'status' => 'unverified',
        ]);

        // Approve Review A
        $response = $this->actingAs($this->admin)
            ->post("/admin/moderation/{$reviewA->id}/approve");

        $response->assertRedirect('/admin/moderation');

        // Check Review A is now verified
        $reviewA->refresh();
        $this->assertEquals('verified', $reviewA->status);
        $this->assertEquals($this->admin->id, $reviewA->verified_by);
        $this->assertNotNull($reviewA->verified_at);

        // Weighted calculation:
        // Review A (verified): 5.0 * 2 = 10.0
        // Review B (unverified): 3.0 * 1 = 3.0
        // Total = 13.0 / 3 = 4.33
        $this->place->refresh();
        $this->assertEquals(4.33, (float) $this->place->avg_rating);
        $this->assertEquals(2, $this->place->total_reviews);
    }

    public function test_admin_can_reject_pending_review_with_reason(): void
    {
        $review = Review::create([
            'user_id' => $this->member->id,
            'place_id' => $this->place->id,
            'content' => 'Review dengan struk palsu',
            'overall_rating' => 4.0,
            'status' => 'pending',
        ]);
        ReviewMedia::create([
            'review_id' => $review->id,
            'media_type' => 'receipt',
            'file_path' => 'receipts/sample-receipt-1.svg',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->post("/admin/moderation/{$review->id}/reject", [
                'reason' => 'Foto struk buram dan tidak terbaca.',
            ]);

        $response->assertRedirect('/admin/moderation');

        $review->refresh();
        $this->assertEquals('unverified', $review->status);
        $this->assertEquals('Foto struk buram dan tidak terbaca.', $review->rejection_reason);

        // When rejected, it becomes unverified with weight 1
        $this->place->refresh();
        $this->assertEquals(4.00, (float) $this->place->avg_rating);
    }

    public function test_admin_can_dismiss_user_report(): void
    {
        $review = Review::create([
            'user_id' => $this->member->id,
            'place_id' => $this->place->id,
            'content' => 'Review normal',
            'overall_rating' => 4.0,
            'status' => 'unverified',
        ]);

        $report = Report::create([
            'review_id' => $review->id,
            'user_id' => $this->member->id,
            'reason' => 'Tidak suka kata-katanya',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->post("/admin/reports/{$report->id}/dismiss", [
                'reason' => 'Tidak melanggar aturan.',
            ]);

        $response->assertRedirect();
        $report->refresh();
        $this->assertEquals('dismissed', $report->status);
    }

    public function test_admin_can_take_down_review_and_resolve_reports(): void
    {
        $violatingReview = Review::create([
            'user_id' => $this->member->id,
            'place_id' => $this->place->id,
            'content' => 'Spam judi online http://spam.xyz',
            'overall_rating' => 1.0,
            'status' => 'unverified',
        ]);

        $report = Report::create([
            'review_id' => $violatingReview->id,
            'user_id' => $this->member->id,
            'reason' => 'Spam link judi',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->post("/admin/reviews/{$violatingReview->id}/takedown", [
                'reason' => 'Ujaran kebencian & spam komersial ilegal.',
            ]);

        $response->assertRedirect();

        // Review is soft deleted
        $this->assertSoftDeleted('reviews', ['id' => $violatingReview->id]);

        // Report is resolved
        $report->refresh();
        $this->assertEquals('resolved', $report->status);
        $this->assertStringContainsString('Ujaran kebencian', $report->action_taken);
    }
}
