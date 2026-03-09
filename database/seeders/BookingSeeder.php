<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use Faker\Factory as Faker;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please create users first.');
            return;
        }
        
        $types = ['flight', 'hotel'];
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled', 'processing'];
        $destinations = ['Dubai, UAE', 'Mombasa, Kenya', 'London, UK', 'Cape Town, SA', 'Istanbul, Turkey', 'Paris, France', 'New York, USA', 'Tokyo, Japan'];
        
        for ($i = 0; $i < 50; $i++) {
            $type = $faker->randomElement($types);
            $totalAmount = $faker->numberBetween(15000, 200000);
            $paidAmount = $faker->numberBetween(0, $totalAmount);
            
            Booking::create([
                'user_id' => $users->random()->id,
                'type' => $type,
                'title' => $type === 'flight' 
                    ? 'Flight to ' . $faker->randomElement($destinations)
                    : 'Hotel in ' . $faker->randomElement($destinations),
                'destination' => $faker->randomElement($destinations),
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'status' => $faker->randomElement($statuses),
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
