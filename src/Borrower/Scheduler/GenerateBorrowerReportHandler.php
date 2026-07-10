<?php

namespace App\Borrower\Scheduler;

use App\Borrower\BorrowerReportGenerator;
use App\Repository\BorrowerRepositoryInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GenerateBorrowerReportHandler {
    public function __construct(private BorrowerReportGenerator $reportGenerator,
                                private BorrowerRepositoryInterface $borrowerRepository) {

    }

    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(GenerateBorrowerReport $message): void {
        $borrower = $this->borrowerRepository->findOneById($message->getBorrowerId());

        if($borrower === null) {
            return;
        }

        $this->reportGenerator->regenerateReportForBorrower($borrower);
    }
}