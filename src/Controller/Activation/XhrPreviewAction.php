<?php

namespace App\Controller\Activation;

use App\Checkout\CheckoutManager;
use App\Http\HttpUtils;
use App\Repository\BookCopyRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

class XhrPreviewAction extends AbstractController {

    public const int MaxNumberOfCopies = 100;

    #[Route('/activation/xhr', name: 'xhr_activation')]
    public function index(Request $request, HttpUtils $httpUtils, BookCopyRepositoryInterface $copyRepository, CheckoutManager $checkoutManager): Response {
        $ids = $httpUtils->parseCharacterSeparatedRequestParamAsIntArray($request, 'ids');

        if(count($ids) > self::MaxNumberOfCopies) {
            throw new BadRequestHttpException(sprintf('Anfrage darf nicht mehr als %d IDs enthalten.', self::MaxNumberOfCopies));
        }

        $copies = $copyRepository->findAllByIds($ids);

        return $this->render('activation/preview.html.twig', [
            'copies' => $copies
        ]);
    }
}
