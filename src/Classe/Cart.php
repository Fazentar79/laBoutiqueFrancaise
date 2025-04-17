<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

readonly class Cart
{
    public function __construct(private RequestStack $requestStack)
    {

    }

    /*
     add()
    Fonction permettant l'ajout d'un produit au panier
     */

    public function add($product): void
    {
        $cart = $this->getCart();

        if (isset($cart[$product->getId()])) {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => $cart[$product->getId()]['qty'] + 1
            ];
        } else {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => 1
            ];
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }

    /*
     getCart()
    Fonction retournant le panier
     */

    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }

    /*
     decrease()
    Fonction permettant la suppression d'une quantity d'un produit au panier
     */

    public function decrease($id): void
    {
        $cart = $this->getCart();

        if ($cart[$id]['qty'] > 1) {
            $cart[$id]['qty'] -= 1;
        } else {
            unset($cart[$id]);
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }

    /*
     fullQuantity()
    Fonction retournant le nombre de produits au panier
     */

    public function fullQuantity(): int
    {
        $cart = $this->getCart();
        $quantity = 0;

        if (!isset($cart)) {
            return $quantity;
        }

        foreach ($cart as $product) {
            $quantity += $product['qty'];
        }

        return $quantity;
    }

    /*
     getTotalWt()
    Fonction retournant le prix total des produits au panier
     */

    public function getTotalWt(): float|int
    {
        $cart = $this->getCart();
        $price = 0;

        if (!isset($cart)) {
            return $price;
        }

        foreach ($cart as $product) {
            $price += ($product['object']->getPriceWt() * $product['qty']);
        }
        return $price;
    }

    /*
     remove()
    Fonction permettant de supprimer totalement le panier
     */

    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }
}
