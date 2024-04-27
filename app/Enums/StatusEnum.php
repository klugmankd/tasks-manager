<?php

namespace App\Enums;

use Illuminate\Support\Arr;
use RuntimeException;

enum StatusEnum: string
{
    case OPEN = 'Open';
    case IN_PROGRESS = 'In Progress';
    case READY = 'Ready';
    case DONE = 'Done';

    public static function randomUnique(bool $reset = true): StatusEnum
    {
        static $used = [];

        if ($reset) {
            // Consecutive method calls within test cases quickly use up all the options
            $used = [];
        }

        $cases = self::cases();

        if (count($used) === count($cases)) {
            throw new RuntimeException('No more unique values available for '.self::class);
        }

        $used[] = $random = Arr::random(
            array_filter($cases, fn ($case) => ! in_array($case, $used))
        );

        return $random;
    }

    public static function values(): array
    {
        return array_map(fn(StatusEnum $item) => $item->value, self::cases());
    }
}
