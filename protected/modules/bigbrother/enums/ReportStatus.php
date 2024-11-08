<?php

declare(strict_types=1);

namespace BigBrother\enums;

class ReportStatus
{
    public const Open = 0;
    public const Removed = 1;
    public const Closed = 10;

    public static function toString(int $status): string
    {
        return match($status) {
            0 => 'Open',
            1 => 'Removed',
            10 => 'Closed',
        };
    }
}
