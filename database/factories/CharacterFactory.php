<?php

namespace Database\Factories;

use App\Domain\Auth\Entities\Character;
use App\Domain\Auth\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Character>
 */
class CharacterFactory extends Factory
{
    protected $model = Character::class;

    public function definition(): array
    {
        return [
            'CharacterID' => $this->generateCharacterId(),
            'CharacterName' => fake()->unique()->userName(),
            'accessToken' => Str::random(50),
            'refreshToken' => Str::random(25),
            'expires_at' => now()->addYear(),
        ];
    }

    private function generateCharacterId(): int
    {
        do {
            $id = fake()->numberBetween(
                100_000_000,
                999_999_999
            );
        } while (
            Character::where('CharacterID', $id)->exists()
            || User::where('main_character_id', $id)->exists()
        );

        return $id;
    }
}
