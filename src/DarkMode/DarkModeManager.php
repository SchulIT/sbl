<?php

namespace App\DarkMode;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use SchulIT\CommonBundle\DarkMode\DarkModeManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DarkModeManager implements DarkModeManagerInterface {

    private const string Key = 'settings.dark_mode.enabled';

    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly UserRepositoryInterface $userRepository
    ) { }

    private function setDarkMode(bool $isDarkModeEnabled): void {
        $user = $this->tokenStorage->getToken()?->getUser();

        if(!$user instanceof User) {
            return;
        }

        $user->setData(self::Key, $isDarkModeEnabled);
        $this->userRepository->persist($user);
    }

    public function enableDarkMode(): void {
        $this->setDarkMode(true);
    }

    public function disableDarkMode(): void {
        $this->setDarkMode(false);
    }

    public function isDarkModeEnabled(): bool {
        $user = $this->tokenStorage->getToken()?->getUser();

        if(!$user instanceof User) {
            return false;
        }

        return $user->getData(self::Key, false) === true;
    }
}