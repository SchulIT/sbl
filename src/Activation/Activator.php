<?php

namespace App\Activation;

use App\Entity\BookCopy;
use App\Repository\BookCopyRepositoryInterface;

readonly class Activator {

    public function __construct(
        private BookCopyRepositoryInterface $bookCopyRepository
    ) {

    }

    public function bulkActivate(BulkActivationRequest $request): int {
        $count = 0;

        $copies = $this->bookCopyRepository->findAllByIds($request->copies);
        $this->bookCopyRepository->beginTransaction();
        foreach($copies as $copy) {
            $count += $this->activate($copy) ? 1 : 0;
        }
        $this->bookCopyRepository->commit();

        return $count;
    }

    public function activate(BookCopy $copy): bool {
        if($copy->isActivated()) {
            return false;
        }

        $copy->setIsActivated(true);
        $this->bookCopyRepository->persist($copy);

        return true;
    }
}