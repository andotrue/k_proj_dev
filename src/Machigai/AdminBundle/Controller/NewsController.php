<?php

namespace Machigai\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Machigai\GameBundle\Entity\News;
use Machigai\AdminBundle\Form\NewsType;

/**
 * News controller.
 *
 * @Route("/News")
 */
class NewsController extends Controller
{

    /**
     * Lists all News entities.
     *
     * @Route("/", name="new")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $pager = $this->get('pager');
        $pager->setInc(20); // 20件表示
        $pager->setPath('News'); // ページのrouting path

        $entities = $pager->getRepository('MachigaiGameBundle:News', array(), array('id' => 'DESC'));

        return array(
            'pager' => $pager->getParameters(),
            'entities' => $entities,
        );
    }
    /**
     * Creates a new News entity.
     *
     * @Route("/", name="news_create")
     * @Method("POST")
     * @Template("MachigaiGameBundle:News:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new News();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
			
			$tmp1 = $entity->getStartedAt();
			$entity->setStartedAt($tmp1->format('Y-m-d H:i:s'));
			$tmp2 = $entity->getEndedAt();
			$entity->setEndedAt($tmp2->format('Y-m-d H:i:s'));

			$entity->setCreatedAt(date('Y-m-d H:i:s'));
			$entity->setUpdatedAt(date('Y-m-d H:i:s'));
			
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('news_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a News entity.
    *
    * @param News $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(News $entity)
    {
        $form = $this->createForm(new NewsType(), $entity, array(
            'action' => $this->generateUrl('news_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new News entity.
     *
     * @Route("/new", name="news_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new News();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a News entity.
     *
     * @Route("/{id}", name="new_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MachigaiGameBundle:News')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing News entity.
     *
     * @Route("/{id}/edit", name="new_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MachigaiGameBundle:News')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find News entity.');
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
    * Creates a form to edit a News entity.
    *
    * @param News $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(News $entity)
    {
        $form = $this->createForm(new NewsType(), $entity, array(
            'action' => $this->generateUrl('news_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing News entity.
     *
     * @Route("/{id}", name="new_update")
     * @Method("PUT")
     * @Template("MachigaiGameBundle:News:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MachigaiGameBundle:News')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
			$tmp1 = $entity->getStartedAt();
			$entity->setStartedAt($tmp1->format('Y-m-d H:i:s'));
			$tmp2 = $entity->getEndedAt();
			$entity->setEndedAt($tmp2->format('Y-m-d H:i:s'));

			$em->flush();

            return $this->redirect($this->generateUrl('news_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a News entity.
     *
     * @Route("/{id}", name="new_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MachigaiGameBundle:News')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find News entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('new'));
    }

    /**
     * Creates a form to delete a News entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('news_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
