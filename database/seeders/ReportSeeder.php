<?php

namespace Database\Seeders;

use App\Models\LearningResource;
use App\Models\Report;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $reporter = User::first() ?? User::factory()->create();

        // --- SAMPLE 1: A Malicious Resource ---
        $res1 = LearningResource::create([
            'title' => 'FREE CHEAT CODES 2026',
            'type' => 'youtube',
            'identifier' => 'https://youtube.com/bad-link', // Changed from url_or_isbn
            'ai_summary' => 'Suspicious link detected.',
        ]);

        Report::create([
            'user_id' => $reporter->id,
            'reason' => 'This is a phishing link.',
            'reportable_id' => $res1->id,
            'reportable_type' => LearningResource::class,
        ]);

        // --- SAMPLE 2: An Off-Topic Resource ---
        $res2 = LearningResource::create([
            'title' => 'How to Boil Eggs',
            'type' => 'textbook',
            'identifier' => '978-3-16-148410-0', // ISBN example
            'author' => 'Chef Quack',
            'ai_summary' => 'Unrelated to programming.',
        ]);

        Report::create([
            'user_id' => $reporter->id,
            'reason' => 'Off-topic content.',
            'reportable_id' => $res2->id,
            'reportable_type' => LearningResource::class,
        ]);

        // --- SAMPLE 3: A Toxic Review ---
        // We need a resource for the review to belong to first
        $validRes = LearningResource::first();

        $badReview = Review::create([
            'user_id' => $reporter->id,
            'learning_resource_id' => $validRes->id,
            'rating' => 1,
            'content' => "The author of this guide is a [REDACTED] and shouldn't be teaching!!",
        ]);

        Report::create([
            'user_id' => $reporter->id,
            'reason' => 'Hate speech and personal attacks against the author.',
            'reportable_id' => $badReview->id,
            'reportable_type' => Review::class,
        ]);
    }
}
