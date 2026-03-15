<?php

namespace App\Controller\Book;

use App\Book\AvailabilityReportGenerator;
use App\Repository\BookCopyRepositoryInterface;
use App\Repository\BookRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexAction extends AbstractController {
    public function __construct(private readonly BookRepositoryInterface $repository,
                                private readonly AvailabilityReportGenerator $availabilityReportHelper) {

    }

    #[Route('/book', name: 'books')]
    public function __invoke(Request $request): Response {
        $this->denyAccessUnlessGranted('ROLE_BOOKS_ADMIN');

        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 25);
        $searchQuery = $request->query->get('q');

        $result = $this->repository->find($page, $limit, $searchQuery);
        $books = $result->result;

        if(!empty($searchQuery) && $result->totalCount === 1) {
            $book = $result->result[0];

            return $this->redirectToRoute('show_book', [ 'uuid' => $book->getUuid() ]);
        }

        $reports = [ ];

        foreach($result->result as $book) {
            $reports[$book->getId()] = $this->availabilityReportHelper->generateReportForBook($book);
        }

        return $this->render('books/index.html.twig', [
            'books' => $result->result,
            'page' => $page,
            'pages' => ceil((double)$result->totalCount / $limit),
            'query' => $searchQuery,
            'reports' => $reports
        ]);
    }
}