<?php

namespace App\Controller\Book;

use App\Book\AvailabilityReportGenerator;
use App\Repository\BookCopyRepositoryInterface;
use App\Repository\BookRepositoryInterface;
use App\Repository\PaginationQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class IndexAction extends AbstractController {
    public function __construct(private readonly BookRepositoryInterface $repository,
                                private readonly AvailabilityReportGenerator $availabilityReportHelper) {

    }

    #[Route('/book', name: 'books')]
    public function __invoke(
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $limit = 25,
        #[MapQueryParameter(name: 'q', filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $searchQuery = null,
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_BOOKS_ADMIN');

        $books = $this->repository->find(new PaginationQuery(page: $page, limit: $limit), $searchQuery);

        if(!empty($searchQuery) && count($books) === 1) {
            $book = array_first($books->getIterator()->getArrayCopy());

            return $this->redirectToRoute('show_book', [ 'uuid' => $book->getUuid() ]);
        }

        $reports = [ ];

        foreach($books as $book) {
            $reports[$book->getId()] = $this->availabilityReportHelper->generateReportForBook($book);
        }

        return $this->render('books/index.html.twig', [
            'books' => $books,
            'query' => $searchQuery,
            'reports' => $reports
        ]);
    }
}