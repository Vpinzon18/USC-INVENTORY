<?php

namespace App\Support;

use Illuminate\Http\Request;

final class PaginationOptions
{
    public const SIZES = [5, 10, 15, 25, 50];

    public static function fromRequest(Request $request): int
    {
        $value = $request->query('per_page', 15);

        if (!is_scalar($value)) {
            return 15;
        }

        $size = filter_var($value, FILTER_VALIDATE_INT);

        return in_array($size, self::SIZES, true) ? $size : 15;
    }
}
