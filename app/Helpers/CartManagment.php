<?php
namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagment {
	//! add item to cart
	static public function addItemToCart( $productId, ) {
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
			// info( 'cartItems quantity', [ $cartItems[ $existingItem ]['quantity'] ] );
			$cartItems[ $existingItem ]['total_amount'] =
				$cartItems[ $existingItem ]['quantity'] * $cartItems[ $existingItem ]['unit_amount'];
			self::addCartItemsToCookie( $cartItems );
			// info( 'cartItems', [ $cartItems ] );
			// info( 'cartItemsCount', [ count( $cartItems ) ] );
			return count( $cartItems );
		}
		$product = Product::find( $productId );
		// info( 'product', [ $product ] );
		// info( 'productId', [ $productId ] );
		// dd();
		if ( $product ) {
			$cartItems[] = [ 
				'product_id' => $product->id,
				'name' => $product->name,
				'image' => collect( $product->images )->last(),
				'quantity' => 1,
				'unit_amount' => $product->price,
				'total_amount' => $product->price,
			];
		}
		self::addCartItemsToCookie( $cartItems );
		// info( 'cartItems', [ $cartItems ] );
		// info( 'cartItemsCount', [ count( $cartItems ) ] );
		return count( $cartItems );
	}

	//! add item to cart with quantity
	static public function addItemToCartWithQty( $productId, $qty = 1 ) {
		$cartItems = self::getCartItems();
		$existingItem = null;
		foreach ( $cartItems as $key => $item ) {
			if ( $item['product_id'] == $productId ) {
				$existingItem = $key;
				break;
			}
		}
		if ( $existingItem !== null ) {
			$cartItems[ $existingItem ]['quantity'] = $qty;
			$cartItems[ $existingItem ]['total_amount'] =
				$cartItems[ $existingItem ]['quantity'] * $cartItems[ $existingItem ]['unit_amount'];
			self::addCartItemsToCookie( $cartItems );
			// info( 'cartItems', [ $cartItems ] );
			// info( 'cartItemsCount', [ count( $cartItems ) ] );
			return count( $cartItems );
		}
		$product = Product::find( $productId );
		// info( 'product', [ $product ] );
		// info( 'productId', [ $productId ] );
		// dd();
		if ( $product ) {
			$cartItems[] = [ 
				'product_id' => $product->id,
				'name' => $product->name,
				'image' => $product->images[0],
				'quantity' => $qty,
				'unit_amount' => $product->price,
				'total_amount' => $product->price,
			];
		}
		self::addCartItemsToCookie( $cartItems );
		// info( 'cartItems', [ $cartItems ] );
		// info( 'cartItemsCount', [ count( $cartItems ) ] );
		return count( $cartItems );
	}

	//! remove item from cart
	static public function removeCartItem( $productId ) {
		$cartItems = self::getCartItems();
		foreach ( $cartItems as $key => $item ) {
			if ( $item['product_id'] == $productId ) {
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
		// info( 'cartItems index', [ $cartItems ] );
		if ( ! $cartItems ) {
			return [];
		}
		return $cartItems;
	}

	//! increment item quantity
	static public function incrementQuantityToCartItem( $productId ) {
		$cartItems = self::getCartItems();
		foreach ( $cartItems as $key => $item ) {
			if ( $item['product_id'] == $productId ) {
				$cartItems[ $key ]['quantity']++;
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
			if ( $item['product_id'] == $productId ) {
				if ( $cartItems[ $key ]['quantity'] <= 1 ) {
					break;
				}
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
		// info( 'calculateGrandTotal', [ $items ] );
		return array_sum( array_column( $items, 'total_amount' ) );
	}
}
