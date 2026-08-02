<?php

namespace App\Controller\BookCopy;

use App\Entity\BookCopy;
use App\Entity\Checkout;
use SchulIT\CommonBundle\Helper\DateHelper;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShowAction extends AbstractController {
    #[Route('/book/copy/{uuid}', name: 'book_copy')]
    public function __invoke(
        #[MapEntity(mapping: ['uuid' => 'uuid'])] BookCopy $copy,
        DateHelper $dateHelper
    ): Response {
        $activeCheckouts = $copy->getCheckouts()->filter(fn(Checkout $c) => $c->getEnd() === null);
        $pastCheckouts = $copy->getCheckouts()->filter(fn(Checkout $c) => $c->getEnd() !== null);

        return $this->render('books/copy/show.html.twig', [
            'copy' => $copy,
            'book' => $copy->getBook(), // for convenience
            'activeCheckouts' => $activeCheckouts,
            'pastCheckouts' => $pastCheckouts,
            'today' => $dateHelper->getToday()
        ]);
    }
}