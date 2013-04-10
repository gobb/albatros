<?php

namespace ARIPD\StoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use ARIPD\StoreBundle\Entity\Attributekey;
use ARIPD\StoreBundle\Form\AttributekeyType;

/**
 * Attributekey controller.
 *
 * @Route("/attributekey")
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 */
class AttributekeyController extends Controller
{
    /**
     * Lists all Attributekey entities.
     *
     * @Route("/", name="aripd_admin_store_attributekey_index")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ARIPDStoreBundle:Attributekey')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Attributekey entity.
     *
     * @Route("/{id}/show", name="aripd_admin_store_attributekey_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ARIPDStoreBundle:Attributekey')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attributekey entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Attributekey entity.
     *
     * @Route("/new", name="aripd_admin_store_attributekey_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Attributekey();
        $form   = $this->createForm(new AttributekeyType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Attributekey entity.
     *
     * @Route("/create", name="aripd_admin_store_attributekey_create")
     * @Method("POST")
     * @Template("ARIPDStoreBundle:Attributekey:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Attributekey();
        $form = $this->createForm(new AttributekeyType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('aripd_admin_store_attributekey_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Attributekey entity.
     *
     * @Route("/{id}/edit", name="aripd_admin_store_attributekey_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ARIPDStoreBundle:Attributekey')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attributekey entity.');
        }

        $editForm = $this->createForm(new AttributekeyType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Attributekey entity.
     *
     * @Route("/{id}/update", name="aripd_admin_store_attributekey_update")
     * @Method("POST")
     * @Template("ARIPDStoreBundle:Attributekey:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ARIPDStoreBundle:Attributekey')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attributekey entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AttributekeyType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('aripd_admin_store_attributekey_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Attributekey entity.
     *
     * @Route("/{id}/delete", name="aripd_admin_store_attributekey_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ARIPDStoreBundle:Attributekey')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Attributekey entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('attributekey'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
