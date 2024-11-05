<?php

declare(strict_types=1);

namespace App\Domain\Shared\Enum;

enum Category: string
{
    case EDUCATION = 'education';
    case SPORT = 'sport';
    case INFRASTRUCTURE = 'infrastructure';
    case MEDICINE = 'medicine';
    case TRAVELING = 'traveling';
    case CULTURE = 'culture';
    case OTHER = 'other';

    public const VALUES = [
        self::EDUCATION->value,
        self::SPORT->value,
        self::INFRASTRUCTURE->value,
        self::MEDICINE->value,
        self::CULTURE->value,
        self::OTHER->value,
    ];

    public static function values(): array
    {
        return array_map(fn(Category $category) => $category->value , self::cases());
    }

    public function title(): string
    {
        return ucfirst($this->value);
    }
}