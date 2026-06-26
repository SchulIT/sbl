<?php

namespace App\Repository;

use App\Entity\Book;

interface BookRepositoryInterface {

    public function findOneById(int $id): ?Book;

    /**
     * @return PaginatedResult<Book>
     */
    public function find(PaginationQuery $paginationQuery, ?string $searchQuery = null): PaginatedResult;

    /**
     * @return Book[]
     */
    public function findAll(): array;

    public function countAll(): int;

    public function persist(Book $book): void;

    public function remove(Book $book): void;
}