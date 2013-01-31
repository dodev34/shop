<?php

namespace Mitch\Bundle\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mitch\Bundle\ShopBundle\Form\CartInfos;
use Mitch\Bundle\ShopBundle\Form\CartHandler;
use Mitch\Bundle\ShopBundle\Form\CartType;
use Mitch\Bundle\ShopBundle\Entity\Cart;

class ShopController extends Controller
{
    /**
     * Index
     * 
     * @return Response
     */
    public function indexAction()
    {   
        $em = $this->getDoctrine()->getEntityManager();

        $form        = $this->createForm(new CartType(), new Cart());
        $formHandler = new CartHandler($form, $this->get('request'), $em);

        // Form process
        if($formHandler->process()) 
        {
            // Message flash d'information.
            $this->get('session')->setFlash('success', "Le produit a été ajouté à votre panier.");
            return $this->redirect($this->generateUrl('mitch_shop_show'));
        }

        return $this->render('MitchShopBundle:Shop:index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function showAction()
    {
        $products = array();

        $em = $this->getDoctrine()->getEntityManager();

        $cart = $this->get('session')->get(CartInfos::SESSION_NAME);

        // On aurais put stocker le nom, ça aurai évité une requête, mais pour le coup..
        if (!empty($cart)) {
            $products = $em->getRepository('MitchShopBundle:Product')->findByIds($cart);
        }

        return $this->render('MitchShopBundle:Shop:show.html.twig', array(
            'products' => $products
        ));
    }

    /**
     * Drop la session cart de l'utilisateur
     * 
     * @return resirect mitch_shop_show
     */
    public function dropAction()
    {
        $this->get('session')->set(CartInfos::SESSION_NAME, array());
        
        $this->get('session')->setFlash('success', "Votre panier vien d'être vidé.");

        return $this->redirect($this->generateUrl('mitch_shop_show'));
    }

    /**
     * Check if user has cart
     * Mitch\Bundle\ShopBundle\Listener\CardListener
     *
     * Comme on est joueur, on a mis ça dans un listener ^^
     * 
     * @return void
     */
    public function preExecute()
    {
        if (!$this->get('session')->has(CartInfos::SESSION_NAME)) {
            $this->get('session')->set(CartInfos::SESSION_NAME, array());
        }
    }
}
