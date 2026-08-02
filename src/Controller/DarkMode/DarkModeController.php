<?php

declare(strict_types=1);

namespace App\Controller\DarkMode;

use SchulIT\CommonBundle\Controller\DarkModeController as BaseDarkModeController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/settings/darkmode')]
class DarkModeController extends BaseDarkModeController { }
