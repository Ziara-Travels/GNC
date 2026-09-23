<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;

class TourSeeder extends Seeder
{
    /**
     * Seed the real tours catalog. This replaces the hardcoded arrays that used
     * to live inline in TourController/Travel\ActivityController.
     */
    public function run()
    {
        $tours = [
            [
                'name' => 'Masai Mara Safari',
                'short_description' => 'Experience the Great Migration in the world-famous Masai Mara.',
                'full_description' => 'A 3 day safari through the Masai Mara National Reserve, home to the Great Migration and the Big Five.',
                'city' => 'Masai Mara',
                'country' => 'Kenya',
                'images' => ['masai-mara.jpg'],
                'price' => 45000,
                'currency' => 'KES',
                'duration' => '3 days',
                'category' => 'Safari',
                'rating' => 4.9,
                'included_services' => ['Park fees', 'Game drives', 'Accommodation'],
                'highlights' => ['Big Five', 'Hot Air Balloon', 'Masai Village Visit'],
            ],
            [
                'name' => 'Serengeti Experience',
                'short_description' => 'Explore the endless plains of the Serengeti National Park.',
                'full_description' => 'A 4 day tour of the Serengeti, including the Ngorongoro Crater and the annual wildebeest migration.',
                'city' => 'Serengeti',
                'country' => 'Tanzania',
                'images' => ['serengeti.jpg'],
                'price' => 55000,
                'currency' => 'KES',
                'duration' => '4 days',
                'category' => 'Safari',
                'rating' => 4.8,
                'included_services' => ['Park fees', 'Game drives', 'Accommodation'],
                'highlights' => ['Game Drives', 'Wildebeest Migration', 'Ngorongoro Crater'],
            ],
            [
                'name' => 'Gorilla Trekking Rwanda',
                'short_description' => 'Trek through misty forests to encounter mountain gorillas.',
                'full_description' => 'A 2 day trek through Volcanoes National Park to see mountain gorillas in their natural habitat.',
                'city' => 'Volcanoes NP',
                'country' => 'Rwanda',
                'images' => ['rwanda-gorillas.jpg'],
                'price' => 150000,
                'currency' => 'KES',
                'duration' => '2 days',
                'category' => 'Wildlife',
                'rating' => 5.0,
                'included_services' => ['Permit fees', 'Guide', 'Accommodation'],
                'highlights' => ['Mountain Gorillas', 'Misty Forests', 'Local Culture'],
            ],
            [
                'name' => 'Zanzibar Spice Tour',
                'short_description' => 'Discover the aromatic spice plantations of Zanzibar.',
                'full_description' => 'A half-day tour of Zanzibar\'s spice plantations, tasting and learning about the island\'s famous spices.',
                'city' => 'Zanzibar',
                'country' => 'Tanzania',
                'images' => ['zanzibar-spice.jpg'],
                'price' => 3500,
                'currency' => 'KES',
                'duration' => '4 hours',
                'duration_hours' => 4,
                'category' => 'Cultural',
                'rating' => 4.7,
                'included_services' => ['Guide', 'Tastings', 'Transport'],
                'highlights' => ['Spice Plantations', 'Local Tastings', 'Stone Town'],
            ],
            [
                'name' => 'White Water Rafting - Nile',
                'short_description' => 'Experience thrilling rapids at the source of the Nile.',
                'full_description' => 'A full-day white water rafting adventure on the Nile River in Jinja, Uganda.',
                'city' => 'Jinja',
                'country' => 'Uganda',
                'images' => ['nile-rafting.jpg'],
                'price' => 8500,
                'currency' => 'KES',
                'duration' => '1 day',
                'duration_hours' => 8,
                'category' => 'Adventure',
                'rating' => 4.8,
                'included_services' => ['Safety gear', 'Guide', 'Lunch'],
                'highlights' => ['White Water Rapids', 'Source of the Nile', 'Adventure'],
            ],
        ];

        foreach ($tours as $tour) {
            Tour::updateOrCreate(
                ['name' => $tour['name']],
                $tour
            );
        }
    }
}
