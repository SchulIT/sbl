<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookCopy;

interface BookCopyRepositoryInterface extends TransactionalRepositoryInterface {
    
    public function findById(int $id): ?BookCopy;

    /**
     * @return BookCopy[]
     */
    public function findAll(): array;

    /**
     * @param int[] $ids
     * @return BookCopy[]
     */
    public function findAllByIds(array $ids): array;

    /**
     * @param Book $book
     * @return BookCopy[]
     */
    public function findByBook(Book $book): array;

    /**
     * @param Book $book
     * @param PaginationQuery $paginationQuery
     * @return PaginatedResult<BookCopy>
     */
    public function findByBookPaginated(Book $book, PaginationQuery $paginationQuery): PaginatedResult;

    public function countNotAvailableByBook(Book $book): int;

    public function countAll(): int;

    public function persist(BookCopy $copy): void;

    public function remove(BookCopy $copy): void;
}