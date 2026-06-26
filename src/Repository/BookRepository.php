<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;

class BookRepository extends AbstractRepository implements BookRepositoryInterface{

    public function __construct(EntityManagerInterface $em) {
        parent::__construct($em);
    }

    public function findOneById(int $id): ?Book {
        return $this->em->getRepository(Book::class)
            ->findOneBy(['id' => $id]);
    }

    public function find(PaginationQuery $paginationQuery, ?string $searchQuery = null): PaginatedResult {
        $qb = $this->em->createQueryBuilder()
            ->select(['b'])
            ->from(Book::class, 'b')
            ->orderBy('b.title', 'ASC');

        if(!empty($searchQuery)) {
            $qb->where('b.title LIKE :searchQuery')
                ->orWhere('b.subtitle LIKE :searchQuery')
                ->orWhere('b.isbn LIKE :searchQuery')
                ->setParameter('searchQuery', '%' . $searchQuery . '%');
        }

        return PaginatedResult::fromQueryBuilder($qb, $paginationQuery);
    }

    public function findAll(): array {
        return $this->em->getRepository(Book::class)->findBy(
            [],
            ['title' => 'asc']
        );
    }

    public function countAll(): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(1)')
            ->from(Book::class, 'b')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function persist(Book $book): void {
        $this->em->persist($book);
        $this->em->flush();
    }

    public function remove(Book $book): void {
        $this->em->remove($book);
        $this->em->flush();
    }
}