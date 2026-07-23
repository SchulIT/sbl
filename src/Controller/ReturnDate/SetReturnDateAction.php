<?php

namespace App\Controller\ReturnDate;

use App\Checkout\ReturnDateSetter;
use App\Checkout\SetReturnDateRequest;
use App\Form\SetReturnDateRequestType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

class SetReturnDateAction extends AbstractController {

    #[Route('/return_date', name: 'return_date')]
    #[IsGranted('ROLE_BOOKS_ADMIN')]
    public function __invoke(
        ReturnDateSetter $returnDateSetter,
        Request $request,
        TranslatorInterface $translator
    ): Response {
        $returnDateRequest = new SetReturnDateRequest();

        $form = $this->createForm(SetReturnDateRequestType::class, $returnDateRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $count = $returnDateSetter->setCheckoutDate($returnDateRequest);

            $this->addFlash(
                'success',
                $translator->trans(
                    'return_date.success',
                    [
                        '%count%' => $count,
                        '%date%' => $returnDateRequest->returnDate->format($translator->trans('date.time'))
                    ]
                )
            );

            return $this->redirectToRoute('return_date');
        }

        return $this->render('return_date/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}