<?php

namespace App\Checkout\Bulk;

use App\Entity\Checkout;
use App\Repository\CheckoutRepository;
use Override;
use SchulIT\CommonBundle\Helper\DateHelper;
use Symfony\Component\HttpFoundation\Request;

readonly class ReturnCheckout implements BulkActionInterface {

    public function __construct(
        private CheckoutRepository $checkoutRepository,
        private DateHelper $dateHelper,
    ) {

    }

    #[Override]
    public function performAction(Checkout $checkout, Request $request): void {
        $checkout->setEnd($this->dateHelper->getNow());
        $this->checkoutRepository->persist($checkout);
    }

    #[Override]
    public function getKey(): string {
        return 'return';
    }

    #[Override]
    public function getMessageTranslationKey(): string {
        return 'checkouts.bulk.actions.return';
    }
}