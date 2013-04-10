<?php
namespace ARIPD\StockBundle\Controller;
use ARIPD\StockBundle\Entity\Incoming;
use ARIPD\StockBundle\Form\Type\IncomingFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDStockBundle:Incoming
 *
 * @Route("/incoming")
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 */
class IncomingController extends Controller
{
    /**
     * Lists all Incoming entities.
     *
     * @Route("/", name="aripd_admin_store_incoming_index")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ARIPDStockBundle:Incoming')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Incoming entity.
     *
     * @Route("/{id}/show", name="aripd_admin_store_incoming_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ARIPDStockBundle:Incoming')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Incoming entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Incoming entity.
     *
     * @Route("/new", name="aripd_admin_store_incoming_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Incoming();
        $form   = $this->createForm(new IncomingFormType(), $entity, array(
        		'em' => $this->getDoctrine()->getManager(),
        ));

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Incoming entity.
     *
     * @Route("/create", name="aripd_admin_store_incoming_create")
     * @Method("POST")
     * @Template("ARIPDStockBundle:Incoming:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Incoming();
        $form = $this->createForm(new IncomingFormType(), $entity, array(
        		'em' => $this->getDoctrine()->getManager(),
        ));
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('aripd_admin_store_incoming_index'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Incoming entity.
     *
     * @Route("/{id}/edit", name="aripd_admin_store_incoming_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ARIPDStockBundle:Incoming')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Incoming entity.');
        }

        $editForm = $this->createForm(new IncomingFormType(), $entity, array(
        		'em' => $this->getDoctrine()->getManager(),
        ));
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Incoming entity.
     *
     * @Route("/{id}/update", name="aripd_admin_store_incoming_update")
     * @Method("POST")
     * @Template("ARIPDStockBundle:Incoming:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ARIPDStockBundle:Incoming')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Incoming entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new IncomingFormType(), $entity, array(
        		'em' => $this->getDoctrine()->getManager(),
        ));
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('aripd_admin_store_incoming_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Incoming entity.
     *
     * @Route("/{id}/delete", name="aripd_admin_store_incoming_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ARIPDStockBundle:Incoming')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Incoming entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('aripd_admin_store_incoming_index'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
