<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrders extends BaseWidget {
	protected int|string|array $columnSpan = 'full';
	protected static ?int $sort = 3;
	public function table( Table $table ): Table {
		return $table
			->query( query: OrderResource::getEloquentQuery() )
			->defaultPaginationPageOption( 5 )
			->defaultSort( 'created_at', 'desc' )
			->columns( [ 
				TextColumn::make( 'user.name' )
					->numeric()
					->sortable()
					->searchable(),
				TextColumn::make( 'grand_total' )
					->numeric()
					->sortable()
					->money( 'usd' ),
				TextColumn::make( 'payment_method' )
					->searchable()
					->sortable(),
				TextColumn::make( 'payment_status' )
					->searchable()
					->sortable(),
				SelectColumn::make( 'status' )
					->options( [ 
						'new' => 'new',
						'processing' => 'processing',
						'shipped' => 'shipped',
						'delivered' => 'delivered',
						'canceled' => 'canceled',
					] )
					->selectablePlaceholder( false )
					->searchable()
					->sortable()
					->toggleable(),
				TextColumn::make( 'currency' )
					->searchable()
					->toggleable( isToggledHiddenByDefault: true ),
				TextColumn::make( 'shipping_amount' )
					->numeric()
					->sortable()
					->toggleable( isToggledHiddenByDefault: true ),
				TextColumn::make( 'shipping_method' )
					->searchable(),
				TextColumn::make( 'created_at' )
					->dateTime()
					->sortable()
					->toggleable( isToggledHiddenByDefault: true ),
				TextColumn::make( 'updated_at' )
					->dateTime()
					->sortable()
					->toggleable( isToggledHiddenByDefault: true ),
			] )->actions( [ 
					ActionGroup::make( [ 
						Action::make( 'view' )->url(
							fn( Order $order ): string => OrderResource::getUrl( 'view', [ 'record' => $order ] )
						)
							->color( 'success' )
							->icon( 'heroicon-o-eye' ),
						Action::make( 'edit' )->url(
							fn( Order $order ): string => OrderResource::getUrl( 'edit', [ 'record' => $order ] ),
						)
							->color( 'info' )
							->icon( 'heroicon-o-pencil' ),
						DeleteAction::make(),
					] ),
				] );
	}
}
