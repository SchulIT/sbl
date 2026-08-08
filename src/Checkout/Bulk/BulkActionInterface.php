<?php

namespace App\Checkout\Bulk;

use App\Entity\Checkout;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\Request;

#[AutoconfigureTag(self::AUTOCONFIGURE_TAG)]
interface BulkActionInterface {
    public const string AUTOCONFIGURE_TAG = 'app.checkouts.bulk.action';

    public function performAction(Checkout $checkout, Request $request): void;

    public function getKey(): string;

    public function getMessageTranslationKey(): string;
}