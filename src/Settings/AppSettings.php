<?php

namespace App\Settings;

use App\Entity\LabelTemplate;
use App\Settings\Type\EntityType;
use Jbtronics\SettingsBundle\ParameterTypes\StringType;
use Jbtronics\SettingsBundle\Settings\Settings;
use Jbtronics\SettingsBundle\Settings\SettingsParameter;
use Jbtronics\SettingsBundle\Settings\SettingsTrait;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

#[Settings]
class AppSettings {
    use SettingsTrait;

    #[SettingsParameter(type: StringType::class, label: 'settings.app.custom_css.label', description: 'settings.app.custom_css.help', formType: TextareaType::class, formOptions: [ 'required' => false, 'attr' => ['rows'=> 30, 'class' => 'font-monospace']], nullable: true)]
    public ?string $customCss = null;

    #[SettingsParameter(type: EntityType::class, label: 'settings.labels.default.label', description: 'settings.labels.default.help', options: ['class' => LabelTemplate::class ], formType: \Symfony\Bridge\Doctrine\Form\Type\EntityType::class, nullable: true, cloneable: false)]
    public LabelTemplate|null $defaultLabelTemplate = null;
}