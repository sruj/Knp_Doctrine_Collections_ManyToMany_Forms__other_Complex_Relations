<?php

namespace AppBundle\Form;

use AppBundle\Entity\GenusScientist;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenusScientistEmbeddedForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(
                FormEvents::POST_SET_DATA,
                array($this, 'onPostSetData')
            )
            ->add('yearsStudied');
    }

    public function onPostSetData(FormEvent $event)
    {
        if ($event->getData() && $event->getData()->getId()) {
            $form = $event->getForm();
            $form->add('user', EntityType::class, [
                'placeholder' => 'Choose',
                'class' => User::class,
                'disabled' => true,
                'choice_label' => 'email',
                'query_builder' => function (UserRepository $repo) {
                    return $repo->createIsScientistQueryBuilder();
                }
            ]);
        } else {
            $form = $event->getForm();
            $form->add('user', EntityType::class, [
                'placeholder' => 'Choose',
                'class' => User::class,
                'choice_label' => 'email',
                'query_builder' => function (UserRepository $repo) {
                    return $repo->createIsScientistQueryBuilder();
                }
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => GenusScientist::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_genus_scientist_embedded_form';
    }
}
