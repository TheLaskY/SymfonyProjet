<?php

namespace BateauxBundle\Controller;

use BateauxBundle\Entity\location;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Location controller.
 *
 * @Route("location")
 */
class locationController extends Controller
{
    /**
     * Lists all location entities.
     *
     * @Route("/", name="location_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $locations = $em->getRepository('BateauxBundle:location')->findAll();

        return $this->render('location/index.html.twig', array(
            'locations' => $locations,
        ));
    }

    /**
     * Creates a new location entity.
     *
     * @Route("/new", name="location_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $location = new Location();
        $form = $this->createForm('BateauxBundle\Form\locationType', $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($location);
            $em->flush();

            return $this->redirectToRoute('location_show', array('idlocation' => $location->getIdlocation()));
        }

        return $this->render('location/new.html.twig', array(
            'location' => $location,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a location entity.
     *
     * @Route("/{idlocation}", name="location_show")
     * @Method("GET")
     */
    public function showAction(location $location)
    {
        $deleteForm = $this->createDeleteForm($location);

        return $this->render('location/show.html.twig', array(
            'location' => $location,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing location entity.
     *
     * @Route("/{idlocation}/edit", name="location_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, location $location)
    {
        $deleteForm = $this->createDeleteForm($location);
        $editForm = $this->createForm('BateauxBundle\Form\locationType', $location);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('location_edit', array('idlocation' => $location->getIdlocation()));
        }

        return $this->render('location/edit.html.twig', array(
            'location' => $location,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a location entity.
     *
     * @Route("/{idlocation}", name="location_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, location $location)
    {
        $form = $this->createDeleteForm($location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($location);
            $em->flush();
        }

        return $this->redirectToRoute('location_index');
    }

    /**
     * Creates a form to delete a location entity.
     *
     * @param location $location The location entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(location $location)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('location_delete', array('idlocation' => $location->getIdlocation())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
