<?php

namespace App\Livewire\Partials;

use App\Helpers\CartManagment;
use Illuminate\Support\Facades\Cookie;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component {
	public $totalCount = 0;
	public function mount() {
		$this->totalCount = count( CartManagment::getCartItems() );
	}
	#[On('updateTotalCount') ]
	public function updateTotalCount( $totalCount ) {
		// // Cookie::forget( 'cartItems' );
		// CartManagment::clearCartItemsFromCookie();
		// info( 'totalCount', [ $totalCount ] );
		// info( 'totalCount from Cookie', [ CartManagment::getCartItems() ] );
		// // dd();
		$this->totalCount = $totalCount;
	}
	public function render() {
		return view( 'livewire.partials.navbar' );
	}
}
