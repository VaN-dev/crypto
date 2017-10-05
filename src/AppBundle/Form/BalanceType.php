<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BalanceType
 * @package AppBundle\Form
 */
class BalanceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currency', EntityType::class, [
                'class' => 'AppBundle:Currency',
                'choice_label' => 'name',
            ])
            ->add('market', EntityType::class, [
                'class' => 'AppBundle:Market',
                'choice_label' => 'name',
            ])
            ->add('value')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => 'AppBundle\Entity\Balance',
            ])
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_bundle_balance_type';
    }
}
