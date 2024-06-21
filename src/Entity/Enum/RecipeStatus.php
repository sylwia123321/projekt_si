<?php
/**
 * Recipe status enumeration.
 */

namespace App\Entity\Enum;

class RecipeStatus
{
    public const ACTIVE = 1;
    public const COMPLETED = 2;
    public const CANCELLED = 3;

    /**
     * Get the status label.
     *
     * @param int $status
     * @return string
     */
    public static function label(int $status): string
    {
        return match($status) {
            self::ACTIVE => 'Active',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            default => 'Unknown',
        };
    }
}
