<?php

namespace App\Livewire;

use App\Helpers\CartManagment;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ProductDetailPage extends Component {
	public Product $product;
	public int $quantity = 1;
	use LivewireAlert;
	public function mount( Product $product ) {
		$this->product = $product;
	}
	public function render() {
		return view( 'livewire.product-detail-page' );
	}
	public function increment() {

		$this->quantity++;
	}
	public function decrement() {
		if ( $this->quantity <= 1 ) {
			return;
		}
		$this->quantity--;
	}

	public function updated( $property ) {
		if ( $property == 'quantity' ) {
			info( 'property', [ $property ] );
			info( 'value', [ $this->quantity ] );
			$this->quantity <= 1 ? $this->quantity = 1 : null;
		}
	}
	public function addToCart( $productId ) {
		$totalCount = CartManagment::addItemToCartWithQty( $productId, $this->quantity );
		// $totalCount = 5;
		$this->dispatch( 'updateTotalCount', $totalCount )->to( Navbar::class);
		$this->alert( 'success', 'Product added to the cart successfully' );
		info( 'cartItems', [ CartManagment::getCartItems() ] );
	}
}
