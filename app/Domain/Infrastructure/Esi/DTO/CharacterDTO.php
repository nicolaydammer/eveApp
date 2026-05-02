<?php

namespace App\Domain\Infrastructure\Esi\DTO;

use DateTime;

readonly class CharacterDTO implements EsiDtoInterface
{
    public function __construct(
        public ?int $id,
        public int $CharacterID,
        public ?int $alliance_id,
        public int $corporation_id,
        public ?int $faction_id,
        public int $bloodline_id,
        public int $race_id,
        public DateTime $birthday,
        public ?string $description,
        public string $gender,
        public string $name,
        public ?int $security_status,
        public DateTime $expires_at,
        public ?string $title
    ) {}

    public function isStale(): bool
    {
        return new \DateTimeImmutable() > $this->expires_at;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'CharacterID' => $this->CharacterID,
            'alliance_id' => $this->alliance_id,
            'corporation_id' => $this->corporation_id,
            'faction_id' => $this->faction_id,
            'bloodline_id' => $this->bloodline_id,
            'race_id' => $this->race_id,
            'birthday' => $this->birthday,
            'description' => $this->description,
            'gender' => $this->gender,
            'name' => $this->name,
            'security_status' => $this->security_status,
            'expires_at' => $this->expires_at,
            'title' => $this->title
        ];
    }
}
