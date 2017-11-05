<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Box;
use AppBundle\Entity\User;
use AppBundle\Form\BoxProductType;
use AppBundle\Services\BoxManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

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
        $repo =  $this->getDoctrine()->getRepository('AppBundle:Box');

        $boxes = $this->get('security.authorization_checker')->isGranted('ROLE_ACHAT')
            ? $repo->findAchatBoxes()
            :$repo->findByCreator($this->getUser());


        return $this->render('AppBundle:Box:index.html.twig', array(
            'boxes' => $boxes,
        ));
    }

    /**
     * Creates a new box entity.
     *
     * @Route("/new", name="box_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_MARKETING')")
     */
    public function newAction(Request $request, BoxManager $boxManager)
    {
        $box = new Box();
        $form = $this->createForm('AppBundle\Form\BoxType', $box);
        $form->handleRequest($request);
        if(!$this->getUser() instanceof User) throw new Exception('L\'utilisateur n\'est pas connecté');

        $box->setCreator($this->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
            $boxManager->insert($box);


            return $this->redirectToRoute('box_edit', array('id' => $box->getId()));
        }

        return $this->render('AppBundle:Box:form.html.twig', array(
            'box' => $box,
            'form' => $form->createView(),
            'can_save' => true
        ));
    }

    /**
     * Finds and displays a box entity.
     *
     * @Route("/manage/{id}", name="box_manage")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ACHAT')")
     */
    public function manageAction(Request $request, Box $box, BoxManager $boxManager)
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

        $btn_state = $boxManager->getNextState($box->getState());

        return $this->render('AppBundle:Box:manage.html.twig', array(
            'box' => $box,
            'boxProducts' => $boxProducts,
            'form' => $form->createView(),
            'valid_form' => $validForm,
            'btn_state' => $btn_state
        ));
    }

    /**
     * Displays a form to edit an existing box entity.
     *
     * @Route("/{id}/edit", name="box_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_MARKETING')")
     */
    public function editAction(Request $request, Box $box)
    {
        $wf = $this->get('workflow.box');
        $deleteForm = $this->createDeleteForm($box);
        $validForm =  $this->createValidForm($box);
        $editForm = $this->createForm('AppBundle\Form\BoxType', $box);
        $editForm->handleRequest($request);
        $can_save = $wf->can($box, 'request') ? true : false;

        $boxProductForm = $this->createForm(BoxProductType::class);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'La box a bien été mise à jour');
            //return $this->redirectToRoute('box_edit', array('id' => $box->getId()));
        }

        return $this->render('AppBundle:Box:form.html.twig', array(
            'box' => $box,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'valid_form' => $validForm->createView(),
            'boxProduct_form' => $boxProductForm->createView(),
            'can_save' => $can_save
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
     * Faire changer l'état d'une box de created to request
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
        return $this->redirectToRoute('box_manage', array('id' => $box->getId()));
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

    /**
     * Form pour valider une box
     * @param Box $box
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
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
            ->setAction($this->generateUrl('box_manage', array('id' => $box->getId())))
            ->setMethod('POST')
            ->getForm()
            ;
    }
}
