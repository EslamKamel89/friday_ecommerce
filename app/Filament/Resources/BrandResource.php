<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class BrandResource extends Resource {
	protected static ?string $model = Brand::class;
	protected static ?string $recordTitleAttribute = 'name';
	protected static ?int $navigationSort = 3;

	protected static ?string $navigationIcon = 'heroicon-o-globe-europe-africa';

	public static function form( Form $form ): Form {
		return $form
			->schema( [ 
				Section::make( [ 
					Grid::make()->schema( [ 
						TextInput::make( 'name' )
							->required()
							->live( true )
							->afterStateUpdated(
								fn( string $operation, string $state, Set $set )
								=> $operation == 'create' ? $set( 'slug', Str::slug( $state ) ) : null )
							->maxLength( 255 ),
						TextInput::make( 'slug' )
							->required()
							->unique( ignoreRecord: true )
							->maxLength( 255 ),
						FileUpload::make( 'image' )
							->directory( 'brands' )
							->image(),
						Toggle::make( 'is_active' )
							->default( true )
							->required(),
					] ),
				] ),
			] );
	}

	public static function table( Table $table ): Table {
		return $table
			->columns( [ 
				ImageColumn::make( 'image' )->circular(),
				TextColumn::make( 'name' )
					->searchable(),
				TextColumn::make( 'slug' )
					->searchable(),
				IconColumn::make( 'is_active' )
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
				//
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
			'index' => Pages\ListBrands::route( '/' ),
			'create' => Pages\CreateBrand::route( '/create' ),
			'edit' => Pages\EditBrand::route( '/{record}/edit' ),
			'view' => Pages\ViewBrand::route( '/{record}' ),
		];
	}
	public static function getGlobalSearchResultUrl( Model $record ): string {
		return route( 'filament.admin.resources.brands.view', [ 'record' => $record ] );
	}
}
