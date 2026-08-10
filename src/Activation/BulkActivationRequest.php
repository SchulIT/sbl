<?php

namespace App\Activation;

use Symfony\Component\Validator\Constraints as Assert;

class BulkActivationRequest {

    /**
     * @var int[]
     */
    #[Assert\Count(min: 1)]
    public array $copies = [ ];
}