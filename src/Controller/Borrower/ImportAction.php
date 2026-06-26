<?php

namespace App\Controller\Borrower;

use App\Controller\Book\AddAction;
use App\Form\BorrowerImportType;
use App\Import\Borrower\CsvImporter;
use App\Security\Voter\BorrowerVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ImportAction extends AbstractController {


    #[Route('/borrower/import', name: 'import_borrowers')]
    public function __invoke(Request $request, CsvImporter $csvImporter): Response {
        $this->denyAccessUnlessGranted(BorrowerVoter::IMPORT);

        $form = $this->createForm(BorrowerImportType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $type = $form->get('type')->getData();
            $delimiter = $form->get('delimiter')->getData();
            $file = file_get_contents($form->get('file')->getData()->getPathname());
            $delete = $form->get('delete')->getData();

            $csvImporter->importCsv($file, $type, $delimiter, $delete);
            $this->addFlash('success', 'borrowers.add.success');

            return $this->redirectToRoute('borrowers');
        }

        return $this->render('borrowers/import.html.twig', [
            'form' => $form->createView()
        ]);
    }
}