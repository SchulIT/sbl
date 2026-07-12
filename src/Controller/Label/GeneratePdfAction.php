<?php

namespace App\Controller\Label;

use App\Form\DownloadLabelsRequestType;
use App\Http\HttpUtils;
use App\Label\DownloadLabelsRequest;
use App\Label\PdfCreator;
use App\Repository\BookCopyRepositoryInterface;
use App\Repository\LabelRepositoryInterface;
use App\Settings\AppSettings;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GeneratePdfAction extends AbstractController {

    public function __construct(private readonly PdfCreator $pdfCreator, private readonly BookCopyRepositoryInterface $bookCopyRepository) {

    }

    #[Route('/labels/pdf', name: 'download_pdf_labels')]
    public function download(
        Request $request,
        HttpUtils $httpUtils,
        AppSettings $appSettings,
        LabelRepositoryInterface $labelRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_BOOKS_ADMIN');

        $downloadRequest = new DownloadLabelsRequest();

        if($appSettings->defaultLabelTemplate !== null) {
            $defaultLabel = $labelRepository->findOneById($appSettings->defaultLabelTemplate->getId());
            $downloadRequest->template = $defaultLabel;
        }

        if($request->query->has('copies')) {
            $ids = $httpUtils->parseCharacterSeparatedRequestParamAsIntArray($request, 'copies');
            $downloadRequest->copies = $this->bookCopyRepository->findAllByIds($ids);
        }

        $form = $this->createForm(DownloadLabelsRequestType::class, $downloadRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->pdfCreator->createPdfResponse($downloadRequest);
        }

        return $this->render('labels/download.html.twig', [
            'form' => $form->createView()
        ]);
    }
}