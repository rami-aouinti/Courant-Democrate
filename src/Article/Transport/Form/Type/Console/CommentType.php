<?php

declare(strict_types=1);

namespace App\Article\Transport\Form\Type\Console;

use App\Article\Application\DTO\Comment\Comment as CommentDTO;
use App\General\Transport\Form\Type\Interfaces\FormTypeLabelInterface;
use App\General\Transport\Form\Type\Traits\AddBasicFieldToForm;
use App\User\Application\Resource\UserGroupResource;
use App\User\Transport\Form\DataTransformer\UserGroupTransformer;
use App\User\Transport\Form\Type\Traits\UserGroupChoices;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Throwable;

/**
 * Class CommentType
 *
 * @package App\Article
 */
class CommentType extends AbstractType
{
    use AddBasicFieldToForm;
    use UserGroupChoices;

    /**
     * Base form fields
     *
     * @var array<int, array<int, mixed>>
     */
    private static array $formFields = [
        [
            'content',
            Type\TextType::class,
            [
                FormTypeLabelInterface::LABEL => 'content',
                FormTypeLabelInterface::REQUIRED => true,
                FormTypeLabelInterface::EMPTY_DATA => '',
            ],
        ],
    ];

    public function __construct(
        private readonly UserGroupResource $userGroupResource,
        private readonly UserGroupTransformer $userGroupTransformer,
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

        $builder
            ->add(
                'userGroups',
                Type\ChoiceType::class,
                [
                    'choices' => $this->getUserGroupChoices(),
                    'multiple' => true,
                    'required' => true,
                    'empty_data' => '',
                ],
            );

        $builder->get('userGroups')->addModelTransformer($this->userGroupTransformer);
    }

    /**
     * Configures the options for this type.
     *
     * @throws AccessException
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => CommentDTO::class,
        ]);
    }
}
