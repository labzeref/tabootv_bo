<?php

namespace App\Enums;

enum SubscriptionStatusEnum: string
{
    case active = 'active';
    case canceled = 'canceled';
    case expired = 'expired';
    case trial = 'trial';
    case trialExpired = 'trial expired';
    case refund = 'refund';
    case chargeback = 'chargeback';
    case paymentFailed = 'payment failed';
}
