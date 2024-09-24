<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products-Friday') ]
class ProductsPage extends Component {
	use WithPagination;
	#[Url ]
	public $selectedCategories = [];

	public function render() {
		$products = Product::where( 'is_active', true )
			->where( function (Builder $query) {
				if ( collect( $this->selectedCategories )->isEmpty() ) {
					return $query;
				}
				return $query->whereIn( 'category_id', $this->selectedCategories );
			} )
			->simplePaginate( 6 );
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
