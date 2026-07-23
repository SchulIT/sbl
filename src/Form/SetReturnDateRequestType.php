<?php

namespace App\Form;

use App\Entity\Book;
use App\Form\Autocomplete\BookAutocompleteField;
use App\Repository\BorrowerRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class SetReturnDateRequestType extends AbstractType {

    public function __construct(
        private readonly BorrowerRepositoryInterface $borrowerRepository
    ) {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $choices = [ ];

        foreach($this->borrowerRepository->findGrades() as $grade) {
            $choices[$grade] = $grade;
        }

        $builder
            ->add('book', BookAutocompleteField::class, [
                'label' => 'return_date.book',
                'class' => Book::class,
                'choice_label' => fn(Book $book) => sprintf('%s [%s]', $book->getTitle(), $book->getSubtitle())
            ])
            ->add('returnDate', DateType::class, [
                'label' => 'return_date.return_date.label',
                'help' => 'return_date.return_date.help',
                'widget' => 'single_text',
            ])
            ->add('overrideExistingReturnDates', CheckboxType::class, [
                'label' => 'return_date.override_existing.label',
                'help' => 'return_date.override_existing.help',
                'required' => false
            ])
            ->add('grades', ChoiceType::class, [
                'label' => 'return_date.grades.label',
                'help' => 'return_date.grades.help',
                'required' => false,
                'choices' => $choices,
                'multiple' => true,
                'attr' => [
                    'data-select' => 'tom-select'
                ]
            ]);
    }
}