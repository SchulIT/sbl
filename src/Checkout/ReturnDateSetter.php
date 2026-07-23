<?php

namespace App\Checkout;

use App\Repository\CheckoutRepositoryInterface;
use InvalidArgumentException;

readonly class ReturnDateSetter {
    public function __construct(
        private CheckoutRepositoryInterface $checkoutRepository
    ) {

    }

    public function setCheckoutDate(SetReturnDateRequest $request): int {
        if($request->book === null) {
            throw new InvalidArgumentException('Book must not be empty.');
        }

        if($request->returnDate === null) {
            throw new InvalidArgumentException('Return date must not be empty.');
        }

        return $this->checkoutRepository->setExpectedReturnDate($request->book, $request->returnDate, $request->overrideExistingReturnDates, $request->grades);
    }
}
