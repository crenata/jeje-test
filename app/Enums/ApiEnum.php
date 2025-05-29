<?php

namespace App\Enums;

use App\Traits\EnumTrait;

class ApiEnum {
    use EnumTrait;

    const VERSION = "v1";
    const PREFIX_ADMIN = "admin";
    const PREFIX_CUSTOMER = "customer";
    const PREFIX_AUTH = "auth";
}
