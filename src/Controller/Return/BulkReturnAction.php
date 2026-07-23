<?php

namespace App\Controller\Return;

use App\Checkout\BulkReturnRequest;
use App\Checkout\CheckoutManager;
use App\Form\BulkReturnRequestType;
use App\Security\Voter\CheckoutVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class BulkReturnAction extends AbstractController {

    public function __construct(private readonly CheckoutManager $checkoutManager) {

    }


    #[Route('/bulk_return', name: 'return')]
    public function __invoke(
        Request $request,
        TranslatorInterface $translator
    ): Response {
        $this->denyAccessUnlessGranted(CheckoutVoter::RETURN);

        $return = new BulkReturnRequest();
        $form = $this->createForm(BulkReturnRequestType::class, $return);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $borrower = $this->checkoutManager->bulkReturn($return);

            $this->addFlash('success', $translator->trans('return.success', [
                '%count%' => count($return->copies)
            ]));

            if($borrower === null) {
                return $this->redirectToRoute('return');
            }

            return $this->redirectToRoute('show_borrower', [
                'uuid' => $borrower->getUuid()
            ]);
        }

        return $this->render('returns/bulk.html.twig', [
            'form' => $form->createView(),
            'maximumNumberOfCopies' => XhrPreviewAction::MaxNumberOfCopies
        ]);
    }
}