<?php
namespace Mitch\Bundle\ShopBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Mitch\Bundle\ShopBundle\Entity\Cart;
use Mitch\Bundle\ShopBundle\Form\CartInfos;

final class CartHandler
{
  /**
   * @var Form
   */
  protected $form;

  /**
   * @var Request
   */
  protected $request;

  /**
   * @var EntityManager
   */
  protected $em;
  
  /**
   * 
   */
  public function __construct(Form $form, Request $request, EntityManager $em) 
  {
    $this->form    = $form;
    $this->request = $request;
    $this->em      = $em;       
  }
  
  /**
   *
   * @return bool 
   */
  public function process() 
  {
    if( $this->request->getMethod() == 'POST' ) 
    {
      $this->form->bindRequest($this->request);

      if( $this->form->isValid() ) 
      {
        $this->onSuccess($this->form->getData());
        
        return true;
      }
    }
    return false;
  }

  /**
   * Enregistrement.
   * 
   * @param  Cart $cart
   */
  public function onSuccess(Cart $cart) 
  {
    $this->em->persist($cart);
    $this->em->flush();

    // Mise a jour de la session
    $session_cart   = $this->request->getSession()->get(CartInfos::SESSION_NAME);
    $session_cart[] = $cart->getProduct()->getId();
    // Une seule fois le même produit en session
    $session_cart   = array_unique($session_cart, SORT_NUMERIC);
    $this->request->getSession()->set(CartInfos::SESSION_NAME, $session_cart);
  }
}