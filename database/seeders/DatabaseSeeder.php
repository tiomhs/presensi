<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
         $faker = Faker::create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('Progress2025$'),
            'nim' => 'STI000001',
        ]);

        // USERS
        for ($i = 1; $i <= 10; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'nim' => 'STI' . $faker->unique()->numberBetween(100000, 999999),
                'password' => bcrypt('password123'), // contoh password terenkripsi
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // EVENTS
        for ($i = 1; $i <= 10; $i++) {
            DB::table('events')->insert([
                'name' => ucfirst($faker->word()) . ' Event',
                'date' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
                'location' => $faker->city(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ROLES
        $roles = ['Chairperson', 'Secretary', 'Treasurer', 'Logistics', 'Public Relations', 'Media', 'Security', 'Consumption', 'Documentation', 'Technician'];
        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'name' => $role
            ]);
        }

        // EVENT_COMMITTEES
        for ($i = 1; $i <= 10; $i++) {
            DB::table('event_committees')->insert([
                'user_id' => rand(1, 10),
                'event_id' => rand(1, 10),
                'role_id' => rand(1, 10),
                'division' => rand(0, 1) ? 'Division ' . chr(rand(65, 68)) : null,
            ]);
        }

        // ATTENDANCES
        $statuses = ['present', 'late', 'absent'];
        for ($i = 1; $i <= 10; $i++) {
            DB::table('attendances')->insert([
                'event_committee_id' => rand(1, 10),
                'status' => $statuses[array_rand($statuses)],
                'note' => rand(0, 1) ? $faker->sentence() : null,
            ]);
        }

        // QR_TOKENS (optional)
        for ($i = 1; $i <= 10; $i++) {
            DB::table('qr_tokens')->insert([
                'event_id' => $i,
                'token' => Str::random(40),
                'expires_at' => now()->addDays(rand(1, 10)),
            ]);
        }
    }
}
