<?php
/**
 * Created by PhpStorm.
 * User: atairov
 * Date: 3/8/16
 * Time: 11:54 PM
 */

namespace Yoda\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Yoda\UserBundle\Entity\User;


class RegisterFormType extends AbstractType
{
    const NAME = 'user_register';

    public function getName()
    {
        return self::NAME;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text')
            ->add('email', 'text', [
                'label' => 'Email Address',
                'attr' => ['class' => 'C-3PO']
            ])
            ->add('plainPassword', 'repeated', [
                'type' => 'password'
            ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
       ]);
    }


}