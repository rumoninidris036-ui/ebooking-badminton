<?php

namespace App\Services;

use LogicException;

class PaymentService
{
    public function createSnapToken(): never
    {
        throw new LogicException('Payment gateway integration is intentionally disabled until the core booking system is stable.');
    }
}
