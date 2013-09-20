<?php

/*
 * This file is part of the Black package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Black\Bundle\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class AdminInvitationType
 *
 * @package Black\Bundle\EventBundle\Form\Type
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class AdminInvitationType extends AbstractType
{

    /**
     * @var string
     */
    protected $class;

    /**
     * @param $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('attendees', 'black_person_double_box_person', array(
                    'label'         => 'event.admin.event.attendees.text',
                    'multiple'      => true,
                    'by_reference'  => false,
                    'required'      => false
                )
            );
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'            => $this->class,
                'intention'             => 'invitation_form',
                'translation_domain'    => 'form'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'black_event_invitation';
    }
}
