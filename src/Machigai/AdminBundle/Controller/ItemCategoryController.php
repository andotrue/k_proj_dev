<?php

namespace Machigai\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Machigai\GameBundle\Entity\ItemCategory;
use Machigai\AdminBundle\Form\ItemCategoryType;

/**
 * Item controller.
 *
 * @Route("/itemcategory")
 */
class ItemCategoryController extends Controller
{

    /**
     * Lists all ItemCategory entities.
     *
     * @Route("/", name="itemcategory")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $pager = $this->get('pager');
        $pager->setInc(20); // 20件表示
        $pager->setPath('itemcategory'); // ページのrouting path

        $entities = $pager->getRepository('MachigaiGameBundle:ItemCategory', array(), array('categoryCode' => 'DESC'));

        return array(
            'pager' => $pager->getParameters(),
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Item entity.
     *
     * @Route("/", name="itemcategory_create")
     * @Method("POST")
     * @Template("MachigaiGameBundle:ItemCategory:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ItemCategory();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
			
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('itemcategory_show', array('categoryCode' => $entity->getCategoryCode())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a ItemCategory entity.
    *
    * @param Item $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(ItemCategory $entity)
    {
        $form = $this->createForm(new ItemCategoryType(), $entity, array(
            'action' => $this->generateUrl('itemcategory_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Item entity.
     *
     * @Route("/new", name="itemcategory_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ItemCategory();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Item entity.
     *
     * @Route("/{categoryCode}", name="itemcategory_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($categoryCode)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MachigaiGameBundle:ItemCategory')->find($categoryCode);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ItemCategory entity.');
        }

        $deleteForm = $this->createDeleteForm($categoryCode);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Item entity.
     *
     * @Route("/{categoryCode}/edit", name="itemcategory_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($categoryCode)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MachigaiGameBundle:ItemCategory')->find($categoryCode);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ItemCategory entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($categoryCode);

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
    private function createEditForm(ItemCategory $entity)
    {
        $form = $this->createForm(new ItemCategoryType(), $entity, array(
            'action' => $this->generateUrl('itemcategory_update', array('categoryCode' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    /**
     * Edits an existing Item entity.
     *
     * @Route("/{categoryCode}", name="itemcategory_update")
     * @Method("PUT")
     * @Template("MachigaiGameBundle:ItemCategory:edit.html.twig")
     */
    public function updateAction(Request $request, $categoryCode)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MachigaiGameBundle:ItemCategory')->find($categoryCode);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ItemCategory entity.');
        }

        $deleteForm = $this->createDeleteForm($categoryCode);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
			
            $em->flush();

            return $this->redirect($this->generateUrl('itemcategory_show', array('categoryCode' => $categoryCode)));
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
     * @Route("/{categoryCode}", name="itemcategory_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $categoryCode)
    {
        $form = $this->createDeleteForm($categoryCode);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MachigaiGameBundle:ItemCategory')->find($categoryCode);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ItemCategory entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('itemcategory'));
    }

    /**
     * Creates a form to delete a Item entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($categoryCode)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('itemcategory_delete', array('categoryCode' => $categoryCode)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn btn-primary')))
            ->getForm()
        ;
    }
}
