<?php

declare(strict_types=1);

namespace App\Enums;

enum ExpenseStatus: string
{
    case APPROVED = 'aprovado';
    case REJECTED = 'rejeitado';
    case PENDING = 'pendente';

    public static function of(string $status): self
    {
        return match ($status) {
            'aprovado' => self::APPROVED,
            'rejeitado' => self::REJECTED,
            'pendente' => self::PENDING,
        };
    }
}
