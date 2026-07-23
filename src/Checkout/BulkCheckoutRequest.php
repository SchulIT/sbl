<?php

namespace App\Checkout;

use App\Entity\BookCopy;
use App\Entity\Borrower;
use Symfony\Component\Validator\Constraints as Assert;

class BulkCheckoutRequest {

    #[Assert\NotNull]
    public ?Borrower $borrower = null;

    /**
     * @var int[]
     */
    #[Assert\Count(min: 1)]
    public array $copies = [ ];
}