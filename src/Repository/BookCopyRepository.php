<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookCopy;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BookCopyRepository extends AbstractTransactionalRepository implements BookCopyRepositoryInterface {

    public function findById(int $id): ?BookCopy {
        return $this->em->getRepository(BookCopy::class)->findOneBy(['id' => $id]);
    }

    public function findAll(): array {
        return $this->em->getRepository(BookCopy::class)->findAll();
    }

    public function findByBook(Book $book): array {
        return $this->em->getRepository(BookCopy::class)
            ->findBy(
                [
                    'book' => $book
                ],
                [
                    'createdAt' => 'ASC'
                ]
            );
    }

    public function findAllByIds(array $ids): array {
        return $this->em->createQueryBuilder()
            ->select('c')
            ->from(BookCopy::class, 'c')
            ->where('c.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }

    public function findByBookPaginated(Book $book, PaginationQuery $paginationQuery): PaginatedResult {
        $qb = $this->em->createQueryBuilder()
            ->select(['c', 'b'])
            ->from(BookCopy::class, 'c')
            ->leftJoin('c.book', 'b')
            ->orderBy('c.createdAt', 'ASC')
            ->addOrderBy('c.id', 'ASC')
            ->where('c.book = :book')
            ->setParameter('book', $book);

        return PaginatedResult::fromQueryBuilder($qb, $paginationQuery);
    }

    public function persist(BookCopy $copy): void {
        $this->em->persist($copy);
        $this->flushIfNotInTransaction();
    }

    public function remove(BookCopy $copy): void {
        $this->em->remove($copy);
        $this->flushIfNotInTransaction();
    }

    public function countNotAvailableByBook(Book $book): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(b)')
            ->from(BookCopy::class, 'b')
            ->where('b.book = :book')
            ->andWhere('b.canCheckout = false')
            ->setParameter('book', $book)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countAll(): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(1)')
            ->from(BookCopy::class, 'c')
            ->getQuery()
            ->getSingleScalarResult();
    }
}