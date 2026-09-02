<?php

namespace App\Domain\Infrastructure\Esi\Requests\Universe;

use App\Domain\Auth\Entities\Character;
use App\Domain\Infrastructure\Esi\Enums\Scope;
use App\Domain\Infrastructure\Esi\Requests\EsiRequest;
use Override;

class GetStructureInformation extends EsiRequest
{
    public function __construct(private Character $character, private int $structureId) {}

    #[Override]
    public function id(): int|string|array|null
    {
        return $this->structureId;
    }

    #[Override]
    public function endpoint(): string
    {
        return '/universe/structures/%d';
    }

    #[Override]
    public function character(): ?Character
    {
        return $this->character;
    }

    #[Override]
    public function requiredScopes(): array
    {
        return [
            Scope::UniverseReadStructures
        ];
    }
}
