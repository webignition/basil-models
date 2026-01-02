<?php

declare(strict_types=1);

namespace webignition\BasilModels\Enum;

enum EncapsulatingStatementType: string
{
    case RESOLVED_ACTION = 'resolved-action';
    case RESOLVED_ASSERTION = 'resolved-assertion';
    case DERIVED_VALUE_OPERATION_ASSERTION = 'derived-value-operation-assertion';
}
