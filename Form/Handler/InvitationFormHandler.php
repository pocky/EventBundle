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

use Black\Bundle\EventBundle\Model\InvitationInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class InvitationFormHandler
 *
 * @package Black\Bundle\EventBundle\Form\Handler
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class InvitationFormHandler
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
    public function process(InvitationInterface $invitation)
    {
        $this->form->setData($invitation);

        if ('POST' === $this->request->getMethod()) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {

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
