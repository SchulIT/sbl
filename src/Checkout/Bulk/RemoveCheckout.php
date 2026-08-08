<?php

namespace App\Checkout\Bulk;

use App\Entity\Checkout;
use App\Repository\CheckoutRepository;
use Override;
use Symfony\Component\HttpFoundation\Request;

readonly class RemoveCheckout implements BulkActionInterface {

    public function __construct(
        private CheckoutRepository $checkoutRepository
    ) {

    }

    #[Override]
    public function performAction(Checkout $checkout, Request $request): void {
        $this->checkoutRepository->remove($checkout);
    }

    #[Override]
    public function getKey(): string {
        return 'remove';
    }

    #[Override]
    public function getMessageTranslationKey(): string {
        return 'checkouts.bulk.actions.remove';
    }
}