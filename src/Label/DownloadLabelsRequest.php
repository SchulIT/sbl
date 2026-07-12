<?php

namespace App\Label;

use App\Entity\BookCopy;
use App\Entity\LabelTemplate;
use Symfony\Component\Validator\Constraints as Assert;

class DownloadLabelsRequest {
    /**
     * @var BookCopy[]
     */
    #[Assert\Count(min: 1)]
    public array $copies = [ ];

    #[Assert\NotNull]
    public ?LabelTemplate $template = null;

    #[Assert\GreaterThanOrEqual(0)]
    public int $offset = 0;

    public bool $setPrintedAtDate = true;

    public bool $skipAlreadyPrinted = true;
}