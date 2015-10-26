<?php
/**
 * Created by mcfedr on 27/06/15 11:58
 */

namespace Ekreative\RedmineLoginBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text')
            ->add('password', 'password');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'login';
    }
}
