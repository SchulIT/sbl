<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookCopy;
use App\Entity\Borrower;
use App\Entity\Checkout;
use DateTime;

interface CheckoutRepositoryInterface extends TransactionalRepositoryInterface {

    /**
     * @param Borrower $borrower
     * @return Checkout[]
     */
    public function findActiveByBorrower(Borrower $borrower): array;

    public function setExpectedReturnDate(Book $book, DateTime $dueDate, bool $overrideExistingReturnDates = false, array $grades = [ ]): int;

    public function countActive(): int;

    public function countAll(): int;

    public function countOverdue(DateTime $today): int;

    /**
     * @param PaginationQuery $paginationQuery
     * @param Book|null $book
     * @param string|null $grade
     * @param bool $onlyActive
     * @param DateTime|null $todayForOverdue If this date is set, only checkouts with an expected return date BEFORE this date are returned
     * @return PaginatedResult<Checkout>
     */
    public function findPaginated(PaginationQuery $paginationQuery, Book|null $book = null, string|null $grade = null, bool $onlyActive = true, DateTime|null $todayForOverdue = null): PaginatedResult;

    /**
     * @param string[] $uuids
     * @return Checkout[]
     */
    public function findAllByUuids(array $uuids): array;

    public function persist(Checkout $checkout): void;

    public function remove(Checkout $checkout): void;
}