<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $previousTime = Carbon::getTestNow();
        Carbon::setTestNow('2026-10-18 12:00:00');

        try {
            $this->call([
                CategorySeeder::class,
                BrandSeeder::class,
                TagSeeder::class,
                ProductSeeder::class,
                ProductVariantSeeder::class,
                ProductImageSeeder::class,
                ProductTranslationSeeder::class,
                CategoryTranslationSeeder::class,
                ProductTagSeeder::class,
                UserSeeder::class,
                CustomerProfileSeeder::class,
                AddressSeeder::class,
                CartSeeder::class,
                CartItemSeeder::class,
                DiscountSeeder::class,
                CouponSeeder::class,
                OrderSeeder::class,
                OrderItemSeeder::class,
                VipSubscriptionSeeder::class,
                PaymentSeeder::class,
                DocumentSeeder::class,
                CouponUserSeeder::class,
                PostCategorySeeder::class,
                PostSeeder::class,
                PostTranslationSeeder::class,
                PostTagSeeder::class,
                GiveawaySeeder::class,
                GiveawayEntrySeeder::class,
                GiveawayWinnerSeeder::class,
            ]);
        } finally {
            Carbon::setTestNow($previousTime);
        }
    }
}
