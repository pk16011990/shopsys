<?php

namespace Shopsys\FrameworkBundle\Form\Admin\Customer;

use Shopsys\FrameworkBundle\Model\Customer\CustomerData;
use Shopsys\FrameworkBundle\Model\Customer\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerFormType extends AbstractType
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userData', UserFormType::class, [
                'scenario' => $options['scenario'],
                'domain_id' => $options['domain_id'],
                'user' => $options['user'] !== null ? $options['user'] : null,
                'render_form_row' => false,
            ])
            ->add('billingAddressData', BillingAddressFormType::class, [
                'domain_id' => $options['domain_id'],
            ])
            ->add('deliveryAddressData', DeliveryAddressFormType::class, [
                'domain_id' => $options['domain_id'],
            ])
            ->add('save', SubmitType::class);

        if ($options['scenario'] === self::SCENARIO_CREATE) {
            $builder->add('sendRegistrationMail', CheckboxType::class, [
                'required' => false,
                'label' => t('Send confirmation e-mail about registration to customer'),
                'attr' => [
                    'class' => 'js-checkbox-toggle',
                ],
            ]);
        }
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['scenario', 'domain_id'])
            ->setAllowedValues('scenario', [self::SCENARIO_CREATE, self::SCENARIO_EDIT])
            ->setAllowedTypes('domain_id', 'int')
            ->setDefaults([
                'data_class' => CustomerData::class,
                'attr' => ['novalidate' => 'novalidate'],
                'user' => null,
            ])
            ->setAllowedTypes('user', [User::class, 'null']);
    }
}
