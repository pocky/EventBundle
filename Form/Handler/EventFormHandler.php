<?php

/*
 * This file is part of the Black package.
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

/**
 * Class EventFormHandler
 *
 * @package Black\Bundle\EventBundle\Form\Handler
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class EventFormHandler
{
    protected $request;
    protected $form;
    protected $session;

    /**
     * @param FormInterface    $form
     * @param Request          $request
     * @param SessionInterface $session
     */
    public function __construct(FormInterface $form, Request $request, SessionInterface $session)
    {
        $this->form     = $form;
        $this->request  = $request;
        $this->session  = $session;
    }

    /**
     * @param EventInterface $event
     *
     * @return bool
     */
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

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param $name
     * @param $msg
     *
     * @return mixed
     */
    protected function setFlash($name, $msg)
    {
        return $this->session->getFlashBag()->add($name, $msg);
    }
}
