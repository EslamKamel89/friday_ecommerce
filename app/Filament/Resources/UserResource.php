<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Livewire\Component as Livewire;

class UserResource extends Resource {
	protected static ?string $model = User::class;

	protected static ?string $navigationIcon = 'heroicon-o-user-group';

	public static function form( Form $form ): Form {
		return $form
			->schema( [
				TextInput::make( 'name' )
					->required( fn( Livewire $livewire ): bool => $livewire->getResourcePageName() == 'create' )
					->maxLength( 255 ),
				TextInput::make( 'email' )
					->email()
					->required( fn( Livewire $livewire ): bool => $livewire->getResourcePageName() == 'create' )
					->unique( ignoreRecord: true )
					->maxLength( 255 ),
				DateTimePicker::make( 'email_verified_at' )
					->default( now() ),
				TextInput::make( 'password' )
					->password()
					->required( fn( Livewire $livewire ): bool => $livewire->getResourcePageName() == 'create' )
					->dehydrated( fn( $state ) => filled( $state ) )
					->maxLength( 255 ),
			] );
	}

	public static function table( Table $table ): Table {
		return $table
			->columns( [
				TextColumn::make( 'name' )
					->searchable(),
				TextColumn::make( 'email' )
					->searchable(),
				TextColumn::make( 'email_verified_at' )
					->dateTime()
					->toggleable( isToggledHiddenByDefault: true )
					->sortable(),
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
				] ),
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
			'index' => Pages\ListUsers::route( '/' ),
			'create' => Pages\CreateUser::route( '/create' ),
			'edit' => Pages\EditUser::route( '/{record}/edit' ),
		];
	}
}
