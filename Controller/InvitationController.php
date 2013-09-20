<?php

/*
 * This file is part of the Black package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Black\Bundle\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Black\Bundle\PersonBundle\Model\PersonInterface;
use Black\Bundle\EventBundle\Model\EventInterface;

/**
 * Class InvitationController
 *
 * @Route("/invitation")
 *
 * @package Black\Bundle\EventBundle\Controller
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class InvitationController extends Controller
{
    /**
     * @param $event
     *
     * @Method({"GET", "POST"})
     * @Route("/{event}/subscribe.html", name="invitation_subscribe")
     * @Template()
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction($event)
    {
        $documentManager    = $this->getInvitationManager();
        $eventManager       = $this->getEventManager();

        $eventDocument = $eventManager->findEventBySlug($event);
        if (!$eventDocument) {
            $this->createNotFoundException('Unable to find this document');
        }

        $document   = $documentManager->createInstance();

        $formHandler    = $this->get('black_event.invitation.form.handler');
        $process        = $formHandler->process($document);

        if ($process) {

            $document->setPerson($this->getUser()->getPerson()->getId());
            $document->setEvent($eventDocument);
            $documentManager->persist($document);
            $documentManager->flush();

            $this->get('session')->getFlashBag()->add('success', 'www.event.invitation.index.success');
            return $this->redirect($this->generateUrl('event_show', array('slug' => $event)));
        }

        return array(
            'document'  => $document,
            'event'     => $event,
            'form'      => $formHandler->getForm()->createView()
        );
    }

    /**
     * @param $event
     *
     * @Method({"GET", "POST"})
     * @Route("/edit/{event}/subscribe.html", name="invitation_edit")
     * @Template()
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction($event)
    {
        $documentManager    = $this->getInvitationManager();
        $eventManager       = $this->getEventManager();

        $eventDocument = $eventManager->findEventBySlug($event);
        if (!$eventDocument) {
            $this->createNotFoundException('Unable to find this document');
        }

        $document       = $documentManager->findEventForPerson($eventDocument->getId(), $this->getUser()->getPerson()->getId());

        $formHandler    = $this->get('black_event.invitation.form.handler');
        $process        = $formHandler->process($document);

        if ($process) {

            $documentManager->flush();

            $this->get('session')->getFlashBag()->add('success', 'www.event.invitation.edit.success');
            return $this->redirect($this->generateUrl('event_show', array('slug' => $event)));
        }

        return array(
            'document'  => $document,
            'event'     => $event,
            'person'    => $this->getUser()->getPerson()->getSlug(),
            'form'      => $formHandler->getForm()->createView()
        );
    }

    /**
     * @return object
     */
    protected function getEventManager()
    {
        return $this->get('black_event.manager.event');
    }

    /**
     * @return object
     */
    protected function getPersonManager()
    {
        return $this->get('black_person.manager.person');
    }

    /**
     * @return object
     */
    protected function getInvitationManager()
    {
        return $this->get('black_event.manager.invitation');
    }
}
