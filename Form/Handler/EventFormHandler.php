<?php

/*
 * This file is part of the Blackengine package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Black\Bundle\EventBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Black\Bundle\EventBundle\Model\EventInterface;

class EventFormHandler
{
    protected $request;
    protected $form;
    protected $session;

    public function __construct(FormInterface $form, Request $request, SessionInterface $session)
    {
        $this->form     = $form;
        $this->request  = $request;
        $this->session  = $session;
    }

    public function process(EventInterface $event)
    {
        $this->form->setData($event);

        if ('POST' === $this->request->getMethod()) {
            $this->form->getData()->cleanSubEvents();
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                
                if ($this->form->getData()->getSubEvents() !== null) {
                    foreach ($this->form->getData()->getSubEvents() as $sub) {
                        $this->form->getData()->addSubEvent($sub);
                    }
                }
                $this->setFlash('success', 'success.event.admin.event.edit');

                return true;
            } else {
                $this->setFlash('error', 'error.event.admin.event.edit.not.valid');
            }
        }
    }

    public function getForm()
    {
        return $this->form;
    }

    protected function setFlash($name, $msg)
    {
        return $this->session->getFlashBag()->add($name, $msg);
    }
}
