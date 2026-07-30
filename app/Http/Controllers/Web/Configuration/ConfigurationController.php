<?php

namespace App\Http\Controllers\Web\Configuration;

use App\Domain\Infrastructure\Configuration\Enums\AdminConfigurationKeysEnum;
use App\Domain\Infrastructure\Configuration\Repositories\ConfigurationRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ConfigurationController
{
    public function __construct(private ConfigurationRepository $configurationRepository) {}

    public function adminStore(string $type, Request $request): void
    {
        $adminConfigType = AdminConfigurationKeysEnum::tryFrom($type);

        if (!$adminConfigType) {
            throw new NotFoundHttpException('Invalid configuration type');
        }

        $validated = $request->validate(
            $adminConfigType->rules()
        );

        $this->configurationRepository->set($adminConfigType->value, $validated['configuration']);
    }

    public function adminGet(string $type): array
    {
        $adminConfigType = AdminConfigurationKeysEnum::tryFrom($type);

        if (!$adminConfigType) {
            throw new NotFoundHttpException('Invalid configuration type');
        }

        return $this->configurationRepository->get($adminConfigType->value);
    }
}
