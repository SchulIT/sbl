<?php

namespace App\Controller\Checkout;

use App\Entity\Book;
use App\Repository\BookRepositoryInterface;
use App\Repository\BorrowerRepositoryInterface;
use App\Repository\CheckoutRepositoryInterface;
use App\Repository\PaginationQuery;
use SchulIT\CommonBundle\Helper\DateHelper;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class IndexAction extends AbstractController {

    public function __construct(
        private readonly CheckoutRepositoryInterface $checkoutRepository,
        private readonly BorrowerRepositoryInterface $borrowerRepository,
        private readonly BookRepositoryInterface $bookRepository,
        private readonly DateHelper $dateHelper,
    ) {

    }

    #[Route('/checkout', name: 'checkouts')]
    public function __invoke(

        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $limit = 25,
        #[MapEntity(mapping: ['book' => 'uuid'])] Book|null $book = null,
        #[MapQueryParameter(name: 'grade', filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $grade = null,
        #[MapQueryParameter(name: 'all')] bool $includePastCheckouts = false,
        #[MapQueryParameter(name: 'overdue')] bool $onlyShowOverdue = false
    ): Response {
        if($onlyShowOverdue) {
            $includePastCheckouts = false;
        }

        $checkouts = $this->checkoutRepository->findPaginated(new PaginationQuery(page: $page, limit: $limit), $book, $grade, !$includePastCheckouts, $onlyShowOverdue ? $this->dateHelper->getToday() : null);
        $grades = $this->borrowerRepository->findGrades();
        $books = $this->bookRepository->findAll();

        return $this->render('checkouts/index.html.twig', [
            'checkouts' => $checkouts,
            'book' => $book,
            'grade' => $grade,
            'grades' => $grades,
            'books' => $books,
            'all' => $includePastCheckouts,
            'overdue' => $onlyShowOverdue,
            'today' => $this->dateHelper->getToday(),
        ]);
    }
}