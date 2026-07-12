<?php

namespace App\Settings\Type;

use Doctrine\Persistence\ManagerRegistry;
use Jbtronics\SettingsBundle\Metadata\ParameterMetadata;
use Jbtronics\SettingsBundle\ParameterTypes\ParameterTypeInterface;
use Jbtronics\SettingsBundle\ParameterTypes\ParameterTypeWithFormDefaultsInterface;
use Override;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

readonly class EntityType implements ParameterTypeInterface, ParameterTypeWithFormDefaultsInterface {

    public function __construct(private ManagerRegistry $registry) {

    }

    #[Override]
    public function convertPHPToNormalized(mixed $value, ParameterMetadata $parameterMetadata): int|string|float|bool|array|null {
        if($value === null) {
            return null;
        }

        $idProperty = $parameterMetadata->getOptions()['id'] ?? 'id';
        $propertyReader = PropertyAccess::createPropertyAccessor();

        return $propertyReader->getValue($value, $idProperty);
    }

    #[Override]
    public function convertNormalizedToPHP(float|int|bool|array|string|null $value, ParameterMetadata $parameterMetadata): mixed {
        if($value === null) {
            return null;
        }

        $class = $parameterMetadata->getOptions()['class'];

        $manager = $this->registry->getManagerForClass($class);
        $entity = $manager->find($class, $value);

        return $entity;
    }

    #[Override]
    public function getFormType(ParameterMetadata $parameterMetadata): string {
        return \Symfony\Bridge\Doctrine\Form\Type\EntityType::class;
    }

    #[Override]
    public function configureFormOptions(OptionsResolver $resolver, ParameterMetadata $parameterMetadata): void {
        $resolver->setDefault('class', $parameterMetadata->getOptions()['class']);
    }
}
