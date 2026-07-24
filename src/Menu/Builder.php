<?php

namespace App\Menu;

use App\Security\Voter\BookVoter;
use App\Security\Voter\BorrowerVoter;
use App\Security\Voter\CheckoutVoter;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use SchulIT\CommonBundle\Helper\DateHelper;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class Builder {
    public function __construct(private FactoryInterface $factory, private readonly AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    public function mainMenu(array $options): ItemInterface {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'navbar-nav me-auto');

        $menu->addChild('dashboard.label', [
            'route' => 'dashboard'
        ])
            ->setExtra('icon', 'fa fa-home');

        if($this->authorizationChecker->isGranted(CheckoutVoter::CHECKOUT)) {
            $menu->addChild('checkout.label', [
                'route' => 'checkout'
            ])
                ->setExtra('icon', 'fa fa-shopping-cart');
        }

        if($this->authorizationChecker->isGranted(CheckoutVoter::RETURN)) {
            $menu->addChild('return.label', [
                'route' => 'return'
            ])
                ->setExtra('icon', 'fa fa-reply');
        }

        if($this->authorizationChecker->isGranted('ROLE_BOOKS_ADMIN')) {
            $menu->addChild('return_date.label', [
                'route' => 'return_date'
            ])
                ->setExtra('icon', 'fa fa-calendar');
        }

        if($this->authorizationChecker->isGranted(CheckoutVoter::SHOW_ANY)) {
            $menu->addChild('checkouts.label', [
                'route' => 'checkouts'
            ])
                ->setExtra('icon', 'fa fa-list');
        }

        if($this->authorizationChecker->isGranted('ROLE_BOOKS_ADMIN')) {
            $menu->addChild('books.label', [
                'route' => 'books'
            ])
                ->setExtra('icon', 'fas fa-book');
        }

        if($this->authorizationChecker->isGranted(BorrowerVoter::SHOW_ANY)) {
            $menu->addChild('borrowers.label', [
                'route' => 'borrowers'
            ])
                ->setExtra('icon', 'fas fa-users');
        }

        if($this->authorizationChecker->isGranted('ROLE_BOOKS_ADMIN')) {
            $menu->addChild('labels.label', [
                    'route' => 'labels'
                ])
                ->setExtra('icon', 'fa fa-barcode');
        }

        return $menu;
    }
}