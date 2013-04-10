<?php

namespace ARIPD\SCMBundle\Controller;

use ARIPD\SCMBundle\Entity\Supplier;
use ARIPD\SCMBundle\Form\SupplierType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDSCMBundle:Supplier
 *
 * @Route("/supplier")
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 */
class SupplierController extends Controller
{
    /**
     * Lists all Supplier entities.
     *
     * @Route("/index", name="aripd_admin_scm_supplier_index")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ARIPDSCMBundle:Supplier')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Supplier entity.
     *
     * @Route("/{id}/show", name="aripd_admin_scm_supplier_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ARIPDSCMBundle:Supplier')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Supplier entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Supplier entity.
     *
     * @Route("/new", name="aripd_admin_scm_supplier_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Supplier();
        $form   = $this->createForm(new SupplierType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Supplier entity.
     *
     * @Route("/create", name="aripd_admin_scm_supplier_create")
     * @Method("POST")
     * @Template("ARIPDSCMBundle:Supplier:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Supplier();
        $form = $this->createForm(new SupplierType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('supplier_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Supplier entity.
     *
     * @Route("/{id}/edit", name="aripd_admin_scm_supplier_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ARIPDSCMBundle:Supplier')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Supplier entity.');
        }

        $editForm = $this->createForm(new SupplierType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Supplier entity.
     *
     * @Route("/{id}/update", name="aripd_admin_scm_supplier_update")
     * @Method("POST")
     * @Template("ARIPDSCMBundle:Supplier:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ARIPDSCMBundle:Supplier')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Supplier entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new SupplierType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('supplier_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Supplier entity.
     *
     * @Route("/{id}/delete", name="aripd_admin_scm_supplier_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ARIPDSCMBundle:Supplier')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Supplier entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('supplier'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
