<?php

namespace App\Form;

use App\Entity\BookCopy;
use App\Entity\LabelTemplate;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class DownloadLabelsRequestType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('copies', EntityType::class, [
                'label' => 'label.copies',
                'class' => BookCopy::class,
                'multiple' => true,
                'help' => 'select.copies',
                'choice_label' => fn(BookCopy $copy) => sprintf('[%s] %s', str_pad($copy->getBarcodeId(), 5, '0'), !empty($copy->getBook()->getBarcodeTitle()) ? $copy->getBook()->getBarcodeTitle() : $copy->getBook()->getTitle()),
                'query_builder' => function(EntityRepository $repository): QueryBuilder {
                    return $repository->createQueryBuilder('c')
                        ->where('c.canCheckout = true');
                },
                'attr' => [
                    'data-choice' => 'true'
                ]
            ])
            ->add('template', EntityType::class, [
                'label' => 'label.template',
                'class' => LabelTemplate::class,
                'choice_label' => fn(LabelTemplate $template) => $template->getName(),
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->createQueryBuilder('l')
                        ->addOrderBy('l.name', 'asc');
                },
                'attr' => [
                    'data-choice' => 'true'
                ]
            ])
            ->add('offset', IntegerType::class, [
                'label' => 'labels.download.offset.label',
                'help' => 'labels.download.offset.help',
            ]);
    }
}