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
 * Class EventController
 *
 * @Route("/event")
 *
 * @package Black\Bundle\EventBundle\Controller
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class EventController extends Controller
{
    /**
     * @Method("GET")
     * @Route("s.html", name="events")
     * @Secure(roles="ROLE_USER")
     * @Template()
     *
     * @return Template
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function indexAction()
    {
        $eventManager   = $this->getEventManager();
        $documents      = $eventManager->findOpenEvents();

        return array('documents' => $documents);
    }

    /**
     * @param $slug
     *
     * @Method("GET")
     * @Route("/{slug}.html", name="event_show")
     * @Secure(roles="ROLE_USER")
     * @Template()
     *
     * @return Template
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($slug)
    {
        $eventManager   = $this->getEventManager();
        $document       = $eventManager->findEventBySlug($slug);

        if (!$document) {
            $this->createNotFoundException('Unable to find this document!');
        }

        $registered = false;

        $invitationManager = $this->getInvitationManager();
        $invitation = $invitationManager->findEventForPerson($document->getId(), $this->getUser()->getPerson()->getId());

        if ($invitation) {
            $registered = true;
        }

        return array(
            'document'      => $document,
            'invitation'    => $invitation,
            'registered'    => $registered
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
    protected function getInvitationManager()
    {
        return $this->get('black_event.manager.invitation');
    }
}
