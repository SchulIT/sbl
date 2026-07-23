<?php

namespace App\Checkout;

use App\Entity\Book;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

class SetReturnDateRequest {

    #[Assert\NotNull]
    public Book|null $book = null;

    #[Assert\NotNull]
    public DateTime|null $returnDate = null;

    public bool $overrideExistingReturnDates = false;

    public array $grades = [ ];
}