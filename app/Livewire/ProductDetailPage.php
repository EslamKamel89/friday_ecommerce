<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductDetailPage extends Component {
	public Product $product;
	public function mount( Product $product ) {
		$this->product = $product;
	}
	public function render() {
		return view( 'livewire.product-detail-page' );
	}
}
