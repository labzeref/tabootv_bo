<?php

namespace App\Enums;

enum SubscriptionEventTypeEnum: string
{
    case PaymentMade = 'payment.made';
    case PaymentTrial = 'payment.trial';
    case PaymentFailed = 'payment.failed';
    case PaymentPending = 'payment.pending';
    case PaymentRefunded = 'payment.refunded';
    case PaymentChargeBack = 'payment.charged_back';
    case PaymentRecurringCancelled = 'payment.recurring.cancelled';
    case PaymentRecurringUpcoming = 'payment.recurring.upcoming';
}
