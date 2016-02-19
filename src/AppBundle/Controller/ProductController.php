<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Product;
use AppBundle\Form\Type\ProductType;
use AppBundle\Form\Handler;

class ProductController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $product = $em->getRepository('AppBundle:Product')->findAll();
        return $this->render(':product:index.html.twig',array(
            'products' => $product,
        ));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     *
     * @Route("/create", name="create_product")
     * @Method({"GET","POST"})
     */
    public function createAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(new ProductType(), $product);
        if ('POST' === $request->getMethod()) {
            if ($this->get('app.product.form.handler')->process($form)) {
                $this->get('session')->getFlashBag()->add('success', 'L\'item a bien été enregistrer');
                return $this->redirect($this->generateUrl('show_product',array(
                    'id' => $product->getId(),
                )));
            }
            $this->get('session')->getFlashBag()->add('danger', 'Veuillez corriger les erreurs ci-dessous');
        }

        return $this->render(':product:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Product $product
     * @return Response
     *
     * @Route("/show/{id}", name="show_product")
     * @Method({"GET"})
     */
    public function showAction(Product $product)
    {
        return $this->render(":Product:show.html.twig",array(
            'product' => $product,
        ));
    }

    /**
     * @param Request $request
     *
     * @Route("/production/ajax/sendmail", name="production_sendmail", options={"expose"=true})
     * @Method("POST")
     */
    public function priceMinAction(Request $request)
    {
        $data = array();

        $em = $this->get('doctrine.orm.entity_manager');
        $product = $em->getRepository('AppBundle:Product')->findByWeightMin();

        $form = $this->createFormBuilder($data)
            ->add('button','prix croissant')
            ->getForm();
        if ($form->isValid()) {

            return $this->render(':Product:index.html.twig', array(
                'form' => $form->createView(),
                'product' => $product,
            ));
        }
    }


}
