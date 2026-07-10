<?php

namespace App\Checkout;

use App\Controller\Return\XhrPreviewAction;
use App\Entity\BookCopy;
use Symfony\Component\Validator\Constraints as Assert;

class BulkReturnRequest {
    /**
     * @var BookCopy[]
     */
    #[Assert\Count(min: 1, max: XhrPreviewAction::MaxNumberOfCopies)]
    public array $copies = [ ];
}