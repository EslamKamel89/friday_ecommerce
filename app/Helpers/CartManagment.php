<?php
namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagment {
	//! add item to cart
	static public function addItemToCart( $productId ) {
		$cartItems = self::getCartItems();
		$existingItem = null;
		foreach ( $cartItems as $key => $item ) {
			if ( $item['product_id'] == $productId ) {
				$existingItem = $key;
				break;
			}
		}
		if ( $existingItem !== null ) {
			$cartItems[ $existingItem ]['quantity']++;
			$cartItems[ $existingItem ]['total_amount'] =
				$cartItems[ $existingItem ]['quantity'] * $cartItems[ $existingItem ]['unit_amount'];
			return;
		}
		$product = Product::find( $productId );
		if ( $product ) {
			$cartItems[] = [ 
				'product_id' => $product->id,
				'name' => $product->name,
				'image' => $product->iamges[0],
				'quantity' => 1,
				'unit_amount' => $product->price,
				'total_amount' => $product->price,
			];
		}
		self::addCartItemsToCookie( $cartItems );
		return count( $cartItems );
	}
	//! remove item from cart
	static public function removeCartItem( $productId ) {
		$cartItems = self::getCartItems();
		foreach ( $cartItems as $key => $item ) {
			if ( $item['productId'] == $productId ) {
				unset( $cartItems[ $key ] );
			}
		}
		self::addCartItemsToCookie( $cartItems );
		return $cartItems;
	}

	//! add cart items to cookie
	static public function addCartItemsToCookie( $cartItems ) {
		Cookie::queue(
			'cartItems', json_encode( $cartItems ), 60 * 24 * 30 );
	}

	//! clear cart items from cookie
	static public function clearCartItemsFromCookie() {
		Cookie::queue( Cookie::forget( 'cartItems' ) );
	}

	//! get all cart items from cookie
	static public function getCartItems() {
		$cartItems = json_decode( Cookie::get( 'cartItems' ), true );
		if ( $cartItems ) {
			return [];
		}
		return $cartItems;
	}

	//! increment item quantity
	static public function incrementQuantityToCartItem( $productId ) {
		$cartItems = self::getCartItems();
		foreach ( $cartItems as $key => $item ) {
			if ( $item['product_id'] == $productId ) {
				$item['quantity']++;
				$cartItems[ $key ]['total_amount'] =
					$cartItems[ $key ]['unit_amount'] *
					$cartItems[ $key ]['quantity'];
			}
		}
		self::addCartItemsToCookie( $cartItems );
	}
	//! decrement item quantity
	static public function decrementQuantityToCartItem( $productId ) {
		$cartItems = self::getCartItems();
		foreach ( $cartItems as $key => $item ) {
			if ( $item['poduct_id'] == $productId ) {
				$cartItems[ $key ]['quantity']--;
				$cartItems[ $key ]['total_amount'] =
					$cartItems[ $key ]['unit_amount'] *
					$cartItems[ $key ]['quantity'];
			}
		}
		self::addCartItemsToCookie( $cartItems );
		return $cartItems;
	}

	//! calculate grand total
	public static function calculateGrandTotal( $items ) {
		return array_sum( array_column( $items, 'total_amount' ) );
	}
}
