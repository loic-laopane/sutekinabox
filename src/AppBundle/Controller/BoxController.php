<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Box;
use AppBundle\Entity\BoxProduct;
use AppBundle\Form\BoxProductType;
use AppBundle\Services\BoxManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Box controller.
 *
 * @Route("box")
 */
class BoxController extends Controller
{
    /**
     * Lists all box entities.
     *
     * @Route("/", name="box_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $boxes = $em->getRepository('AppBundle:Box')->findAll();

        return $this->render('AppBundle:Box:index.html.twig', array(
            'boxes' => $boxes,
        ));
    }

    /**
     * Creates a new box entity.
     *
     * @Route("/new", name="box_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, BoxManager $boxManager)
    {
        $box = new Box();
        $form = $this->createForm('AppBundle\Form\BoxType', $box);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boxManager->insert($box);


            return $this->redirectToRoute('box_show', array('id' => $box->getId()));
        }

        return $this->render('AppBundle:Box:form.html.twig', array(
            'box' => $box,
            'form' => $form->createView()
        ));
    }

    /**
     * Finds and displays a box entity.
     *
     * @Route("/{id}", name="box_show")
     * @Method({"GET", "POST"})
     */
    public function showAction(Request $request, Box $box, BoxManager $boxManager)
    {
        $wf = $this->get('workflow.box');
        $repo = $this->getDoctrine()->getRepository('AppBundle:BoxProduct');
        $boxProducts = $repo->findBoxProducts($box);
        $validForm = $wf->can($box, 'request') ? $this->createValidForm($box)->createView() : false;

        $form = $this->createStateForm($box);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $boxManager->changeState($box);
        }

        return $this->render('AppBundle:Box:show.html.twig', array(
            'box' => $box,
            'boxProducts' => $boxProducts,
            'form' => $form->createView(),
            'valid_form' => $validForm
        ));
    }

    /**
     * Displays a form to edit an existing box entity.
     *
     * @Route("/{id}/edit", name="box_edit")
     * @Method({"GET", "POST", "DELETE"})
     */
    public function editAction(Request $request, Box $box)
    {
        $wf = $this->get('workflow.box');
        $deleteForm = $this->createDeleteForm($box);
        $validForm =  $this->createValidForm($box);
        $editForm = $this->createForm('AppBundle\Form\BoxType', $box);
        $editForm->handleRequest($request);

        $boxProductForm = $this->createForm(BoxProductType::class);

        dump($box);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('box_edit', array('id' => $box->getId()));
        }

        return $this->render('AppBundle:Box:form.html.twig', array(
            'box' => $box,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'valid_form' => $validForm->createView(),
            'boxProduct_form' => $boxProductForm->createView()
        ));
    }


    /**
     * Deletes a box entity.
     *
     * @Route("/{id}", name="box_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Box $box)
    {
        $form = $this->createDeleteForm($box);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($box);
            $em->flush();
        }

        return $this->redirectToRoute('box_list');
    }

    /**
     * @Route("/{id}/valid", name="box_valid")
     */
    public function validAction(Request $request, Box $box, BoxManager $boxManager) {
        $form = $this->createValidForm($box);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $boxManager->changeState($box);
        }
        return $this->redirectToRoute('box_edit', array('id' => $box->getId()));
    }

    /**
     * @param Request $request
     * @param Box $box
     * @param BoxManager $boxManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Method("POST")
     * @Route("/{id}/state", name="box_state")
     */
    public function stateAction(Request $request, Box $box, BoxManager $boxManager)
    {
        $form = $this->createValidForm($box);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $boxManager->changeState($box);

        }
        return $this->redirectToRoute('box_show', array('id' => $box->getId()));
    }

    /**
     * Creates a form to delete a box entity.
     *
     * @param Box $box The box entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Box $box)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('box_delete', array('id' => $box->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    private function createValidForm(Box $box)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('box_valid', array('id' => $box->getId())))
            ->setMethod('POST')
            ->getForm()
            ;
    }

    private function createStateForm(Box $box)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('box_show', array('id' => $box->getId())))
            ->setMethod('POST')
            ->getForm()
            ;
    }
}
