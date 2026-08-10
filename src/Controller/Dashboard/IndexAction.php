<?php

namespace App\Controller\Dashboard;

use App\Entity\User;
use App\Repository\BookCopyRepositoryInterface;
use App\Repository\BookRepositoryInterface;
use App\Repository\BorrowerRepositoryInterface;
use App\Repository\CheckoutRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use SchulIT\CommonBundle\Helper\DateHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class IndexAction extends AbstractController {

    public function __construct(private readonly CheckoutRepositoryInterface $checkoutRepository,
                                private readonly BorrowerRepositoryInterface $borrowerRepository,
                                private readonly BookRepositoryInterface $bookRepository,
                                private readonly BookCopyRepositoryInterface $bookCopyRepository,
                                private readonly DateHelper $dateHelper) {

    }

    #[Route('/dashboard', name: 'dashboard')]
    public function indexAction(#[CurrentUser] User $user): Response {
        $totalCheckouts = $this->checkoutRepository->countAll();
        $activeCheckouts = $this->checkoutRepository->countActive();
        $overdueCheckouts = $this->checkoutRepository->countOverdue($this->dateHelper->getToday());
        $borrowersCount = $this->borrowerRepository->countAll();
        $booksCount = $this->bookRepository->countAll();
        $copiesCount = $this->bookCopyRepository->countActivated();

        $currentCheckouts = [ ];

        foreach($user->getAssociatedBorrowers() as $borrower) {
            $currentCheckouts[$borrower->getId()] = $this->checkoutRepository->findActiveByBorrower($borrower);
        }

        return $this->render('dashboard/index.html.twig', [
            'totalCheckouts' => $totalCheckouts,
            'activeCheckouts' => $activeCheckouts,
            'overdueCheckouts' => $overdueCheckouts,
            'borrowersCount' => $borrowersCount,
            'booksCount' => $booksCount,
            'copiesCount' => $copiesCount,
            'currentCheckouts' => $currentCheckouts,
            'today' => $this->dateHelper->getToday()
        ]);
    }
}