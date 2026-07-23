<?php

namespace App\Controller\Borrower;

use App\Entity\Borrower;
use App\Entity\Checkout;
use App\Security\Voter\BorrowerVoter;
use SchulIT\CommonBundle\Helper\DateHelper;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShowAction extends AbstractController {


    #[Route('/borrower/{uuid}', name: 'show_borrower')]
    public function __invoke(
        #[MapEntity(mapping: ['uuid' => 'uuid'])] Borrower $borrower,
        DateHelper $dateHelper
    ): Response {
        $this->denyAccessUnlessGranted(BorrowerVoter::SHOW, $borrower);

        $activeCheckouts = $borrower->getCheckouts()->filter(fn(Checkout $c) => $c->getEnd() === null);
        $pastCheckouts = $borrower->getCheckouts()->filter(fn(Checkout $c) => $c->getEnd() !== null);

        return $this->render('borrowers/show.html.twig', [
            'borrower' => $borrower,
            'activeCheckouts' => $activeCheckouts,
            'pastCheckouts' => $pastCheckouts,
            'today' => $dateHelper->getToday()
        ]);
    }
}