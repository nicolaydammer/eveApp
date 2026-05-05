<?php

namespace App\Domain\Infrastructure\Esi\DTO;

use DateTime;

readonly class AllianceDTO implements EsiDtoInterface
{
    public function __construct(
        public ?int $id,
        public int $alliance_id,
        public int $creator_corporation_id,
        public int $creator_id,
        public DateTime $date_founded,
        public ?int $executor_corporation_id,
        public ?int $faction_id,
        public string $name,
        public string $ticker,
        public DateTime $expires_at,
    ) {}

    public function isStale(): bool
    {
        return new \DateTimeImmutable() > $this->expires_at;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'alliance_id' => $this->alliance_id,
            'creator_corporation_id' => $this->creator_corporation_id,
            'creator_id' => $this->creator_id,
            'date_founded' => $this->date_founded,
            'executor_corporation_id' => $this->executor_corporation_id,
            'faction_id' => $this->faction_id,
            'name' => $this->name,
            'ticker' => $this->ticker,
            'expires_at' => $this->expires_at
        ];
    }
}
