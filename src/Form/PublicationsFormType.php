<?php

namespace App\Form;

use App\Entity\User;
use App\Helper\PostHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PublicationsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', EntityType::class, [
                'required' => false,
                'class' => User::class,
                'choice_label' => 'email',
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control collection-container',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => PostHelper::getStatuses(true),
            ])
            ->add('createdFrom', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('createdTo', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
            ])
        ;

        $builder->get('author')->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }
}
