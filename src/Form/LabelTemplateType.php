<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LabelTemplateType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('name', TextType::class, [
                'label' => 'label.name'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'label.description',
                'required' => false
            ])
            ->add('rows', IntegerType::class, [
                'label' => 'labels.rows.label',
                'help' => 'labels.rows.help'
            ])
            ->add('columns',  IntegerType::class, [
                'label' => 'labels.columns.label',
                'help' => 'labels.columns.help'
            ])
            ->add('topMarginMM',  NumberType::class, [
                'label' => 'labels.margin.top.label',
                'help' => 'labels.margin.top.help'
            ])
            ->add('bottomMarginMM',  NumberType::class, [
                'label' => 'labels.margin.bottom.label',
                'help' => 'labels.margin.bottom.help'
            ])
            ->add('leftMarginMM',  NumberType::class, [
                'label' => 'labels.margin.left.label',
                'help' => 'labels.margin.left.help'
            ])
            ->add('rightMarginMM',  NumberType::class, [
                'label' => 'labels.margin.right.label',
                'help' => 'labels.margin.right.help'
            ])
            ->add('cellWidthMM', NumberType::class, [
                'label' => 'labels.cell.width.label',
                'help' => 'labels.cell.width.help'
            ])
            ->add('cellHeightMM',   NumberType::class, [
                'label' => 'labels.cell.height.label',
                'help' => 'labels.cell.height.help'
            ])
            ->add('cellPaddingMM', NumberType::class, [
                'label' => 'labels.cell.padding.label',
                'help' => 'labels.cell.padding.help'
            ]);
    }
}