<?php

namespace Machigai\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Machigai\GameBundle\Entity\Ranking;
use Machigai\AdminBundle\Form\RankingType;

/**
 * Ranking controller.
 *
 * @Route("/Ranking")
 */
class RankingController extends Controller
{

    /**
     * Lists all Ranking entities.
     *
     * @Route("/", name="ranking")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $pager = $this->get('pager');
        $pager->setInc(20); // 20件表示
        $pager->setPath('Ranking'); // ページのrouting path

        $entities = $pager->getRepository('MachigaiGameBundle:Ranking', array(), array('id' => 'DESC'));

        return array(
            'pager' => $pager->getParameters(),
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Ranking entity.
     *
     * @Route("/", name="ranking_create")
     * @Method("POST")
     * @Template("MachigaiGameBundle:Ranking:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Ranking();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ranking_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Ranking entity.
    *
    * @param Ranking $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Ranking $entity)
    {
        $form = $this->createForm(new RankingType(), $entity, array(
            'action' => $this->generateUrl('ranking_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Ranking entity.
     *
     * @Route("/new", name="ranking_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Ranking();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Ranking entity.
     *
     * @Route("/{id}", name="ranking_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MachigaiGameBundle:Ranking')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ranking entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Ranking entity.
     *
     * @Route("/{id}/edit", name="ranking_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MachigaiGameBundle:Ranking')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ranking entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Ranking entity.
    *
    * @param Ranking $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Ranking $entity)
    {
        $form = $this->createForm(new RankingType(), $entity, array(
            'action' => $this->generateUrl('ranking_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Ranking entity.
     *
     * @Route("/{id}", name="ranking_update")
     * @Method("PUT")
     * @Template("MachigaiGameBundle:Ranking:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MachigaiGameBundle:Ranking')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ranking entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ranking_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Ranking entity.
     *
     * @Route("/{id}", name="ranking_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MachigaiGameBundle:Ranking')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ranking entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ranking'));
    }

    /**
     * Creates a form to delete a Ranking entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ranking_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
