<?php

/*
 * This file is part of the Blackengine package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Blackroom\Bundle\EventBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Blackroom\Bundle\EventBundle\Model\Event\Event;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

class EventFormHandler
{
    protected $request;
    protected $form;
    protected $factory;
    protected $session;

    public function __construct(FormInterface $form, Request $request, SessionInterface $session, ManagerRegistry $manager)
    {
        $this->form     = $form;
        $this->request  = $request;
        $this->session  = $session;
        $this->manager  = $manager->getManager();
    }

    public function process(Event $event)
    {
        $this->form->setData($event);

        if ('POST' === $this->request->getMethod()) {

            $this->form->bind($this->request);

            if ($this->form->isValid()) {

                $this->setFlash('success', $event->getName() . ' was successfully updated!');

                return true;
            } else {
                $this->setFlash('failure', 'The form is not valid');
            }
        }
    }

    public function getForm()
    {
        return $this->form;
    }

    protected function setFlash($name, $msg)
    {
        return $this->session->setFlash($name, $msg);
    }
}