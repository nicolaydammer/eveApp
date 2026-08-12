<?php

namespace App\Domain\Infrastructure\Esi\Requests\Corporation;

use App\Domain\Infrastructure\Esi\Requests\EsiRequest;

class PublicCorpInfoRequest extends EsiRequest
{
    public function __construct(private int $corpId) {}

    public function endpoint(): string
    {
        return '/corporations/%d';
    }
    public function id(): int|string|null
    {
        return $this->corpId;
    }
}
