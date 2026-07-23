<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookCopy;
use App\Entity\Borrower;
use App\Entity\Checkout;
use DateTime;

interface CheckoutRepositoryInterface {

    /**
     * @param Borrower $borrower
     * @return Checkout[]
     */
    public function findActiveByBorrower(Borrower $borrower): array;

    public function setExpectedReturnDate(Book $book, DateTime $dueDate, bool $overrideExistingReturnDates = false, array $grades = [ ]): int;

    public function countActive(): int;

    public function countAll(): int;

    public function persist(Checkout $checkout): void;

    public function remove(Checkout $checkout): void;
}