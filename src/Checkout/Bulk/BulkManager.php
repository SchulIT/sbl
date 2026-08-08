<?php

namespace App\Checkout\Bulk;

use App\Repository\CheckoutRepositoryInterface;
use App\Utils\ArrayUtils;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\HttpFoundation\Request;

readonly class BulkManager {
    public const int MAX_ITEMS = 200;

    /** @var array<string, BulkActionInterface> */
    private array $actions;

    public function __construct(
        #[AutowireIterator(BulkActionInterface::AUTOCONFIGURE_TAG)] iterable $actions,
        private CheckoutRepositoryInterface $checkoutRepository,
    ) {
        $this->actions = ArrayUtils::createArrayWithKeys(
            iterator_to_array($actions),
            fn(BulkActionInterface $action) => $action->getKey()
        );
    }

    public function perform(array $checkoutUuids, string $action, Request $request): int {
        if(count($checkoutUuids) > self::MAX_ITEMS) {
            return 0;
        }

        $action = $this->actions[$action] ?? null;

        if($action === null) {
            return 0;
        }

        $checkouts = $this->checkoutRepository->findAllByUuids($checkoutUuids);
        $counter = 0;

        $this->checkoutRepository->beginTransaction();

        foreach($checkouts as $checkout) {
            $action->performAction($checkout, $request);
            $counter++;
        }

        $this->checkoutRepository->commit();
        return $counter;
    }

    /**
     * @return array
     */
    public function getActions(): array {
        $actions = [ ];

        foreach($this->actions as $action) {
            $actions[$action->getKey()] = $action->getMessageTranslationKey();
        }

        return $actions;
    }
}