<?php

namespace App\Enums;

enum ConditionType: string
{
    case None = 'none';
    case TimeRange = 'time_range';
    case DateRange = 'date_range';
}
