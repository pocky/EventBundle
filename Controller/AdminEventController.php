<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blackroom\Bundle\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use ActivCompany\Bundle\ERPBundle\Document\Event;

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
     */
    public function indexAction()
    {
        $repository = $this->get('doctrine_mongodb')->getManager()->getRepository('BlackroomEventBundle:Event\Event');
        $rawDocuments  = $repository->findAll();
        $csrf       = $this->container->get('form.csrf_provider');

        $documents = array();

        foreach ($rawDocuments as $document) {
            $documents[] = array(
                'id'        => $document->getId(),
                'Name'      => $document->getName()
            );
        }

        return array(
            'documents' => $documents,
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
     */
    public function newAction()
    {
        $document           = new Event();
        $documentManager    = $this->get('doctrine_mongodb')->getManager();

        $formHandler    = $this->get('blackroom_event.form.handler.event');
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
     * @Method({"GET", "POST"})
     * @Route("/{id}/edit", name="admin_event_edit")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        $documentManager = $this->get('doctrine_mongodb')->getManager();
        $repository = $documentManager->getRepository('BlackroomEventBundle:Event\Event');

        $document = $repository->findOneById($id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Event document.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $formHandler    = $this->get('blackroom_event.form.handler.event');
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
     * @Method({"POST", "GET"})
     * @Route("/{id}/delete", name="admin_event_delete")
     * @Secure(roles="ROLE_ADMIN")
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function deleteAction($id, $token = NULL)
    {
        $form       = $this->createDeleteForm($id);
        $request    = $this->getRequest();

        $form->bind($request);

        if (null === $token) {
            $token = $this->get('form.csrf_provider')->isCsrfTokenValid('delete' . $id, $request->query->get('token'));
        }

        if ($form->isValid() || true === $token) {

            $dm         = $this->get('doctrine_mongodb')->getManager();
            $repository = $dm->getRepository('BlackroomEventBundle:Event\Event');
            $document   = $repository->findOneById($id);

            if (!$document) {
                throw $this->createNotFoundException('Unable to find Event document.');
            }

            $dm->remove($document);
            $dm->flush();

            $this->get('session')->setFlash('success', 'This event was successfully deleted!');

        } else {
            $this->setFlash('failure', 'The form is not valid');
        }

        return $this->redirect($this->generateUrl('admin_events'));
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
            $this->get('session')->setFlash('failure', 'You must select at least one item');
            return $this->redirect($this->generateUrl('admin_events'));
        }

        if (!$action = $request->get('batchAction')) {
            $this->get('session')->setFlash('failure', 'You must select an action to execute on the selected items');
            return $this->redirect($this->generateUrl('admin_events'));
        }

        if (!method_exists($this, $method = $action . 'Action')) {
            throw new Exception\InvalidArgumentException(
                sprintf('You must create a "%s" method for action "%s"', $method, $action)
            );
        }

        if (false === $token) {
            $this->get('session')->setFlash('failure', 'CSRF Attack detected! This is bad. Very Bad hombre!');

            return $this->redirect($this->generateUrl('admin_events'));
        }

        foreach ($ids as $id) {
            $this->$method($id, $token);
        }

        return $this->redirect($this->generateUrl('admin_events'));

    }

    private function createDeleteForm($id)
    {
        $form = $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();

        return $form;
    }
}
