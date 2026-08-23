<?php

namespace App\Domain\Infrastructure\Esi\Requests\Market;

use App\Domain\Infrastructure\Esi\Enums\PaginationType;
use App\Domain\Infrastructure\Esi\Requests\EsiRequest;
use Override;

class RegionMarketOrdersRequest extends EsiRequest
{
    public function __construct(private int $region_id) {}

    #[Override]
    public function id(): int|string|null
    {
        return $this->region_id;
    }

    #[Override]
    public function endpoint(): string
    {
        return '/markets/%d/orders';
    }

    #[Override]
    public function paginated(): PaginationType
    {
        return PaginationType::Page;
    }

    #[Override]
    public function data(): array
    {
        return [
            'order_type' => 'all',
        ];
    }
}
