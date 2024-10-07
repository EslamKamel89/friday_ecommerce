<?php

namespace App\Livewire;

use App\Helpers\CartManagment;
use App\Models\Address;
use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;


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

	public function mount() {
		$cartItems = CartManagment::getCartItems();
		if ( count( $cartItems ) == 0 ) {
			return redirect()->route( 'home' );
		}
	}


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
		$cartItems = CartManagment::getCartItems();
		$lineItems = [];
		foreach ( $cartItems as $item ) {
			$lineItems[] = [ 
				'price_data' => [ 
					'currency' => 'USD',
					'unit_amount' => $item['unit_amount'] * 100,
					'product_data' => [ 
						'name' => $item['name']
					],
				],
				'quantity' => $item['quantity'],
			];
		}
		$order = new Order();
		$order->user_id = auth()->id();
		$order->grand_total = CartManagment::calculateGrandTotal( $cartItems );
		$order->payment_method = $this->paymentMethod;
		$order->payment_status = 'pending';
		$order->status = 'new';
		$order->currency = 'USD';
		$order->shipping_amount = 0;
		$order->shipping_method = 'none';
		$order->notes = 'Order Placed By ' . auth()->user()->name;

		$address = new Address();
		$address->first_name = $this->firstName;
		$address->last_name = $this->lastName;
		$address->phone = $this->phone;
		$address->street_address = $this->streetAddress;
		$address->city = $this->city;
		$address->state = $this->state;
		$address->zip_code = $this->zipCode;

		$redirctUrl = '';
		if ( $this->paymentMethod == 'stripe' ) {
			Stripe::setApiKey( env( 'STRIPE_SECRET' ) );
			$sessionCheckout = Session::create( [ 
				'payment_method_types' => [ 'card' ],
				'customer_email' => auth()->user()->email,
				'line_items' => $lineItems,
				'mode' => 'payment',
				'success_url' => route( 'success' ) . '?session_id={CHECKOUT_SESSION_ID}',
				'cancel_url' => route( 'cancel' ),
			] );
			$redirectUrl = $sessionCheckout->url;
		} else {
			$redirectUrl = route( 'success' );
		}
		$order->save();
		$address->order_id = $order->id;
		$address->save();
		$order->orderItems()->createMany( $cartItems );
		CartManagment::clearCartItemsFromCookie();
		return redirect( $redirectUrl );
	}
}
