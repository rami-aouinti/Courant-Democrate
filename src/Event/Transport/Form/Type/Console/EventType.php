<?php

declare(strict_types=1);

namespace App\Event\Transport\Form\Type\Console;


use App\General\Transport\Form\Type\Interfaces\FormTypeLabelInterface;
use App\General\Transport\Form\Type\Traits\AddBasicFieldToForm;
use App\Event\Application\DTO\Event\Event as EventDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Throwable;

use function array_map;

/**
 * Class EventType
 *
 * @package App\Event
 */
class EventType extends AbstractType
{
    use AddBasicFieldToForm;

    /**
     * Base form fields
     *
     * @var array<int, array<int, mixed>>
     */
    private static array $formFields = [
        [
            'title',
            Type\TextType::class,
            [
                FormTypeLabelInterface::LABEL => 'Title',
                FormTypeLabelInterface::REQUIRED => true,
                FormTypeLabelInterface::EMPTY_DATA => '',
            ],
        ],
        [
            'description',
            Type\TextType::class,
            [
                FormTypeLabelInterface::LABEL => 'Description',
                FormTypeLabelInterface::REQUIRED => true,
                FormTypeLabelInterface::EMPTY_DATA => '',
            ],
        ],
        [
            'allDays',
            Type\CheckboxType::class,
            [
                FormTypeLabelInterface::LABEL => 'All Days',
                FormTypeLabelInterface::REQUIRED => true,
            ],
        ],
        [
            'className',
            Type\TextType::class,
            [
                FormTypeLabelInterface::LABEL => 'Class Name',
                FormTypeLabelInterface::REQUIRED => true,
                FormTypeLabelInterface::EMPTY_DATA => '',
            ],
        ],
        [
            'backgroundColor',
            Type\TextType::class,
            [
                FormTypeLabelInterface::LABEL => 'Background Color',
                FormTypeLabelInterface::REQUIRED => true,
                FormTypeLabelInterface::EMPTY_DATA => '',
            ],
        ],
        [
            'borderColor',
            Type\TextType::class,
            [
                FormTypeLabelInterface::LABEL => 'Border Color',
                FormTypeLabelInterface::REQUIRED => true,
                FormTypeLabelInterface::EMPTY_DATA => '',
            ],
        ],
        [
            'textColor',
            Type\TextType::class,
            [
                FormTypeLabelInterface::LABEL => 'Text Color',
                FormTypeLabelInterface::REQUIRED => true,
                FormTypeLabelInterface::EMPTY_DATA => '',
            ],
        ],
        [
            'start',
            Type\DateTimeType::class,
            [
                FormTypeLabelInterface::LABEL => 'Start',
                FormTypeLabelInterface::REQUIRED => true,
                FormTypeLabelInterface::EMPTY_DATA => '',
            ],
        ],
        [
            'end',
            Type\DateTimeType::class,
            [
                FormTypeLabelInterface::LABEL => 'End',
                FormTypeLabelInterface::REQUIRED => true,
                FormTypeLabelInterface::EMPTY_DATA => '',
            ],
        ],
    ];

    public function __construct(
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * @throws Throwable
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this->addBasicFieldToForm($builder, self::$formFields);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     *
     * @throws AccessException
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => EventDto::class,
        ]);
    }
}
