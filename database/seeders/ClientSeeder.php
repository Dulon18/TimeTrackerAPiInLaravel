<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'name'           => 'SKIFF Technologies',
                'email'          => 'info@acme.com',
                'contact_person' => 'Sadman Kabir',
            ],
            [
                'name'           => 'Acme Corporation',
                'email'          => 'info@acme.com',
                'contact_person' => 'John Doe',
            ],
            [
                'name'           => 'Globex Inc.',
                'email'          => 'contact@globex.com',
                'contact_person' => 'Jane Smith',
            ],
        ];

        foreach ($clients as $client) {
            Client::updateOrCreate(
                ['email' => $client['email']],
                $client
            );
        }

        $this->command->info('Clients table seeded successfully!');
    }
}
