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
     * @param string $class The Person class name
     */
    public function __construct($class, PostalAddressType $postal)
    {
        $this->class = $class;
        $this->postalType   = $postal;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label'         => 'event.admin.form.name',
            ))
            ->add('description', 'textarea', array(
                'label'         => 'event.admin.form.description',
                'required'      => false
            ))
            ->add('startDate', 'date', array(
                'label'         => 'event.admin.form.startDate',
                'empty_value'   => array('year' => 'admin.form.date.year', 'month' => 'admin.form.date.month', 'day' => 'admin.form.date.day')
            ))
            ->add('endDate', 'date', array(
                'required'      => false,
                'label'         => 'event.admin.form.endDate',
                'empty_value'   =>  array('year' => 'admin.form.date.year', 'month' => 'admin.form.date.month', 'day' => 'admin.form.date.day')
            ))
            ->add('duration', 'text', array(
                'label'         => 'event.admin.form.duration',
                'required'      => false
            ))
            ->add('url', 'url', array(
                'label'         => 'event.admin.form.url',
                'required'      => false
            ))
            ->add('location', $this->postalType, array(
                'label'                 => 'event.admin.form.location',
                'cascade_validation'    => true,
                'required'              => false
            ))
            ->add('subEvents', 'collection', array(
                'type'          => new SubEventType($this->class, $this->postalType),
                'label'         => 'event.admin.form.subEvent',
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
                'label'         => 'event.admin.form.superEvent.title',
                'required'      => false,
                'empty_value'   => 'event.admin.form.superEvent.empty'
            ))
            ->add('attendees', 'document', array(
                'class'         => $this->class,
                'property'      => 'name',
                'multiple'      => true,
                'by_reference'  => false,
                'label'         => 'event.admin.form.attendees',
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