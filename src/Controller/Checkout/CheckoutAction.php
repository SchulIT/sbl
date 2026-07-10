<?php

namespace App\Controller\Checkout;

use App\Checkout\BulkCheckoutRequest;
use App\Checkout\CheckoutManager;
use App\Form\BulkCheckoutRequestType;
use App\Security\Voter\CheckoutVoter;
use App\Student\Selector\StudentSelectorJsonGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CheckoutAction extends AbstractController {
    public function __construct(private readonly CheckoutManager $checkoutManager, private readonly StudentSelectorJsonGenerator $studentSelectorJsonGenerator) {

    }

    #[Route('/checkout', name: 'checkout')]
    public function __invoke(Request $request): Response {
        $this->denyAccessUnlessGranted(CheckoutVoter::CHECKOUT);

        $checkout = new BulkCheckoutRequest();
        $form = $this->createForm(BulkCheckoutRequestType::class, $checkout);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->checkoutManager->bulkCheckout($checkout);
            $this->addFlash('success', 'checkout.success');

            return $this->redirectToRoute('show_borrower', [
                'uuid' => $checkout->borrower->getUuid()
            ]);
        }

        return $this->render('checkouts/bulk.html.twig', [
            'form' => $form->createView(),
            'grades' => $this->studentSelectorJsonGenerator->generateGrades(),
            'maximumNumberOfCopies' => XhrPreviewAction::MaxNumberOfCopies
        ]);
    }
}