<?php

namespace App\Quiz\Transport\Form;

use App\Quiz\Domain\Entity\Answer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AnswerType extends AbstractType
{
    private TranslatorInterface $translator;
    private ParameterBagInterface $param;
    private TokenStorageInterface $tokenStorage;

    public function __construct(TranslatorInterface $translator, ParameterBagInterface $param, TokenStorageInterface $tokenStorage)
    {
        $this->translator = $translator;
        $this->param = $param;
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        switch ($options['form_type']) {
            case 'student_questioning':
                $builder->add('workout_correct_given', CheckboxType::class, array(
                    'label' => false,
                    'text_property' => 'text',
                    'required' => false,
                ));
                break;
            case 'student_marking':
                $builder->add('workout_correct_given', CheckboxType::class, array(
                    'label' => false,
                    'text_property' => 'text',
                    'correct_given_property' => 'workout_correct_given',
                    'correct_property' => 'correct',
                    'required' => false,
                ));
                break;
            case 'admin':
            case 'teacher':
                $builder->add('text', null, array(
                    'label' => false,
                ));
                $builder->add('correct');
                break;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Answer::class,
            'form_type' => 'student_questioning',
        ]);
    }
}
