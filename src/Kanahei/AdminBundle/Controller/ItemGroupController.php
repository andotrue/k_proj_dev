<?php

namespace Kanahei\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Kanahei\GameBundle\Entity\ItemGroup;
use Kanahei\AdminBundle\Form\ItemGroupType;

/**
 * Item controller.
 *
 * @Route("/itemgroup")
 */
class ItemGroupController extends Controller
{

    /**
     * Lists all itemgroup entities.
     *
     * @Route("/", name="itemgroup")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $pager = $this->get('pager');
        $pager->setInc(20); // 20件表示
        $pager->setPath('itemgroup'); // ページのrouting path

        $entities = $pager->getRepository('KanaheiGameBundle:ItemGroup', array(), array('groupCode' => 'DESC'));

        return array(
            'pager' => $pager->getParameters(),
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Item entity.
     *
     * @Route("/", name="itemgroup_create")
     * @Method("POST")
     * @Template("KanaheiGameBundle:ItemGroup:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ItemGroup();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
			
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('itemgroup_show', array('groupCode' => $entity->getGroupCode())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a ItemGroup entity.
    *
    * @param Item $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(ItemGroup $entity)
    {
        $form = $this->createForm(new ItemGroupType(), $entity, array(
            'action' => $this->generateUrl('itemgroup_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Item entity.
     *
     * @Route("/new", name="itemgroup_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ItemGroup();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Item entity.
     *
     * @Route("/{groupCode}", name="itemgroup_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($groupCode)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('KanaheiGameBundle:ItemGroup')->find($groupCode);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ItemGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($groupCode);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Item entity.
     *
     * @Route("/{groupCode}/edit", name="itemgroup_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($groupCode)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('KanaheiGameBundle:ItemGroup')->find($groupCode);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ItemGroup entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($groupCode);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Item entity.
    *
    * @param Item $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ItemGroup $entity)
    {
        $form = $this->createForm(new ItemGroupType(), $entity, array(
            'action' => $this->generateUrl('itemgroup_update', array('groupCode' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    /**
     * Edits an existing Item entity.
     *
     * @Route("/{groupCode}", name="itemgroup_update")
     * @Method("PUT")
     * @Template("KanaheiGameBundle:ItemGroup:edit.html.twig")
     */
    public function updateAction(Request $request, $groupCode)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('KanaheiGameBundle:ItemGroup')->find($groupCode);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ItemGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($groupCode);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
			
            $em->flush();

            return $this->redirect($this->generateUrl('itemgroup_show', array('groupCode' => $groupCode)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Item entity.
     *
     * @Route("/{groupCode}", name="itemgroup_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $groupCode)
    {
        $form = $this->createDeleteForm($groupCode);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('KanaheiGameBundle:ItemGroup')->find($groupCode);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ItemGroup entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('itemgroup'));
    }

    /**
     * Creates a form to delete a Item entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($groupCode)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('itemgroup_delete', array('groupCode' => $groupCode)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn btn-primary')))
            ->getForm()
        ;
    }
}
