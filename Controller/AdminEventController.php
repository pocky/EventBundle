<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
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
use Black\Bundle\EngineBundle\Model\PersonInterface;
use Black\Bundle\EventBundle\Model\EventInterface;

/**
 * Controller managing the event profile
 *
 * @Route("/admin/event")
 */
class AdminEventController extends Controller
{
    /**
     * Show lists of Events
     *
     * @Method("GET")
     * @Route("/index.html", name="admin_events")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     * 
     * @return Template
     */
    public function indexAction()
    {
        $csrf       = $this->container->get('form.csrf_provider');

        $keys = array(
            'id',
            'event.admin.event.name.text'
        );

        return array(
            'keys'      => $keys,
            'csrf'      => $csrf
        );
    }

    /**
     * Show lists of Events
     *
     * @Method("GET")
     * @Route("/list.json", name="admin_events_json")
     * @Secure(roles="ROLE_ADMIN")
     * 
     * @return Response
     */
    public function ajaxListAction()
    {
        $manager       = $this->getManager();
        $repository    = $manager->getRepository();
        $rawDocuments  = $repository->findAll();

        $documents = array('aaData' => array());
        foreach ($rawDocuments as $document) {
            $documents['aaData'][] = array(
                $document->getId(),
                $document->getName(),
                null
            );
        }

        return new Response(json_encode($documents));
    }

    /**
     * Show lists of Events
     * 
     * @param integer $id
     * 
     * @Method("GET")
     * @Route("/{id}/show.html", name="admin_event_show")
     * @Secure(roles="ROLE_USER")
     * @Template()
     * 
     * @return Temlate
     */
    public function showAction($id)
    {
        $documentManager = $this->getManager();
        $repository = $documentManager->getRepository();

        $document   = $repository->findOneById($id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Person document.');
        }

        $csrf   = $this->container->get('form.csrf_provider');

        $attendees = array();

        foreach ($document->getAttendees() as $attendee) {
            if ($attendee->getAddress()->first() != null) {
                $country = $attendee->getAddress()->first()->getAddressCountryLocale($this->getRequest()->getLocale());
            } else {
                $country = false;
            }

            $attendees[] = array(
                'id'                                                => $attendee->getId(),
                'engine.admin.person.name.given.text'               => $attendee->getGivenName(),
                'engine.admin.person.name.family.text'              => $attendee->getFamilyName(),
                'engine.admin.person.email.text'                    => $attendee->getEmail(),
                'engine.admin.postalAddress.address.country.text'   => $country
            );
        }

        return array(
            'document'  => $document,
            'attendees' => $attendees,
            'csrf'      => $csrf
        );
    }

    /**
     * Displays a form to create a new Event document.
     *
     * @Method({"GET", "POST"})
     * @Route("/new", name="admin_event_new")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     * 
     * @return Template
     */
    public function newAction()
    {
        $documentManager    = $this->getManager();
        $document           = $documentManager->createInstance();

        $formHandler    = $this->get('black_event.event.form.handler');
        $process        = $formHandler->process($document);

        if ($process) {
            $documentManager->persist($document);
            $documentManager->flush();

            return $this->redirect($this->generateUrl('admin_event_edit', array('id' => $document->getId())));
        }

        return array(
            'document'  => $document,
            'form'      => $formHandler->getForm()->createView()
        );
    }

