<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::factory()->administrator()->create([
            'name'  => 'System Administrator',
            'email' => 'system.administrator@tasks.com',
        ]);
        User::factory()->assignee()->create();
    }
}
