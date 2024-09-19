<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class CategoryResource extends Resource {
	protected static ?string $model = Category::class;

	protected static ?string $navigationIcon = 'heroicon-o-tag';

	public static function form( Form $form ): Form {
		return $form
			->schema( [
				Section::make( [
					Grid::make()->schema( [
						TextInput::make( 'name' )
							->required()
							->afterStateUpdated( function (string $state, Set $set, string $operation) {
								$operation == 'create' ? $set( 'slug', Str::slug( $state ) ) : null;
							} )
							->live( true )
							->maxLength( 255 ),
						TextInput::make( 'slug' )
							->required()
							->unique( 'categories', 'slug', ignoreRecord: true, )
							// ->disabled()
							->maxLength( 255 ),
					] ),
					FileUpload::make( 'image' )
						->directory( 'categories' )
						->image(),
					Toggle::make( 'is_active' )
						->required()
						->default( true ),
				] ),
			] );
	}

	public static function table( Table $table ): Table {
		return $table
			->columns( [
				ImageColumn::make( 'image' )->circular(),
				TextColumn::make( 'name' )
					->searchable(),
				Tables\Columns\TextColumn::make( 'slug' )
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
					Tables\Actions\DeleteAction::make(),
					Tables\Actions\ViewAction::make(),
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
			'index' => Pages\ListCategories::route( '/' ),
			'create' => Pages\CreateCategory::route( '/create' ),
			'edit' => Pages\EditCategory::route( '/{record}/edit' ),
		];
	}
}
