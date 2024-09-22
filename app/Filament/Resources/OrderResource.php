<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource {
	protected static ?string $model = Order::class;

	protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
	protected static ?int $navigationSort = 5;

	public static function form( Form $form ): Form {
		return $form
			->schema( [ 
				Group::make()->schema( [ 
					Section::make( 'Order Information' )
						->schema( [ 
							Select::make( 'user_id' )
								->label( 'Customer' )
								->relationship( 'user', 'name' )
								->searchable()
								->preload()
								->required()
								->columnSpanFull(),
							ToggleButtons::make( 'payment_method' )
								->options( [ 
									'stripe' => 'Stripe',
									'COD' => 'Cash On Delivery',
								] )
								->inline()
								->default( 'stripe' )
								->colors( [ 
									'stripe' => 'danger',
									'COD' => 'primary',
								] )
								->icons( [ 
									'stripe' => 'heroicon-o-credit-card',
									'COD' => 'heroicon-o-currency-dollar',
								] )
								->required()
								->columnSpanFull(),
							ToggleButtons::make( 'payment_status' )
								->options( [ 
									'pending' => 'Pending',
									'paid' => 'Paid',
									'failed' => 'Failed',
								] )
								->colors( [ 
									'pending' => 'danger',
									'paid' => 'primary',
									'failed' => 'success',
								] )->icons( [ 
										'pending' => 'heroicon-o-clock',
										'paid' => 'heroicon-o-check',
										'failed' => 'heroicon-o-exclamation-triangle',
									] )
								->inline()
								->default( 'pending' )
								->required()
								->columnSpanFull(),
							ToggleButtons::make( 'status' )
								->options( [ 
									'new' => 'new',
									'processing' => 'processing',
									'shipped' => 'shipped',
									'delivered' => 'delivered',
									'canceled' => 'canceled',
								] )
								->colors( [ 
									'new' => 'danger',
									'processing' => 'info',
									'shipped' => 'primary',
									'delivered' => 'success',
									'canceled' => 'warning',
								] )
								->icons( [ 
									'new' => 'heroicon-o-plus',
									'processing' => 'heroicon-o-arrow-up-tray',
									'shipped' => 'heroicon-o-truck',
									'delivered' => 'heroicon-o-user-circle',
									'canceled' => 'heroicon-o-archive-box-x-mark',
								] )
								->inline()
								->default( 'new' )
								->required()
								->columnSpanFull(),
							TextInput::make( 'currency' )
								->maxLength( 255 )
								->default( null ),
							// TextInput::make( 'shipping_amount' )
							// 	->numeric()
							// 	->default( null ),
							Select::make( 'shipping_method' )
								->options( [ 
									'fedex' => 'Fedex',
									'ups' => 'UPS',
									'dhl' => 'DHL',
									'usps' => 'USPS',
								] )
								->required()
								->default( 'fedex' ),
							Textarea::make( 'notes' )
								->columnSpanFull(),
						] )->columns( 2 )
				] )->columnSpan( 3 ),
				//!
				Group::make()
					->schema( [ 
						Section::make( 'Order Items' )
							->schema( [ 
								Repeater::make( 'orderItems' )
									->relationship()
									->schema( [ 
										Select::make( 'product_id' )
											->relationship( 'product', 'name' )
											->searchable()
											->preload()
											->required()
											->distinct()
											->disableOptionsWhenSelectedInSiblingRepeaterItems()
											->reactive()
											->afterStateUpdated( function ($state, Set $set, ) {
												$set( 'unit_amount', Product::find( $state )?->price ?? 0 );
											} )
											->afterStateUpdated( function ($state, Set $set, Get $get) {
												$set( 'total_amount', $get( 'unit_amount' ) * $get( 'quantity' ) );
											} )
											->columnSpan( 4 ),
										TextInput::make( 'quantity' )
											->numeric()
											->required()
											->default( 1 )
											->minValue( 1 )
											->columnSpan( 2 )
											->reactive()
											->afterStateUpdated( function ($state, Set $set, Get $get) {
												$set( 'total_amount', $get( 'unit_amount' ) * $get( 'quantity' ) );
											} ),
										TextInput::make( 'unit_amount' )
											->numeric()
											->required()
											->disabled()
											->dehydrated()
											->columnSpan( 3 ),
										TextInput::make( 'total_amount' )
											->numeric()
											->required()
											->columnSpan( 3 ),
									] )->columns( 12 ),
								Placeholder::make( 'grand_total_placeholder' )
									->label( 'Grand Total' )
									->content( function (Get $get, Set $set) {
										$total = 0;
										$repeaters = $get( 'orderItems' );
										if ( $repeaters == null ) {
											return $total;
										}
										foreach ( $repeaters as $key => $repeater ) {
											// dump( $key, $repeater, $repeater['total_amount'] );
											$total += $repeater['total_amount'];
										}
										$set( 'grand_total', $total );
										return $total;
									} ),
								Hidden::make( 'grand_total' )
									->default( 0 ),
							] )
					] )->columnSpan( 3 )
			] )->columns( 3 );
	}

	public static function table( Table $table ): Table {
		return $table
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
			->actions( [ 
				ActionGroup::make( [ 
					Tables\Actions\ViewAction::make(),
					Tables\Actions\EditAction::make(),
					Tables\Actions\DeleteAction::make(),

				] )
			] )
			->bulkActions( [ 
				Tables\Actions\BulkActionGroup::make( [ 
					Tables\Actions\DeleteBulkAction::make(),
				] ),
			] );
	}

	public static function getRelations(): array {
		return [ 
			AddressRelationManager::class,
		];
	}

	public static function getNavigationBadge(): ?string {
		return static::getModel()::count();
	}

	public static function getNavigationBadgeColor(): string|array|null {
		return static::getModel()::count() < 10 ? 'danger' : 'success';
	}
	public static function getPages(): array {
		return [ 
			'index' => Pages\ListOrders::route( '/' ),
			'create' => Pages\CreateOrder::route( '/create' ),
			'view' => Pages\ViewOrder::route( '/{record}' ),
			'edit' => Pages\EditOrder::route( '/{record}/edit' ),
		];
	}
}
