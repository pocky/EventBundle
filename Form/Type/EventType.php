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
 * Class EventType
 *
 * @package Black\Bundle\EventBundle\Form\Type
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class EventType extends AbstractType
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
            ->add('name', 'text', array(
                   'label'  => 'event.admin.event.name.text'
                )
            )
            ->add('description', 'textarea', array(
                    'label'     => 'event.admin.event.description.text',
                    'required'  => false
                )
            )
            ->add('visibility', 'black_event_choice_list_visibility', array(
                    'label'         => 'event.admin.event.visibility.text',
                    'empty_value'   => 'event.admin.event.visibility.empty',
                    'required'      => true
                )
            )
            ->add('status', 'black_event_choice_list_status', array(
                    'label'         => 'event.admin.event.status.text',
                    'empty_value'   => 'event.admin.event.status.empty',
                    'required'      => true
                )
            )
            ->add('startDate', 'datetime', array(
                    'required'      => true,
                    'label'         => 'event.admin.event.startDate.text',
                    'empty_value'   => array(
                        'year'  => 'event.admin.event.startDate.choice.year',
                        'month' => 'event.admin.event.startDate.choice.month',
                        'day'   => 'event.admin.event.startDate.choice.day'
                    )
                )
            )
            ->add('endDate', 'datetime', array(
                    'required'      => false,
                    'label'         => 'event.admin.event.endDate.text',
                    'empty_value'   => array(
                        'year'  => 'event.admin.event.endDate.choice.year',
                        'month' => 'event.admin.event.endDate.choice.month',
                        'day'   => 'event.admin.event.endDate.choice.day')
                )
            )

            ->add('url', 'url', array(
                    'label'     => 'event.admin.event.url.text',
                    'required'  => false
                )
            )
            ->add('location', 'black_event_postaladdress', array(
                    'label'                 => 'event.admin.event.location.text',
                    'cascade_validation'    => true,
                    'required'              => false
                )
            )
            ->add('subEvents', 'collection', array(
                    'type'          => 'black_event_sub_event',
                    'label'         => 'event.admin.event.subEvent.text',
                    'allow_add'     => true,
                    'allow_delete'  => true,
                    'required'      => false,
                    'attr'          => array(
                        'class' => 'event-collection',
                        'add'   => 'add-another-event'
                    ),
                )
            )
            ->add('superEvent', 'black_event_choice_list_event', array(
                    'label'         => 'event.admin.event.superEvent.text',
                    'required'      => false,
                    'empty_value'   => 'event.admin.event.superEvent.empty'
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
                'intention'             => 'event_form',
                'translation_domain'    => 'form'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'black_event_event';
    }
}
