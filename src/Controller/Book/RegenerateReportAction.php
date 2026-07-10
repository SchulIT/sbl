<?php

namespace App\Controller\Book;

use App\Book\Scheduler\GenerateAvailabilityReportsTask;
use App\Borrower\Scheduler\GenerateBorrowerReportsTask;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Scheduler\Messenger\ServiceCallMessage;
use Throwable;

#[AsController]
class RegenerateReportAction extends AbstractController {

    public const string CsrfTokenId = 'book_report';

    #[Route('/book/report/regenerate', name: 'regenerate_book_report', methods: ['POST'])]
    public function __invoke(
        MessageBusInterface $messageBus,
        Request $request
    ): RedirectResponse {
        if(!$this->isCsrfTokenValid(self::CsrfTokenId, $request->request->get('_csrf_token'))) {
            $this->addFlash('error', 'CSRF-Token ungültig.');
        }

        try {
            $messageBus->dispatch(new ServiceCallMessage(GenerateAvailabilityReportsTask::class));
            $this->addFlash('success', 'books.regenerate_book_report.success');
        } catch (Throwable $exception) {
            $this->addFlash('error', $exception->getMessage());
        }

        return $this->redirectToRoute('books');
    }
}