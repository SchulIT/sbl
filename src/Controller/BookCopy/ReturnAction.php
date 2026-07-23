<?php

namespace App\Controller\BookCopy;

use App\Checkout\CheckoutManager;
use App\Entity\BookCopy;
use App\Entity\Borrower;
use App\Entity\Checkout;
use App\Security\Voter\BookCopyVoter;
use SchulIT\CommonBundle\Form\ConfirmType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReturnAction extends AbstractController {

    public function __construct(private readonly CheckoutManager $checkoutManager, private readonly TranslatorInterface $translator) {

    }

    #[Route('/book/copy/{uuid}/return', name: 'return_copy')]
    public function returnCopy(
        #[MapEntity(mapping: ['uuid' => 'uuid'])] BookCopy $copy,
        Request $request,
        TranslatorInterface $translator
    ): RedirectResponse|Response {
        $this->denyAccessUnlessGranted(BookCopyVoter::RETURN, $copy);

        /** @var Checkout|null $lastCheckout */
        $lastCheckout = $copy->getCheckouts()->first();
        /** @var Borrower|null $lastBorrower */
        $lastBorrower = $lastCheckout?->getBorrower();

        if($lastCheckout === false || $lastCheckout->getEnd() !== null || $lastBorrower === null) {
            return $this->redirectToRoute('book_copy', [
                'uuid' => $copy->getUuid(),
            ]);
        }

        $form = $this->createForm(ConfirmType::class, [], [
            'message' => 'return.confirm',
            'message_parameters' => [
                '%title%' => $copy->getBook()->getTitle(),
                '%firstname%' => $lastBorrower->getFirstName(),
                '%lastname%' => $lastBorrower->getLastName(),
                '%type%' => $lastBorrower->getType()->trans($this->translator)
            ]
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->checkoutManager->return($copy);

            $this->addFlash('success', $translator->trans('return.success', [
                '%count%' => 1
            ]));

            return $this->redirectToRoute('book_copy', [
                'uuid' => $copy->getUuid(),
            ]);
        }

        return $this->render('books/copy/return.html.twig', [
            'book' => $copy->getBook(),
            'copy' => $copy,
            'form' => $form->createView(),
        ]);
    }
}