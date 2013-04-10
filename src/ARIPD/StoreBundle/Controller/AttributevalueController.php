<?php

namespace ARIPD\StoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use ARIPD\StoreBundle\Entity\Attributevalue;
use ARIPD\StoreBundle\Form\AttributevalueType;

/**
 * Attributevalue controller.
 *
 * @Route("/attributevalue")
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 */
class AttributevalueController extends Controller
{
    /**
     * Lists all Attributevalue entities.
     *
     * @Route("/", name="aripd_admin_store_attributevalue_index")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ARIPDStoreBundle:Attributevalue')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Attributevalue entity.
     *
     * @Route("/{id}/show", name="aripd_admin_store_attributevalue_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ARIPDStoreBundle:Attributevalue')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attributevalue entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Attributevalue entity.
     *
     * @Route("/new", name="aripd_admin_store_attributevalue_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Attributevalue();
        $form   = $this->createForm(new AttributevalueType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Attributevalue entity.
     *
     * @Route("/create", name="aripd_admin_store_attributevalue_create")
     * @Method("POST")
     * @Template("ARIPDStoreBundle:Attributevalue:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Attributevalue();
        $form = $this->createForm(new AttributevalueType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('aripd_admin_store_attributevalue_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Attributevalue entity.
     *
     * @Route("/{id}/edit", name="aripd_admin_store_attributevalue_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ARIPDStoreBundle:Attributevalue')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attributevalue entity.');
        }

        $editForm = $this->createForm(new AttributevalueType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Attributevalue entity.
     *
     * @Route("/{id}/update", name="aripd_admin_store_attributevalue_update")
     * @Method("POST")
     * @Template("ARIPDStoreBundle:Attributevalue:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ARIPDStoreBundle:Attributevalue')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attributevalue entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AttributevalueType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('aripd_admin_store_attributevalue_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Attributevalue entity.
     *
     * @Route("/{id}/delete", name="aripd_admin_store_attributevalue_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ARIPDStoreBundle:Attributevalue')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Attributevalue entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('attributevalue'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
