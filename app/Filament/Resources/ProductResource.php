<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource {
	protected static ?string $model = Product::class;
	protected static ?string $recordTitleAttribute = 'name';
	protected static ?int $navigationSort = 4;

	protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

	public static function form( Form $form ): Form {
		return $form
			->schema( [ 
				//!
				Group::make()->schema( [ 
					Section::make( 'Product Details' )->schema( [ 
						TextInput::make( 'name' )
							->required()
							->live( true )
							->afterStateUpdated(
								fn( string $operation, string $state, Set $set ) => $operation == 'create' ? $set( 'slug', Str::slug( $state ) ) : null )
							->maxLength( 255 ),
						TextInput::make( 'slug' )
							->required()
							->maxLength( 255 ),
						MarkdownEditor::make( 'description' )
							->fileAttachmentsDirectory( 'products' )
							->columnSpanFull(),
					] )->columns( 2 ),
					//!
					Section::make( 'Images' )
						->schema( [ 
							FileUpload::make( 'images' )
								->multiple()
								->maxFiles( 5 )
								->reorderable()
								->columnSpanFull(),
						] )->columns( 2 ),
				] )->columnSpan( 2 ),
				//!
				Group::make()->schema( [ 
					Section::make( 'Price' )
						->schema( [ 
							TextInput::make( 'price' )
								->required()
								->numeric()
								->prefix( '$' ),
						] )->columnSpanFull(),
					Section::make( 'Associations' )
						->schema( [ 
							Select::make( 'category_id' )
								->relationship( 'category', 'name' )
								->searchable()
								->preload()
								->required(),
							Select::make( 'brand_id' )
								->relationship( 'brand', 'name' )
								->searchable()
								->preload()
								->required(),
						] )->columnSpanFull(),
					Section::make( 'Status' )->schema( [ 
						Toggle::make( 'in_stock' )
							->label( 'In Stock' )
							->default( true )
							->required(),
						Toggle::make( 'is_active' )
							->label( 'Active' )
							->default( true )
							->required(),
						Toggle::make( 'is_featured' )
							->label( 'Featured' )
							->default( false )
							->required(),
						Toggle::make( 'on_sale' )
							->label( 'On Sale' )
							->default( false )
							->required(),
					] )->columnSpanFull(),
				] )->columnSpan( 1 ),
			] )->columns( 3 );
	}

	public static function table( Table $table ): Table {
		return $table
			->columns( [ 
				ImageColumn::make( 'images' )
					->circular()
					->stacked(),
				TextColumn::make( 'category.name' )
					->numeric()
					->sortable(),
				TextColumn::make( 'brand.name' )
					->numeric()
					->sortable(),
				TextColumn::make( 'name' )
					->searchable()
					->wrap(),
				TextColumn::make( 'slug' )
					->searchable()
					->toggleable( isToggledHiddenByDefault: true ),
				TextColumn::make( 'price' )
					->money()
					->sortable(),
				IconColumn::make( 'is_active' )
					->boolean(),
				IconColumn::make( 'is_featured' )
					->boolean(),
				IconColumn::make( 'on_sale' )
					->boolean(),
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
				SelectFilter::make( 'category' )
					->relationship( 'category', 'name' )
					->preload()
					->multiple(),
				SelectFilter::make( 'brand' )
					->relationship( 'brand', 'name' )
					->preload()
					->multiple(),
			] )
			->actions( [ 
				ActionGroup::make( [ 
					Tables\Actions\EditAction::make(),
					Tables\Actions\ViewAction::make(),
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
			//
		];
	}

	public static function getPages(): array {
		return [ 
			'index' => Pages\ListProducts::route( '/' ),
			'create' => Pages\CreateProduct::route( '/create' ),
			'edit' => Pages\EditProduct::route( '/{record}/edit' ),
			'view' => Pages\ViewProduct::route( '/{record}' ),
		];
	}
	public static function getGlobalSearchResultUrl( Model $record ): string {
		return route( 'filament.admin.resources.products.view', [ 'record' => $record ] );
	}
}
