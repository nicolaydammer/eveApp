<?php

namespace Database\Factories;

use App\Domain\Auth\Entities\Character;
use App\Domain\Auth\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'main_character_id' => $this->generateCharacterId(),
            'remember_token' => null,
            'is_admin' => false,
        ];
    }

    public function withCharacters(int $count): static
    {
        $count = max(1, $count);

        return $this->afterCreating(function (User $user) use ($count) {
            // Create the main character.
            Character::factory()->create([
                'CharacterID' => $user->main_character_id,
                'user_id' => $user->id,
            ]);

            // Create additional characters.
            if ($count > 1) {
                Character::factory()
                    ->count($count - 1)
                    ->create([
                        'user_id' => $user->id,
                    ]);
            }
        });
    }

    public function withRandomCharacters(): static
    {
        return $this->afterCreating(function (User $user) {
            $count = fake()->numberBetween(1, 3);

            Character::factory()->create([
                'CharacterID' => $user->main_character_id,
                'user_id' => $user->id,
            ]);

            if ($count > 1) {
                Character::factory()
                    ->count($count - 1)
                    ->create([
                        'user_id' => $user->id,
                    ]);
            }
        });
    }

    private function generateCharacterId(): int
    {
        do {
            $id = fake()->numberBetween(
                100_000_000,
                999_999_999
            );
        } while (
            User::where('main_character_id', $id)->exists()
            || Character::where('CharacterID', $id)->exists()
        );

        return $id;
    }
}
