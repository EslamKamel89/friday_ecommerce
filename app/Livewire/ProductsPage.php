<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products-Friday') ]
class ProductsPage extends Component {
	use WithPagination;
	#[Url ]
	public $selectedCategories = [];

	#[Url ]
	public $selectedBrands = [];

	#[Url ]
	public $featured;

	#[Url ]
	public $onSale;

	#[Url ]
	public $price;

	#[Computed(true, 120) ]
	public function minPrice(): float {
		return Product::min( 'price' );
	}
	#[Computed(true, 120) ]
	public function maxPrice(): float {
		return Product::max( 'price' );
	}

	public function render() {
		$products = Product::where( 'is_active', true )
			->where( function (Builder $query) {
				if ( collect( $this->selectedCategories )->isEmpty() ) {
					return $query;
				}
				return $query->whereIn( 'category_id', $this->selectedCategories );
			} )->where( function (Builder $query) {
				if ( collect( $this->selectedBrands )->isEmpty() ) {
					return $query;
				}
				return $query->whereIn( 'brand_id', $this->selectedBrands );
			} )->where( function (Builder $query) {
				if ( $this->featured != '1' ) {
					return $query;
				}
				return $query->where( 'is_featured', $this->featured );
			} )->where( function (Builder $query) {
				if ( $this->onSale != '1' ) {
					return $query;
				}
				return $query->where( 'on_sale', $this->onSale );
			} )->where( function (Builder $query) {
				if ( $this->price == null ) {
					return $query;
				}
				return $query->where( 'price', '<=', $this->price );
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
