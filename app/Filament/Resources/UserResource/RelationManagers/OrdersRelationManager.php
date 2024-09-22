<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use App\Models\Order;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;


class OrdersRelationManager extends RelationManager {
	protected static string $relationship = 'orders';

	public function form( Form $form ): Form {
		return $form
			->schema( [

			] );
	}

	public function table( Table $table ): Table {
		return $table
			->recordTitleAttribute( 'id' )
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
			] )
			->filters( [
				//
			] )
			->headerActions( [ 

				// Tables\Actions\CreateAction::make(),
				\Filament\Tables\Actions\Action::make( 'Create Order' )->url( OrderResource::getUrl( 'create' ) )
			] )
			->actions( [ 
				ActionGroup::make( [ 
					Action::make( 'view' )
						->url(
							fn( Order $order ): string => OrderResource::getUrl( 'view', parameters: [ 'record' => $order ] )
						)->color( 'success' )->icon( 'heroicon-o-eye' ),
					Action::make( 'Edit' )
						->url(
							fn( Order $order ): string => OrderResource::getUrl( 'edit', [ 'record' => $order ] )
						)->color( 'info' )->icon( 'heroicon-o-pencil' ),
					Tables\Actions\DeleteAction::make(),
				] )
			] )
			->bulkActions( [ 
				Tables\Actions\BulkActionGroup::make( [ 
					Tables\Actions\DeleteBulkAction::make(),
				] ),
			] );
	}
}
