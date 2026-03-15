<?php

namespace App\Book;

use App\Checkout\CheckoutManager;
use App\Checkout\CheckoutStatus;
use App\Entity\Book;
use DateTime;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class AvailabilityReportGenerator {

    private const string KEY_PATTERN = 'report.%d';
    public const int LIFETIME_IN_SECONDS = 3600;

    public function __construct(private readonly CheckoutManager $checkoutManager,
                                private readonly CacheInterface $cache) {

    }

    /**
     * Enforces regeneration of availability report
     *
     * @param Book $book
     * @return AvailabilityReport
     * @throws InvalidArgumentException
     */
    public function regenerateReportForBook(Book $book): AvailabilityReport {
        $this->cache->delete(sprintf(self::KEY_PATTERN, $book->getId()));
        return $this->generateReportForBook($book);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function generateReportForBook(Book $book): AvailabilityReport {
        return $this->cache->get(sprintf(self::KEY_PATTERN, $book->getId()), function (ItemInterface $item) use($book): AvailabilityReport {
            $item->expiresAfter(self::LIFETIME_IN_SECONDS);

            $notAvailable = 0;
            $checkedOut = 0;
            $availableAndNotCheckedOut = 0;

            foreach($book->getCopies() as $copy) {
                $status = $this->checkoutManager->getStatus($copy);

                switch($status) {
                    case CheckoutStatus::NotAvailable:
                        $notAvailable++;
                        break;
                    case CheckoutStatus::CheckedOut:
                        $checkedOut++;
                        break;
                    case CheckoutStatus::Available:
                        $availableAndNotCheckedOut++;
                        break;
                }
            }

            return new AvailabilityReport($notAvailable, $checkedOut, $availableAndNotCheckedOut, new DateTime());
        });
    }
}