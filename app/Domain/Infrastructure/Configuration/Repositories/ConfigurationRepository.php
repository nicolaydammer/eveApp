<?php

namespace App\Domain\Infrastructure\Configuration\Repositories;

use App\Domain\Infrastructure\Configuration\Models\Configuration;

class ConfigurationRepository
{
    public function get(string $name): array
    {
        return Configuration::query()->where('name', $name)->firstOrFail()->configuration;
    }

    public function set(string $name, array $configuration): void
    {
        Configuration::query()->updateOrCreate(['name' => $name], ['configuration' => $configuration]);
    }

    public function delete(string $name): void
    {
        Configuration::query()->where('name', $name)->delete();
    }

    public function has(string $name): bool
    {
        return Configuration::query()->where('name', $name)->exists();
    }
}
