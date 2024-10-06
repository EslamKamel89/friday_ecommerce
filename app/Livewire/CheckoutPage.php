<?php

namespace App\Livewire;

use App\Helpers\CartManagment;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('CheckOut-Friday') ]
class CheckoutPage extends Component {
	public $firstName;
	public $lastName;
	public $phone;
	public $streetAddress;
	public $city;
	public $state;
	public $zipCode;
	public $paymentMethod;

	public function render() {
		$cartItems = CartManagment::getCartItems();
		$grandTotal = CartManagment::calculateGrandTotal( $cartItems );
		return view( 'livewire.checkout-page',
			[ 
				'cartItems' => $cartItems,
				'grandTotal' => $grandTotal,
			] );
	}

	public function placeOrder() {
		$this->validate( [ 
			'firstName' => 'required',
			'lastName' => 'required',
			'phone' => 'required',
			'streetAddress' => 'required',
			'city' => 'required',
			'state' => 'required',
			'zipCode' => 'required',
			'paymentMethod' => 'required',
		] );
	}
}
