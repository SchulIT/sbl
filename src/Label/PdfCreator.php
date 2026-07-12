<?php

namespace App\Label;

use App\Entity\BookCopy;
use App\Entity\LabelTemplate;
use App\Repository\BookCopyRepositoryInterface;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use TCPDF;

readonly class PdfCreator {

    public function __construct(
        private BookCopyRepositoryInterface $bookCopyRepository
    ) { }

    public function createPdfResponse(DownloadLabelsRequest $request): Response {
        $response = new Response($this->createPdf($request), 200, [
            'Content-Type' => 'application/pdf'
        ]);

        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'barcodes.pdf'));

        return $response;
    }

    /**
     * @param DownloadLabelsRequest $request
     * @return string Resulting PDF document as (binary?) string
     */
    public function createPdf(DownloadLabelsRequest $request): string {
        $label = $request->template;

        $this->bookCopyRepository->beginTransaction();

        $pdf = $this->createTCPDF($label);
        $pdf->AddPage();

        $style = $this->getBarcodeStyle();

        $column = 1;
        $row = 1;

        $cellHeight = $label->getCellHeightMM() - 2 * $label->getCellPaddingMM();
        $cellWidth = $label->getCellWidthMM() - 2 * $label->getCellPaddingMM();

        $copies = $request->copies;

        if($request->skipAlreadyPrinted) {
            $copies = array_filter(
                $copies,
                fn(BookCopy $copy): bool => $copy->getLabelPrintedAt() === null
            );
        }

        array_unshift(
            $copies,
            ...array_fill(0, $request->offset, null)
        );

        foreach ($copies as $copy) {
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            /*$pdf->setCellMargins(
                $label->getCellPaddingMM(),
                $label->getCellPaddingMM(),
                $label->getCellPaddingMM(),
                $label->getCellPaddingMM()
            );*/

            if ($copy !== null) {
                if($request->setPrintedAtDate) {
                    $copy->setLabelPrintedAt(new DateTime());
                    $this->bookCopyRepository->persist($copy);
                }

                $pdf->write1DBarcode(
                    $copy->getBarcodeId(),
                    'C39',
                    $x + $label->getCellPaddingMM(),
                    $y + $label->getCellPaddingMM(),
                    $cellWidth,
                    $cellHeight * 0.55,
                    0.4,
                    $style,
                    'M'
                );
            }

            $pdf->setX($x + $label->getCellPaddingMM());

            if($copy !== null) {
                $text = !empty($copy->getBook()->getBarcodeTitle()) ? $copy->getBook()->getBarcodeTitle() : $copy->getBook()->getTitle();
                $pdf->Cell(
                    $cellWidth,
                    $cellHeight * 0.45,
                    $text,
                    align: 'C'
                );
            }

            $pdf->setXY($x + $label->getCellWidthMM(), $y);

            if($column === $label->getColumns()) {
                $pdf->Ln($label->getCellHeightMM());
                $column = 1;
                $row++;
            } else {
                $column++;
            }

            if($row === $label->getRows() + 1) {
                $pdf->AddPage();
                $row = 1;
            }
        }

        $this->bookCopyRepository->commit();

        return $pdf->Output('barcodes.pdf', 'S');
    }

    private function getBarcodeStyle(): array {
        return [
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => false,
            'cellfitalign' => '',
            'border' => 0,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => [ 0, 0, 0 ], // black
            'bgcolor' => false,
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => false
        ];
    }

    private function createTCPDF(LabelTemplate $label): TCPDF {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->setAuthor('');
        $pdf->setTitle('Barcodes');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->setTopMargin($label->getTopMarginMM());
        $pdf->setLeftMargin($label->getLeftMarginMM());
        $pdf->setRightMargin($label->getRightMarginMM());

        $pdf->setAutoPageBreak(false);

        $pdf->setFont('helvetica', '', 8);

        return $pdf;
    }
}