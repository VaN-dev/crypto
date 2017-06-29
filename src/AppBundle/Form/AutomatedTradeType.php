<?php

namespace AppBundle\Form;

use AppBundle\Entity\Currency;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AutomatedTrade
 * @package AppBundle\Form
 */
class AutomatedTradeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sourceCurrency', EntityType::class, [
                'class' => 'AppBundle\Entity\Currency',
                'choice_label' => 'symbol',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.symbol');
                },
            ])
            ->add('targetCurrency', EntityType::class, [
                'class' => 'AppBundle\Entity\Currency',
                'choice_label' => 'symbol',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.symbol');
                },
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_bundle_automated_trade_type';
    }
}
