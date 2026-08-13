<?php

namespace Database\Seeders;

use App\Domain\Auth\Entities\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->count(15)
            ->withCharacters(3)
            ->create();
    }
}
