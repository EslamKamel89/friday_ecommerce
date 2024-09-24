<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products-Friday') ]
class ProductsPage extends Component {
	use WithPagination;

	public function render() {
		$products = Product::where( 'is_active', true )->simplePaginate( 6 );
		$categories = Category::where( 'is_active', true )->get();
		$brands = Brand::where( 'is_active', true )->get();
		return view( 'livewire.products-page',
			[ 
				'products' => $products,
				'categories' => $categories,
				'brands' => $brands,
			]
		);
	}
}
