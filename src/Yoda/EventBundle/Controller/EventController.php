<?php

namespace Yoda\EventBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Yoda\EventBundle\Entity\Event;
use Yoda\EventBundle\Form\EventType;

/**
 * Event controller.
 *
 */
class EventController extends Controller
{

    /**
     * Lists all Event entities.
     *
     */
    public function indexAction()
    {
        return $this->render('YodaEventBundle:Event:index.html.twig', []);
    }

    public function _upcomingEventsAction($max = null)
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('YodaEventBundle:Event')
            ->getUpcomingEvents($max);

        return $this->render('YodaEventBundle:Event:_upcomingEvents.html.twig', [
            'events' => $events
        ]);
    }
    /**
     * Creates a new Event entity.
     *
     */
    public function createAction(Request $request)
    {
        $this->enforceUserSecurity('ROLE_USER_CREATE');
        $entity = new Event();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->getUser();
            $entity->setOwner($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('event_show', array('slug' => $entity->getSlug())));
        }

        return $this->render('YodaEventBundle:Event:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Event entity.
     *
     * @param Event $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Event $entity)
    {
        $form = $this->createForm(new EventType(), $entity, array(
            'action' => $this->generateUrl('event_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Event entity.
     *
     */
    public function newAction()
    {
        $this->enforceUserSecurity('ROLE_USER_CREATE');
        $entity = new Event();
        $form   = $this->createCreateForm($entity);

        return $this->render('YodaEventBundle:Event:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Event entity.
     *
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YodaEventBundle:Event')
            ->findOneBy(array('slug' => $slug));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $deleteForm = $this->createDeleteForm($entity->getId());

        return $this->render('YodaEventBundle:Event:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Event entity.
     *
     */
    public function editAction($slug)
    {
        $this->enforceUserSecurity();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YodaEventBundle:Event')->findOneBy(array('slug' => $slug));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $this->enforceOwnerSecurity($entity);

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($slug);

        return $this->render('YodaEventBundle:Event:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Event entity.
    *
    * @param Event $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Event $entity)
    {
        $form = $this->createForm(new EventType(), $entity, array(
            'action' => $this->generateUrl('event_update', array('slug' => $entity->getSlug())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Event entity.
     *
     */
    public function updateAction(Request $request, $slug)
    {
        $this->enforceUserSecurity();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YodaEventBundle:Event')->findOneBy(array('slug' => $slug));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $this->enforceOwnerSecurity($entity);

        $deleteForm = $this->createDeleteForm($slug);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('event_edit', array('slug' => $entity->getSlug())));
        }

        return $this->render('YodaEventBundle:Event:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Event entity.
     *
     */
    public function deleteAction(Request $request, $slug)
    {
        $this->enforceUserSecurity();
        $form = $this->createDeleteForm($slug);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('YodaEventBundle:Event')->findOneBy(array('slug' => $slug));

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Event entity.');
            }

            $this->enforceOwnerSecurity($entity);

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('event'));
    }

    /**
     * Creates a form to delete a Event entity by id.
     *
     * @param $slug
     * @return \Symfony\Component\Form\Form The form
     * @internal param mixed $id The entity id
     *
     */
    private function createDeleteForm($slug)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('event_delete', array('slug' => $slug)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function attendAction($slug, $format)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('YodaEventBundle:Event')
            ->findOneBy(array('slug' => $slug));

        if (!$event) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        if (!$event->hasAttendee($this->getUser())) {
            $event->getAttendees()->add($this->getUser());
            $em->persist($event);
            $em->flush();
        }

        return $this->createAttendingResponse($event, $format);

    }

    /**
     * @param string $slug
     * @param string $format
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unattendAction($slug, $format)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('YodaEventBundle:Event')
            ->findOneBy(array('slug' => $slug));

        if (!$event) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        if ($event->hasAttendee($this->getUser())) {
            $event->getAttendees()->removeElement($this->getUser());
            $em->persist($event);
            $em->flush();
        }
        return $this->createAttendingResponse($event, $format);
    }

    private function createAttendingResponse(Event $event, $format)
    {

        if ($format == 'json') {
            $data = array(
                'attending' => $event->hasAttendee($this->getUser())
            );
            $response = new JsonResponse($data);
            return $response;
        }

        $url = $this->generateUrl('event_show', array('slug' => $event->getSlug()));

        return $this->redirect($url);
        
    }

    private function enforceUserSecurity($role = 'ROLE_USER')
    {
        if (!$this->getSecurityContext()->isGranted($role)) {
            throw new AccessDeniedException('Need ' . $role);
        }
    }
}
