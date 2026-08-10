<?php

namespace App\Controller\Activation;

use App\Activation\Activator;
use App\Activation\BulkActivationRequest;
use App\Activation\BulkActivationRequestType;
use App\Security\Voter\BookCopyVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class BulkActivateActionController extends AbstractController {

    public function __construct(
        private readonly Activator $activator,
        private readonly TranslatorInterface $translator
    ) {

    }

    #[Route('/activation', name: 'bulk_activate')]
    public function __invoke(Request $request): Response {
        $this->denyAccessUnlessGranted(BookCopyVoter::ACTIVATE);

        $activationRequest = new BulkActivationRequest();
        $form = $this->createForm(BulkActivationRequestType::class, $activationRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $count = $this->activator->bulkActivate($activationRequest);
            $this->addFlash(
                'success',
                $this->translator->trans('activation.success', ['%count%' => $count])
            );

            return $this->redirectToRoute('bulk_activate');
        }

        return $this->render('activation/bulk.html.twig', [
            'form' => $form->createView(),
            'maximumNumberOfCopies' => XhrPreviewAction::MaxNumberOfCopies
        ]);
    }
}
