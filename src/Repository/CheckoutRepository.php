<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Borrower;
use App\Entity\Checkout;
use DateTime;
use Override;

class CheckoutRepository extends AbstractTransactionalRepository implements CheckoutRepositoryInterface {

    public function persist(Checkout $checkout): void {
        $this->em->persist($checkout);
        $this->em->flush();
    }

    public function remove(Checkout $checkout): void {
        $this->em->remove($checkout);
        $this->em->flush();
    }

    public function findActiveByBorrower(Borrower $borrower): array {
        return $this->em->createQueryBuilder()
            ->select(['c'])
            ->from(Checkout::class, 'c')
            ->leftJoin('c.borrower', 'b')
            ->where('c.borrower = :borrower')
            ->andWhere('c.end IS NULL')
            ->setParameter('borrower', $borrower)
            ->getQuery()
            ->getResult();
    }

    public function countActive(): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(1)')
            ->from(Checkout::class, 'c')
            ->where('c.end IS NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countAll(): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(1)')
            ->from(Checkout::class, 'c')
            ->getQuery()
            ->getSingleScalarResult();
    }

    #[Override]
    public function countOverdue(DateTime $today): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(c)')
            ->from(Checkout::class, 'c')
            ->where('c.end IS NULL')
            ->andWhere('c.expectedReturnDate < :today')
            ->setParameter('today', $today)
            ->getQuery()
            ->getSingleScalarResult();
    }

    #[Override]
    public function setExpectedReturnDate(Book $book, DateTime $dueDate, bool $overrideExistingReturnDates = false, array $grades = []): int {
        $qb = $this->em->createQueryBuilder()
            ->update(Checkout::class, 'c')
            ->set('c.expectedReturnDate', ':dueDate')
            ->setParameter('dueDate', $dueDate)
            ->setParameter('book', $book);

        $qbInner = $this->em->createQueryBuilder()
            ->select('cInner.id')
            ->from(Checkout::class, 'cInner')
            ->leftJoin('cInner.borrower', 'bInner')
            ->leftJoin('cInner.bookCopy', 'copyInner')
            ->leftJoin('copyInner.book', 'bookInner')
            ->where('bookInner.id = :book')
            ->andWhere('cInner.end IS NULL');

        if($overrideExistingReturnDates === false) {
            $qbInner
                ->andWhere('cInner.expectedReturnDate IS NULL');
        }

        if(count($grades) > 0) {
            $qbInner
                ->andWhere('bInner.grade IN (:grades)');
            $qb->setParameter('grades', $grades);
        }

        return $qb
            ->where(
                $qb->expr()->in(
                    'c.id', $qbInner->getDQL()
                )
            )
            ->getQuery()
            ->getSingleScalarResult();
    }

    #[Override]
    public function findPaginated(PaginationQuery $paginationQuery, ?Book $book = null, ?string $grade = null, bool $onlyActive = true, DateTime|null $todayForOverdue = null): PaginatedResult {
        $qb = $this->em->createQueryBuilder()
            ->select(['c', 'b', 'copy', 'book'])
            ->from(Checkout::class, 'c')
            ->leftJoin('c.borrower', 'b')
            ->leftJoin('c.bookCopy', 'copy')
            ->leftJoin('copy.book', 'book')
            ->orderBy('b.lastname', 'asc')
            ->addOrderBy('b.firstname', 'asc');

        $qbInner = $this->em->createQueryBuilder()
            ->select('cInner.id')
            ->from(Checkout::class, 'cInner')
            ->leftJoin('cInner.borrower', 'bInner')
            ->leftJoin('cInner.bookCopy', 'copyInner')
            ->leftJoin('copyInner.book', 'bookInner');

        if($onlyActive) {
            $qbInner->andWhere('cInner.end IS NULL');
        }

        if($todayForOverdue !== null) {
            $qbInner->andWhere('cInner.expectedReturnDate IS NOT NULL')
                ->andWhere('cInner.expectedReturnDate < :today');
            $qb->setParameter('today', $todayForOverdue);
        }

        if($grade !== null) {
            $qbInner->andWhere('bInner.grade = :grade');
            $qb->setParameter('grade', $grade);
        }

        if($book !== null) {
            $qbInner->andWhere('book.id = :book');
            $qb->setParameter('book', $book->getId());
        }

        $qb->andWhere(
            $qb->expr()->in(
                'c.id', $qbInner->getDQL()
            )
        );

        return PaginatedResult::fromQueryBuilder(
            $qb,
            $paginationQuery
        );
    }

    #[Override]
    public function findAllByUuids(array $uuids): array {
        return $this->em->createQueryBuilder()
            ->select(['c'])
            ->from(Checkout::class, 'c')
            ->where('c.uuid IN (:uuids)')
            ->setParameter('uuids', $uuids)
            ->getQuery()
            ->getResult();
    }
}