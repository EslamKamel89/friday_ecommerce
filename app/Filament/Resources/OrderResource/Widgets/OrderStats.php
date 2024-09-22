<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStats extends BaseWidget {

	// protected static ?string $pollingInterval = null;
	protected function getStats(): array {
		return [ 
			Stat::make( 'New Orders', Order::where( 'status', 'new' )->count() ),
			Stat::make( 'Orders Proccessing', Order::where( 'status', 'processing' )->count() ),
			Stat::make( 'Orders Shipped', Order::where( 'status', 'shipped' )->count() ),
			Stat::make( 'Orders Delivered', Order::where( 'status', 'delivered' )->count() ),
			Stat::make( 'Orders Canceled', Order::where( 'status', 'canceled' )->count() ),
			Stat::make( 'Average Price', Number::currency( Order::avg( 'grand_total' ), 'USD' ) ),
		];
	}
}
