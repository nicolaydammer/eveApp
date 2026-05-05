<?php

namespace App\Domain\Infrastructure\Esi\DTO;

use DateTime;

readonly class CorporationDTO implements EsiDtoInterface
{
    public function __construct(
        public ?int $id,
        public int $corporation_id,
        public ?int $alliance_id,
        public int $ceo_id,
        public int $creator_id,
        public DateTime $date_founded,
        public ?string $description,
        public ?int $faction_id,
        public ?int $home_station_id,
        public int $member_count,
        public string $name,
        public ?int $shares,
        public int $tax_rate,
        public string $ticker,
        public ?string $url,
        public ?bool $war_eligible,
        public DateTime $expires_at
    ) {}

    public function isStale(): bool
    {
        return new \DateTimeImmutable() > $this->expires_at;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'corporation_id' => $this->corporation_id,
            'alliance_id' => $this->alliance_id,
            'ceo_id' => $this->ceo_id,
            'creator_id' => $this->creator_id,
            'date_founded' => $this->date_founded,
            'description' => $this->description,
            'faction_id' => $this->faction_id,
            'home_station_id' => $this->home_station_id,
            'member_count' => $this->member_count,
            'name' => $this->name,
            'shares' => $this->shares,
            'tax_rate' => $this->tax_rate,
            'ticker' => $this->ticker,
            'url' => $this->url,
            'war_eligible' => $this->war_eligible,
            'expires_at' => $this->expires_at
        ];
    }
}
