<?php

namespace App\Config\Enums;

enum FeatureFlagEnum: int
{
    case Disabled = 0;
    case Enabled = 1;
}
