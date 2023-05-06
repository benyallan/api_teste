<?php

declare(strict_types=1);

namespace App\Enums;

enum ExpenseStatus: string
{
    case APPROVED = 'aprovado';
    case REJECTED = 'rejeitado';
    case PENDING = 'pendente';
}

