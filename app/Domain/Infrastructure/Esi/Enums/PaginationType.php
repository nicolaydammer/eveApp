<?php

namespace App\Domain\Infrastructure\Esi\Enums;

enum PaginationType
{
    case None;
    case Page;
    case Cursor;
}
