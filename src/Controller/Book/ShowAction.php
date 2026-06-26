<?php

namespace App\Controller\Book;

use App\BookCopy\BookCopyCreateRequest;
use App\BookCopy\BookCopyCreator;
use App\Checkout\CheckoutManager;
use App\Entity\Book;
use App\Entity\BookCopy;
use App\Form\BookCopyCreateRequestType;
use App\Repository\BookCopyRepositoryInterface;
use App\Repository\PaginationQuery;
use App\Security\Voter\BookVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class ShowAction extends AbstractController {

    #[Route('/book/{uuid}', name: 'show_book')]
    public function show(
        #[MapEntity(mapping: ['uuid' => 'uuid'])] Book $book,
        Request $request,
        BookCopyCreator $creator,
        BookCopyRepositoryInterface $copyRepository,
        CheckoutManager $checkoutManager,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $limit = 25
    ): Response {
        $this->denyAccessUnlessGranted(BookVoter::SHOW, $book);

        $createRequest = new BookCopyCreateRequest();
        $createRequest->book = $book;
        $createForm = $this->createForm(BookCopyCreateRequestType::class, $createRequest);
        $createForm->handleRequest($request);

        if($createForm->isSubmitted() && $createForm->isValid()) {
            $copies = $creator->createCopies($createRequest);

            $this->addFlash('success', 'books.copies.add.success');
            return $this->redirectToRoute('download_pdf_labels', [
                'copies' => implode(',', array_map(fn(BookCopy $copy) => $copy->getId(), $copies)),
            ]);
        }

        $copies = $copyRepository->findByBookPaginated($book, new PaginationQuery(page: $page, limit: $limit));

        return $this->render('books/show.html.twig', [
            'book' => $book,
            'copies' => $copies,
            'form' => $createForm->createView(),
            'manager' => $checkoutManager
        ]);
    }
}