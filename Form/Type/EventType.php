<?php

/*
 * This file is part of the Blackengine package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Blackroom\Bundle\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Blackroom\Bundle\EngineBundle\Form\Type\PostalAddressType;

class EventType extends AbstractType
{
    private $class;

    /**
     * @param string $class The Event class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label'         => 'Event name',
            ))
            ->add('description', 'textarea', array(
                'required'      => false
            ))
            ->add('startDate', 'date', array(
                'label'         => 'Start date',
                'empty_value'   => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day')
            ))
            ->add('endDate', 'date', array(
                'required'      => false,
                'label'         => 'End date',
                'empty_value'   => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day')
            ))
            ->add('duration', 'text', array(
                'required'      => false
            ))
            ->add('url', 'url', array(
                'required'      => false
            ))
            ->add('location', new PostalAddressType(), array(
                'cascade_validation'    => true,
                'required'              => false
            ))
            ->add('subEvents', 'collection', array(
                'type'          => new SubEventType('Blackroom\Bundle\EventBundle\Document\Event\Event'),
                'label'         => 'Sub Events',
                'allow_add'     => true,
                'allow_delete'  => true,
                'required'      => false,
                'attr'          => array(
                    'class' => 'event-collection',
                    'add'   => 'add-another-event'
                ),
            ))
            ->add('superEvent', 'document', array(
                'class'         => $this->class,
                'property'      => 'name',
                'label'         => 'Super Event',
                'required'      => false,
                'empty_value'   => 'Choose an Event'
            ))
            ->add('attendees', 'document', array(
                'class'         => 'Blackroom\Bundle\ERPBundle\Document\Person',
                'property'      => 'name',
                'multiple'      => true,
                'by_reference'  => false,
                'label'         => 'Attendees',
                'required'      => false
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => $this->class,
            ));
    }

    public function getName()
    {
        return 'blackroom_event_event';
    }
}