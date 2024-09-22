<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressRelationManager extends RelationManager {
	protected static string $relationship = 'address';

	public function form( Form $form ): Form {
		return $form
			->schema( [ 
				Group::make( [ 
					Section::make( 'User Info' )
						->schema( [ TextInput::make( 'first_name' )
							->maxLength( 255 )
							->default( null ),
							TextInput::make( 'last_name' )
								->maxLength( 255 )
								->default( null ),
							TextInput::make( 'phone' )
								->tel()
								->maxLength( 255 )
								->default( null ),
						] )->columnSpan( 4 ),
					Section::make( 'Address Info' )
						->schema( [ Textarea::make( 'street_address' )
							->columnSpanFull(),
							TextInput::make( 'city' )
								->maxLength( 255 )
								->default( null ),
							TextInput::make( 'state' )
								->maxLength( 255 )
								->default( null ),
							TextInput::make( 'zip_code' )
								->maxLength( 255 )
								->default( null ),
						] )->columnSpan( 8 )->columns( 3 ),
				] )->columnSpanFull()->columns( 12 ),
			] );
	}

	public function table( Table $table ): Table {
		return $table
			->recordTitleAttribute( 'street_address' )
			->columns( [ 
				Tables\Columns\TextColumn::make( 'order.id' )
					->numeric()
					->sortable(),
				Tables\Columns\TextColumn::make( 'first_name' )
					->searchable(),
				Tables\Columns\TextColumn::make( 'last_name' )
					->searchable(),
				Tables\Columns\TextColumn::make( 'phone' )
					->searchable(),
				Tables\Columns\TextColumn::make( 'city' )
					->searchable(),
				Tables\Columns\TextColumn::make( 'state' )
					->searchable(),
				Tables\Columns\TextColumn::make( 'zip_code' )
					->searchable(),
				Tables\Columns\TextColumn::make( 'created_at' )
					->dateTime()
					->sortable()
					->toggleable( isToggledHiddenByDefault: true ),
				Tables\Columns\TextColumn::make( 'updated_at' )
					->dateTime()
					->sortable()
					->toggleable( isToggledHiddenByDefault: true ),
			] )
			->filters( [
				//
			] )
			->headerActions( [ 
				Tables\Actions\CreateAction::make(),
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
}
