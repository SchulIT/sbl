<?php

namespace App\Controller\Checkout;

use App\Checkout\Bulk\BulkManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class BulkAction extends AbstractController {

    public const string CSRF_TOKEN_ID = 'checkouts-bulk';

    public function __construct(
        private readonly BulkManager $bulkManager,
        private readonly TranslatorInterface $translator
    ) {

    }

    #[Route('/checkout/bulk', name: 'checkouts_bulk', methods: ['POST'])]
    public function __invoke(
        Request $request
    ): RedirectResponse {
        if(!$this->isCsrfTokenValid(self::CSRF_TOKEN_ID, $request->request->get('_csrf_token'))) {
            $this->addFlash('error', 'Invalid CSRF token');
            return $this->redirectToRoute('checkouts');
        }

        $uuids = explode(',', $request->request->get('checkouts', ''));
        $action = $request->request->get('action');

        $count = $this->bulkManager->perform($uuids, $action, $request);

        $this->addFlash(
            'success',
            $this->translator->trans(
                'checkouts.bulk.success',
                ['%count%' => $count],
            )
        );

        return $this->redirectToRoute('checkouts');
    }
}