    /**
     * Displays a form to edit an existing Event document.
     *
     * @param string $id The document ID
     *
     * @Method({"GET", "POST"})
     * @Route("/{id}/edit", name="admin_event_edit")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        $documentManager = $this->getManager();
        $repository = $documentManager->getRepository();

        $document = $repository->findOneById($id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Event document.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $formHandler    = $this->get('black_event.event.form.handler');
        $process        = $formHandler->process($document);

        if ($process) {
            $documentManager->flush();

            return $this->redirect($this->generateUrl('admin_event_edit', array('id' => $id)));
        }

        return array(
            'document'      => $document,
            'form'          => $formHandler->getForm()->createView(),
            'delete_form'   => $deleteForm->createView()
        );
    }

    /**
     * Deletes an Event document.
     * 
     * @param string $id
     * @param string $token
     * 
     * @Method({"POST", "GET"})
     * @Route("/{id}/delete/{token}", name="admin_event_delete")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function deleteAction($id, $token = null)
    {
        $form       = $this->createDeleteForm($id);
        $request    = $this->getRequest();

        $form->bind($request);

        if (null !== $token) {
            $token = $this->get('form.csrf_provider')->isCsrfTokenValid('delete', $token);
        }

        if ($form->isValid() || true === $token) {

            $dm         = $this->getManager();
            $repository = $dm->getRepository();
            $document   = $repository->findOneById($id);

            if (!$document) {
                throw $this->createNotFoundException('Unable to find Event document.');
            }

            $dm->remove($document);
            $dm->flush();

            $this->get('session')->getFlashBag()->add('success', 'success.event.admin.event.delete');

        } else {
            $this->get('session')->getFlashBag()->add('error', 'error.event.admin.event.delete.not.valid');
        }

        return $this->redirect($this->generateUrl('admin_events'));
    }

    /**
     * Delete an Attendee from document
     *7
     * @param PersonInterface $person
     * @param EventInterface  $event
     * @param null            $token
     *
     * @ParamConverter("person", class="ActivCompany\Bundle\ERPBundle\Document\Person")
     * @ParamConverter("event", class="ActivCompany\Bundle\ERPBundle\Document\Event")
     * @Method({"GET"})
     * @Route("/{event}/delete/attendee/{person}", name="admin_event_attendee_delete")
     * @Secure(roles="ROLE_USER")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function deleteAttendeeAction(PersonInterface $person, EventInterface $event, $token = null)
    {
        $request    = $this->getRequest();

        if (null === $token) {
            $token = $this->get('form.csrf_provider')->isCsrfTokenValid(
                'delete' . $event->getId(),
                $request->query->get('token')
            );
        }

        if (true === $token) {
            $dm = $this->getManager();
            $event->removeAttendee($person);
            $dm->flush();

            $this->get('session')->getFlashBag()->add('success', 'success.event.admin.attendee.delete');

        } else {
            $this->get('session')->getFlashBag()->add('error', 'error.event.admin.attendee.delete.not.valid');
        }

        return $this->redirect($this->generateUrl('admin_event_show', array('id' => $event->getId())));
    }

    /**
     * Deletes a Event document.
     *
     * @Method({"POST"})
     * @Route("/batch", name="admin_event_batch")
     *
     * @return array
     *
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException If method does not exist
     */
    public function batchAction()
    {
        $request    = $this->getRequest();
        $token      = $this->get('form.csrf_provider')->isCsrfTokenValid('batch', $request->get('token'));

        if (!$ids = $request->get('ids')) {
            $this->get('session')->getFlashBag()->add('error', 'error.event.admin.batch.no.item');

            return $this->redirect($this->generateUrl('admin_events'));
        }

        if (!$action = $request->get('batchAction')) {
            $this->get('session')->getFlashBag()->add('error', 'error.event.admin.batch.no.action');

            return $this->redirect($this->generateUrl('admin_events'));
        }

        if (!method_exists($this, $method = $action . 'Action')) {
            throw new Exception\InvalidArgumentException(
                sprintf('You must create a "%s" method for action "%s"', $method, $action)
            );
        }

        if (false === $token) {
            $this->get('session')->getFlashBag()->add('error', 'error.event.admin.batch.csrf');

            return $this->redirect($this->generateUrl('admin_events'));
        }

        foreach ($ids as $id) {
            $this->$method($id, $token);
        }

        return $this->redirect($this->generateUrl('admin_events'));

    }

    private function createDeleteAttendeeForm($user, $event)
    {
        $form = $this->createFormBuilder(array('user' => $user, 'event' => $event))
            ->add('user', 'hidden')
            ->add('event', 'hidden')
            ->getForm();

        return $form;
    }

    private function createDeleteForm($id)
    {
        $form = $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();

        return $form;
    }

    protected function getManager()
    {
        return $this->get('black_event.manager.event');
    }

    protected function getPersonManager()
    {
        return $this->get('black_engine.manager.person');
    }
}
