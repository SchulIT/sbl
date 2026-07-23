<?php

namespace App\Form\Autocomplete;

use App\Entity\Book;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;
use Vich\UploaderBundle\Twig\Extension\UploaderExtensionRuntime;

#[AsEntityAutocompleteField]
class BookAutocompleteField extends AbstractType {

    public function __construct(
        private readonly UploaderExtensionRuntime $extension
    ) { }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'class' => Book::class,
            'placeholder' => 'select.book',
            'searchable_fields' => [
                'title', 'subtitle', 'isbn'
            ],
            'query_builder' => function(EntityRepository $repository) {
                return $repository->createQueryBuilder('b')
                    ->orderBy('b.title', 'ASC')
                    ->addOrderBy('b.subtitle', 'ASC');
            },
            'choice_label' => 'title',
            'additional_attributes' => function(Book $book) {
                return [
                    'label' => $book->getTitle(),
                    'sublabel' => $book->getSubtitle(),
                    'extra' => $book->getIsbn(),
                    'image' => $this->extension->asset($book, 'cover')
                ];
            }
        ]);
    }

    public function getParent(): string {
        return BaseEntityAutocompleteType::class;
    }
}
