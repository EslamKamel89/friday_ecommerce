<?php

namespace App\Livewire;

use App\Helpers\CartManagment;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cart-Friday') ]
class CartPage extends Component {
	public $cartItems = [];
	public $grandTotal;
	public function mount() {
		$this->cartItems = CartManagment::getCartItems();
		$this->grandTotal = CartManagment::calculateGrandTotal( $this->cartItems );
	}
	public function render() {
		return view( 'livewire.cart-page' );
	}

	public function removeItem( $productId ) {
		// info( 'items Removed', [ $productId ] );
		$this->cartItems = CartManagment::removeCartItem( $productId );
		$this->grandTotal = CartManagment::calculateGrandTotal( $this->cartItems );
		$this->dispatch( 'updateTotalCount', count( $this->cartItems ) );
	}

	public function increment( $productId ) {
		CartManagment::incrementQuantityToCartItem( $productId );
		$this->cartItems = CartManagment::getCartItems();
		// info( 'increment', $this->cartItems );
		$this->grandTotal = CartManagment::calculateGrandTotal( $this->cartItems );
		$this->dispatch( 'updateTotalCount', count( $this->cartItems ) );
	}
	public function decrement( $productId ) {
		CartManagment::decrementQuantityToCartItem( $productId );
		$this->cartItems = CartManagment::getCartItems();
		// info( 'decrement', $this->cartItems );
		$this->grandTotal = CartManagment::calculateGrandTotal( $this->cartItems );
		$this->dispatch( 'updateTotalCount', count( $this->cartItems ) );
	}
}
