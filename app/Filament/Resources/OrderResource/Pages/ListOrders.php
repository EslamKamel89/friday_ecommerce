<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords {
	protected static string $resource = OrderResource::class;

	protected function getHeaderActions(): array {
		return [ 
			Actions\CreateAction::make(),
		];
	}
	protected function getHeaderWidgets(): array {
		return [ 
			OrderStats::class,
		];
	}

	public function getTabs(): array {
		return [ 
			'all' => Tab::make(),
			'new' => Tab::make()->modifyQueryUsing( fn( Builder $query ) => $query->where( 'status', 'new' ) ),
			'processing' => Tab::make()->modifyQueryUsing( fn( Builder $query ) => $query->where( 'status', 'processing' ) ),
			'shipped' => Tab::make()->modifyQueryUsing( fn( Builder $query ) => $query->where( 'status', 'shipped' ) ),
			'delivered' => Tab::make()->modifyQueryUsing( fn( Builder $query ) => $query->where( 'status', 'delivered' ) ),
			'canceled' => Tab::make()->modifyQueryUsing( fn( Builder $query ) => $query->where( 'status', 'canceled' ) ),
		];
	}
}
