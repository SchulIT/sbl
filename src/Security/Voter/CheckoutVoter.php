<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CheckoutVoter extends Voter {

    public const string CHECKOUT = 'can-checkout-any';
    public const string RETURN = 'can-return-any';

    public const string SHOW_ANY = 'can-show-any';

    public function __construct(private readonly AccessDecisionManagerInterface $accessDecisionManager) { }

    protected function supports(string $attribute, mixed $subject): bool {
        return $attribute === self::CHECKOUT
            || $attribute === self::RETURN
            || $attribute === self::SHOW_ANY;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
        return $this->accessDecisionManager->decide($token, ['ROLE_LENDER']);
    }
}