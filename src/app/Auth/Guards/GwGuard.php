<?php

namespace App\Auth\Guards;

use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\StatefulGuard;

final class GwGuard extends SessionGuard implements StatefulGuard
{

}
