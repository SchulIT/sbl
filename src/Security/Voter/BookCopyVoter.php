<?php

namespace App\Security\Voter;

use App\Checkout\CheckoutManager;
use App\Entity\BookCopy;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BookCopyVoter extends Voter {
    public const string ADD = 'add-book-copy';
    public const string EDIT = 'edit';
    public const string REMOVE = 'remove';
    public const string CHECKOUT = 'checkout';
    public const string RETURN = 'return';

    public const string ACTIVATE = 'activate-book-copy';

    public function __construct(private readonly AccessDecisionManagerInterface $accessDecisionManager, private readonly CheckoutManager $checkoutManager) {

    }

    protected function supports(string $attribute, mixed $subject): bool {
        return $attribute === self::ADD
            || $attribute === self::ACTIVATE
            || ($subject instanceof BookCopy && in_array($attribute, [self::EDIT, self::REMOVE, self::CHECKOUT, self::RETURN]));
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
        switch($attribute) {
            case self::ADD:
                return $this->canAdd($token);

            case self::EDIT:
                return $this->canEdit($token);

            case self::REMOVE:
                return $this->canRemove($token);

            case self::CHECKOUT:
                return $this->canCheckout($token, $subject);

            case self::RETURN:
                return $this->canReturn($token, $subject);

            case self::ACTIVATE:
                return $this->canActivate($token);
        }

        throw new LogicException('This code should not be reached!');
    }

    private function canAdd(TokenInterface $token): bool {
        return $this->accessDecisionManager->decide($token, ['ROLE_BOOKS_ADMIN']);
    }

    private function canActivate(TokenInterface $token): bool {
        return $this->accessDecisionManager->decide($token, ['ROLE_BOOKS_ADMIN']);
    }

    private function canEdit(TokenInterface $token): bool {
        return $this->accessDecisionManager->decide($token, ['ROLE_BOOKS_ADMIN']);
    }

    private function canRemove(TokenInterface $token): bool {
        return $this->accessDecisionManager->decide($token, ['ROLE_BOOKS_ADMIN']);
    }

    private function canCheckout(TokenInterface $token, BookCopy $copy): bool {
        return $this->accessDecisionManager->decide($token, ['ROLE_LENDER'])
            && $copy->canCheckout() === true
            && $this->checkoutManager->isAvailable($copy);
    }

    private function canReturn(TokenInterface $token, BookCopy $copy): bool {
        return $this->accessDecisionManager->decide($token, ['ROLE_LENDER'])
            && $copy->canCheckout() === true;
    }
}