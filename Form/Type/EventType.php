<?php

/*
 * This file is part of the Blackengine package.
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
use Black\Bundle\EngineBundle\Form\Type\PostalAddressType;
use Black\Bundle\EngineBundle\Document\Person;

class EventType extends AbstractType
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var
     */
    protected $postalType;

    /**
     * @var
     */
    protected $personDocument;
    
    /**
     * @param string $class The Person class name
     */
    public function __construct($class, PostalAddressType $postal, Person $person)
    {
        $this->class = $class;
        $this->postalType   = $postal;
        $this->personDocument   = $person;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label'         => 'event.admin.event.name.text',
            ))
            ->add('description', 'textarea', array(
                'label'         => 'event.admin.event.description.text',
                'required'      => false
            ))
            ->add('startDate', 'date', array(
                'required'      => true,
                'label'         => 'event.admin.event.startDate.text',
                'empty_value'   => array(
                    'year' => 'event.admin.event.startDate.choice.year',
                    'month' => 'event.admin.event.startDate.choice.month', 
                    'day' => 'event.admin.event.startDate.choice.day')
            ))
            ->add('endDate', 'date', array(
                'required'      => false,
                'label'         => 'event.admin.event.endDate.text',
                'empty_value'   =>  array(
                    'year' => 'event.admin.event.endDate.choice.year',
                    'month' => 'event.admin.event.endDate.choice.month', 
                    'day' => 'event.admin.event.endDate.choice.day')
            ))
            ->add('duration', 'text', array(
                'label'         => 'event.admin.event.duration.text',
                'required'      => false
            ))
            ->add('url', 'url', array(
                'label'         => 'event.admin.event.url.text',
                'required'      => false
            ))
            ->add('location', $this->postalType, array(
                'label'                 => 'event.admin.event.location.text',
                'cascade_validation'    => true,
                'required'              => false
            ))
            ->add('subEvents', 'collection', array(
                'type'          => new SubEventType($this->class, $this->postalType, $this->personDocument),
                'label'         => 'event.admin.event.subEvent.text',
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
                'label'         => 'event.admin.event.superEvent.text',
                'required'      => false,
                'empty_value'   => 'event.admin.event.superEvent.empty'
            ))
            ->add('attendees', 'document', array(
                'class'         => get_class($this->personDocument),
                'property'      => 'name',
                'multiple'      => true,
                'by_reference'  => false,
                'label'         => 'event.admin.event.attendees.text',
                'required'      => false
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class'    => $this->class,
                'intention'     => 'event_form'
            ));
    }

    public function getName()
    {
        return 'black_event_event';
    }
}