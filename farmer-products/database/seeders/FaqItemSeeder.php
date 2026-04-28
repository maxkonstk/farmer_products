<?php

namespace Database\Seeders;

use App\Models\FaqItem;
use Illuminate\Database\Seeder;

class FaqItemSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('shop.faq', []) as $index => $faqItem) {
            FaqItem::query()->updateOrCreate(
                ['question' => $faqItem['question']],
                [
                    'answer' => $faqItem['answer'],
                    'sort_order' => $index + 1,
                    'is_published' => true,
                ]
            );
        }
    }
}
