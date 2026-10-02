<?php

namespace Database\Seeders;

use App\Models\Tour;
use Illuminate\Database\Seeder;

/**
 * Seeds the live tour catalogue mirrored from ziaratravels.co.ke.
 *
 * The source of truth is database/data/reference_tours.php, generated from the
 * reference site so the content is reproducible rather than hand-maintained.
 * Rows are keyed on `slug`, which makes repeated runs (including the one on
 * every production boot) idempotent.
 */
class TourSeeder extends Seeder
{
    public function run(): void
    {
        $catalogue = require database_path('data/reference_tours.php');

        if (! is_array($catalogue) || $catalogue === []) {
            $this->command?->error('Reference tour catalogue is empty; nothing seeded.');

            return;
        }

        $now = now();

        foreach ($catalogue as $row) {
            $slug = $row['slug'];

            // The reference lists the cheapest tier first; `price` holds that
            // amount while `pricing_options` keeps every labelled option.
            $options = $row['prices'] ?? [];

            Tour::updateOrCreate(
                ['slug' => $slug],
                [
                    'tour_reference'     => 'ZT' . strtoupper(substr(md5($slug), 0, 10)),
                    'name'               => $row['name'],
                    'short_description'  => $row['short_description'],
                    'full_description'   => $row['full_description'],
                    'card_excerpt'       => $row['card_excerpt'] ?? null,
                    'city'               => $row['location'] !== '' ? $row['location'] : $row['name'],
                    'country'            => $row['country'],
                    'images'             => $row['images'],
                    'price'              => $row['price'],
                    'pricing_options'    => $options,
                    'currency'           => $row['currency'],
                    'duration'           => $row['duration'],
                    'included_services'  => $row['inclusions'],
                    'highlights'         => $options,
                    'category'           => $row['category'],
                    'featured'           => $row['featured'],
                    'status'             => 'active',
                    'published_at'       => $now,
                    'updated_at'         => $now,
                    'created_at'         => $now,
                ]
            );
        }

        $this->command?->info('Seeded ' . count($catalogue) . ' reference tours.');
    }
}