<?php

namespace App\Book;

use DateTime;

readonly class AvailabilityReport {
    public function __construct(public int $notAvailableCount, public int $checkedOutCount, public int $availableAndNotCheckedOut, public DateTime $createdAt) {

    }
}