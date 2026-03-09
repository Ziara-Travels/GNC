<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; 

class EastAfricanAirportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

         // Clear existing records
         Airport::truncate();

         // Enable foreign key checks
        Schema::enableForeignKeyConstraints();

        $airports = [
    // Kenya
    ['iata_code' => 'NBO', 'name' => 'Jomo Kenyatta International Airport', 'city' => 'Nairobi', 'country' => 'Kenya', 'location' => '-1.319167, 36.927778'],
    ['iata_code' => 'MBA', 'name' => 'Moi International Airport', 'city' => 'Mombasa', 'country' => 'Kenya', 'location' => '-4.034833, 39.594333'],
    ['iata_code' => 'KIS', 'name' => 'Kisumu International Airport', 'city' => 'Kisumu', 'country' => 'Kenya', 'location' => '-0.086139, 34.728892'],
    ['iata_code' => 'MYD', 'name' => 'Malindi Airport', 'city' => 'Malindi', 'country' => 'Kenya', 'location' => '-3.229306, 40.102222'],
    
    // Uganda
    ['iata_code' => 'EBB', 'name' => 'Entebbe International Airport', 'city' => 'Entebbe', 'country' => 'Uganda', 'location' => '0.042386, 32.443503'],
    ['iata_code' => 'KLA', 'name' => 'Kampala Airport', 'city' => 'Kampala', 'country' => 'Uganda', 'location' => '0.3476, 32.5825'],
    
    // Tanzania
    ['iata_code' => 'DAR', 'name' => 'Julius Nyerere International Airport', 'city' => 'Dar es Salaam', 'country' => 'Tanzania', 'location' => '-6.878111, 39.202625'],
    ['iata_code' => 'JRO', 'name' => 'Kilimanjaro International Airport', 'city' => 'Kilimanjaro', 'country' => 'Tanzania', 'location' => '-3.429406, 37.074461'],
    ['iata_code' => 'ZNZ', 'name' => 'Abeid Amani Karume International Airport', 'city' => 'Zanzibar', 'country' => 'Tanzania', 'location' => '-6.222025, 39.224886'],
    
    // Burundi
    ['iata_code' => 'BJM', 'name' => 'Bujumbura International Airport', 'city' => 'Bujumbura', 'country' => 'Burundi', 'location' => '-3.324019, 29.318519'],
    
    // Rwanda
    ['iata_code' => 'KGL', 'name' => 'Kigali International Airport', 'city' => 'Kigali', 'country' => 'Rwanda', 'location' => '-1.968628, 30.139183'],
    
    // Seychelles
    ['iata_code' => 'SEZ', 'name' => 'Seychelles International Airport', 'city' => 'Victoria', 'country' => 'Seychelles', 'location' => '-4.674342, 55.521839'],
    
    // Madagascar
    ['iata_code' => 'TNR', 'name' => 'Ivato International Airport', 'city' => 'Antananarivo', 'country' => 'Madagascar', 'location' => '-18.796867, 47.478806'],
    ['iata_code' => 'DIE', 'name' => 'Diego Suarez Airport', 'city' => 'Antsiranana', 'country' => 'Madagascar', 'location' => '-12.3494, 49.291747'],
    ['iata_code' => 'NOS', 'name' => 'Fascene Airport', 'city' => 'Nosy Be', 'country' => 'Madagascar', 'location' => '-13.312067, 48.314822'],
    
    // South Africa
    ['iata_code' => 'JNB', 'name' => 'O.R. Tambo International Airport', 'city' => 'Johannesburg', 'country' => 'South Africa', 'location' => '-26.139166, 28.246'],
    ['iata_code' => 'CPT', 'name' => 'Cape Town International Airport', 'city' => 'Cape Town', 'country' => 'South Africa', 'location' => '-33.964806, 18.601667'],
    ['iata_code' => 'DUR', 'name' => 'King Shaka International Airport', 'city' => 'Durban', 'country' => 'South Africa', 'location' => '-29.614445, 31.119722'],
    ['iata_code' => 'PLZ', 'name' => 'Chief Dawid Stuurman International Airport', 'city' => 'Port Elizabeth', 'country' => 'South Africa', 'location' => '-33.984722, 25.617222'],
    ['iata_code' => 'GRJ', 'name' => 'George Airport', 'city' => 'George', 'country' => 'South Africa', 'location' => '-34.005553, 22.378889'],
    
    // Ethiopia
    ['iata_code' => 'ADD', 'name' => 'Addis Ababa Bole International Airport', 'city' => 'Addis Ababa', 'country' => 'Ethiopia', 'location' => '8.977778, 38.799722'],
    
    // Djibouti
    ['iata_code' => 'JIB', 'name' => 'Djibouti-Ambouli International Airport', 'city' => 'Djibouti', 'country' => 'Djibouti', 'location' => '11.547222, 43.159444'],
    
    // Somalia
    ['iata_code' => 'MGQ', 'name' => 'Aden Adde International Airport', 'city' => 'Mogadishu', 'country' => 'Somalia', 'location' => '2.014444, 45.304722'],
    
    // Mauritius (Indian Ocean)
    ['iata_code' => 'MRU', 'name' => 'Sir Seewoosagur Ramgoolam International Airport', 'city' => 'Port Louis', 'country' => 'Mauritius', 'location' => '-20.430206, 57.683558'],
    
    // Comoros
    ['iata_code' => 'HAH', 'name' => 'Prince Said Ibrahim International Airport', 'city' => 'Moroni', 'country' => 'Comoros', 'location' => '-11.533611, 43.271944'],
];
         // Insert airports in chunks to improve performance
         foreach (array_chunk($airports, 100) as $chunk) {
            Airport::insert($chunk);
        }
    }
}
