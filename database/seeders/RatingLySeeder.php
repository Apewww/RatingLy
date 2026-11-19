<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MultiCriteriaScore;
use App\Models\Place;
use App\Models\Report;
use App\Models\Review;
use App\Models\ReviewMedia;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RatingLySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@ratingly.com'],
            [
                'name' => 'Reqi Fauzan (Admin)',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $budi = User::firstOrCreate(
            ['email' => 'budi@gmail.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'member',
            ]
        );

        $siti = User::firstOrCreate(
            ['email' => 'siti@gmail.com'],
            [
                'name' => 'Siti Rahmawati',
                'password' => Hash::make('password'),
                'role' => 'member',
            ]
        );

        $dian = User::firstOrCreate(
            ['email' => 'dian@gmail.com'],
            [
                'name' => 'Dian Pratama',
                'password' => Hash::make('password'),
                'role' => 'member',
            ]
        );

        // 2. Create Categories
        $catCafe = Category::firstOrCreate(['slug' => 'coffee-shop-cafe'], ['name' => 'Coffee Shop & Cafe']);
        $catSunda = Category::firstOrCreate(['slug' => 'resto-sunda'], ['name' => 'Restoran Tradisional Sunda']);
        $catStreet = Category::firstOrCreate(['slug' => 'street-food'], ['name' => 'Street Food & Kuliner Malam']);

        // 3. Create Places
        $placeSelasar = Place::firstOrCreate(
            ['slug' => 'kopi-selasar-cimahi'],
            [
                'category_id' => $catCafe->id,
                'name' => 'Kopi Selasar Cimahi',
                'address' => 'Jl. Gandawijaya No. 88, Cimahi Tengah, Kota Cimahi',
                'city' => 'Cimahi',
                'description' => 'Tempat ngopi asri dan tenang di pusat Kota Cimahi dengan konsep semi-outdoor, colokan melimpah, dan Wi-Fi cepat.',
                'avg_rating' => 4.65,
                'total_reviews' => 2,
            ]
        );

        $placeAmpera = Place::firstOrCreate(
            ['slug' => 'rm-ampera-dago'],
            [
                'category_id' => $catSunda->id,
                'name' => 'RM Ampera Dago',
                'address' => 'Jl. Ir. H. Juanda No. 120, Dago, Coblong, Kota Bandung',
                'city' => 'Bandung',
                'description' => 'Masakan khas Sunda prasmanan legendaris di kawasan Dago Bandung dengan aneka pepes, ayam bakar, dan sambal khas.',
                'avg_rating' => 3.80,
                'total_reviews' => 2,
            ]
        );

        $placeLamalera = Place::firstOrCreate(
            ['slug' => 'sei-sapi-lamalera'],
            [
                'category_id' => $catSunda->id,
                'name' => 'Sei Sapi Lamalera',
                'address' => 'Jl. Bagusrangin No. 24A, Dipatiukur, Kota Bandung',
                'city' => 'Bandung',
                'description' => 'Pelopor sei sapi khas NTT dengan kuah kaldu gurih dan sambal lu\'at di Bandung.',
                'avg_rating' => 4.50,
                'total_reviews' => 0,
            ]
        );

        // 4. Create Reviews
        // Review 1: Pending with receipt from Budi on Kopi Selasar
        $review1 = Review::create([
            'user_id' => $budi->id,
            'place_id' => $placeSelasar->id,
            'content' => 'Tempatnya cozy banget buat nugas dan WFA. Kopinya rich, croissant almond-nya renyah. Wi-Fi stabil 40 Mbps dan ada colokan di tiap meja. Struk pembayaran saya sertakan.',
            'overall_rating' => 4.5,
            'status' => 'pending',
        ]);
        MultiCriteriaScore::create([
            'review_id' => $review1->id,
            'taste_score' => 4.8,
            'ambience_score' => 4.5,
            'wifi_socket_score' => 4.2,
            'price_value_score' => 4.5,
        ]);
        ReviewMedia::create([
            'review_id' => $review1->id,
            'media_type' => 'receipt',
            'file_path' => 'receipts/sample-receipt-1.svg',
            'status' => 'pending',
        ]);
        ReviewMedia::create([
            'review_id' => $review1->id,
            'media_type' => 'place_photo',
            'file_path' => 'places/selasar-cph-1.svg',
            'status' => 'pending',
        ]);

        // Review 2: Pending with receipt from Siti on RM Ampera
        $review2 = Review::create([
            'user_id' => $siti->id,
            'place_id' => $placeAmpera->id,
            'content' => 'Ayam bakar madunya enak bumbunya meresap sampai ke tulang. Sambel terasinya juara. Suasana agak ramai saat jam makan siang, Wi-Fi standar.',
            'overall_rating' => 4.0,
            'status' => 'pending',
        ]);
        MultiCriteriaScore::create([
            'review_id' => $review2->id,
            'taste_score' => 4.5,
            'ambience_score' => 3.5,
            'wifi_socket_score' => 3.0,
            'price_value_score' => 4.8,
        ]);
        ReviewMedia::create([
            'review_id' => $review2->id,
            'media_type' => 'receipt',
            'file_path' => 'receipts/sample-receipt-2.svg',
            'status' => 'pending',
        ]);
        ReviewMedia::create([
            'review_id' => $review2->id,
            'media_type' => 'place_photo',
            'file_path' => 'places/ampera-dago-1.svg',
            'status' => 'pending',
        ]);

        // Review 3: Verified Review from Dian on Kopi Selasar
        $review3 = Review::create([
            'user_id' => $dian->id,
            'place_id' => $placeSelasar->id,
            'content' => 'Pelayanan ramah sekali. Baristanya komunikatif dan merekomendasikan beans manual brew yang pas dengan selera saya. Sangat recommended.',
            'overall_rating' => 4.8,
            'status' => 'verified',
            'verified_by' => $admin->id,
            'verified_at' => now()->subDay(),
        ]);
        MultiCriteriaScore::create([
            'review_id' => $review3->id,
            'taste_score' => 5.0,
            'ambience_score' => 4.8,
            'wifi_socket_score' => 4.5,
            'price_value_score' => 4.5,
        ]);
        ReviewMedia::create([
            'review_id' => $review3->id,
            'media_type' => 'receipt',
            'file_path' => 'receipts/sample-receipt-1.svg',
            'status' => 'verified',
        ]);

        // Review 4: Unverified Review from Budi on RM Ampera
        $review4 = Review::create([
            'user_id' => $budi->id,
            'place_id' => $placeAmpera->id,
            'content' => 'Makan bareng keluarga. Pilihan lauk banyak, sayur asem seger. Sayang parkir mobil agak sempit.',
            'overall_rating' => 3.5,
            'status' => 'unverified',
        ]);
        MultiCriteriaScore::create([
            'review_id' => $review4->id,
            'taste_score' => 4.0,
            'ambience_score' => 3.0,
            'wifi_socket_score' => 2.5,
            'price_value_score' => 4.0,
        ]);

        // Review 5: Violating review with spam from Dian
        $review5 = Review::create([
            'user_id' => $dian->id,
            'place_id' => $placeSelasar->id,
            'content' => 'Tempat jelek dan mahal! Daripada jajan di sini mending gabung link judi slot gacor http://spam-jud1.xyz terbukti cuan ratusan juta per hari!!',
            'overall_rating' => 1.0,
            'status' => 'unverified',
        ]);
        MultiCriteriaScore::create([
            'review_id' => $review5->id,
            'taste_score' => 1.0,
            'ambience_score' => 1.0,
            'wifi_socket_score' => 1.0,
            'price_value_score' => 1.0,
        ]);

        // 5. Reports on Review 5
        Report::create([
            'review_id' => $review5->id,
            'user_id' => $budi->id,
            'reason' => 'Spam promosi situs judi online dan fitnah tidak relevan.',
            'status' => 'pending',
        ]);

        Report::create([
            'review_id' => $review5->id,
            'user_id' => $siti->id,
            'reason' => 'Ulasan memuat link berbahaya (phishing / scam judi).',
            'status' => 'pending',
        ]);

        // Resolved report on Review 4
        Report::create([
            'review_id' => $review4->id,
            'user_id' => $dian->id,
            'reason' => 'Dugaan ulasan tidak relevan dengan menu.',
            'status' => 'dismissed',
            'action_taken' => 'Laporan ditolak karena ulasan membahas pengalaman bersantap yang valid.',
            'moderated_by' => $admin->id,
            'moderated_at' => now()->subHours(5),
        ]);
    }
}
