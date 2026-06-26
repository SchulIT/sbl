<?php

namespace App\Controller\Borrower;

use App\Borrower\BorrowerReportGenerator;
use App\Entity\BorrowerType;
use App\Repository\BorrowerRepositoryInterface;
use App\Repository\PaginationQuery;
use App\Security\Voter\BorrowerVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

class IndexAction extends AbstractController {

    public const string CHECK_VALUE = '✓';

    public function __construct(private readonly BorrowerRepositoryInterface $repository, private readonly BorrowerReportGenerator $borrowerReportGenerator) {

    }

    #[Route('/borrower', name: 'borrowers')]
    public function __invoke(
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter(name: 'type', filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] BorrowerType|null $type = null,
        #[MapQueryParameter(name: 'grade', filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $grade = null,
        #[MapQueryParameter(name: 'q', filter: FILTER_DEFAULT, flags: FILTER_FLAG_EMPTY_STRING_NULL | FILTER_NULL_ON_FAILURE)] string|null $searchQuery = null,
        #[MapQueryParameter(name: 'active_checkouts')] bool $onlyWithActiveCheckouts = false
    ): Response {
        $this->denyAccessUnlessGranted(BorrowerVoter::SHOW_ANY);

        $selectedTypes = BorrowerType::cases();

        if($type !== null) {
            $selectedTypes = [ $type ];
        }

        $borrowers = $this->repository->find($selectedTypes, new PaginationQuery(page: $page), $grade, $searchQuery, $onlyWithActiveCheckouts);

        if(!empty($searchQuery) && count($borrowers) === 1) {
            $borrower = array_first($borrowers->getIterator()->getArrayCopy());

            return $this->redirectToRoute('show_borrower', [
                'uuid' => $borrower->getUuid()
            ]);
        }

        $grades = $this->repository->findGrades();
        $reports = [ ];

        foreach($borrowers as $borrower) {
            $reports[$borrower->getId()] = $this->borrowerReportGenerator->generateReportForBorrower($borrower);
        }

        return $this->render('borrowers/index.html.twig', [
            'borrowers' => $borrowers,
            'grade' => $grade,
            'grades' => $grades,
            'query' => $searchQuery,
            'types' => BorrowerType::cases(),
            'selectedType' => count($selectedTypes) > 1 ? null : $selectedTypes[0],
            'reports' => $reports,
            'active_checkouts' => $onlyWithActiveCheckouts
        ]);
    }
}