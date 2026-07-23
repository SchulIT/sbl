<?php

namespace App\Form;

use App\Entity\BookCopy;
use App\Entity\Borrower;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BulkCheckoutRequestType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('borrower', EntityType::class, [
                'label' => 'label.borrower',
                'class'=> Borrower::class,
                'placeholder' => 'select.borrower',
                'attr' => [
                    'data-choice' => 'true'
                ],
                'choice_label' => fn(Borrower $borrower) => sprintf('[%d] %s, %s', $borrower->getBarcodeId(), $borrower->getLastname(), $borrower->getFirstname())
            ])
            ->add('copies', EntityType::class, [
                'label' => 'label.copies',
                'class' => BookCopy::class,
                'multiple' => true,
                'help' => 'select.copies',
                'choice_label' => fn(BookCopy $copy) => sprintf('[%s] %s', str_pad($copy->getBarcodeId(), 5, '0'), !empty($copy->getBook()->getBarcodeTitle()) ? $copy->getBook()->getBarcodeTitle() : $copy->getBook()->getTitle()),
                'query_builder' => function(EntityRepository $repository): QueryBuilder {
                    return $repository->createQueryBuilder('c')
                        ->select(['c', 'b'])
                        ->leftJoin('c.book', 'b')
                        ->where('c.canCheckout = true');
                },
                'attr' => [
                    'data-choice' => 'true'
                ]
            ]);
    }
}