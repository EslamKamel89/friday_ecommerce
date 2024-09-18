<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model {
	use HasFactory;
	protected $fillable = [
		'cateogry_id',
		'brand_id',
		'name',
		'slug',
		'images',
		'description',
		'price',
		'is_active',
		'is_featured',
		'on_sale',
	];

	protected $casts = [
		'images' => 'array',
	];

	public function category(): BelongsTo {
		return $this->belognsTo( Category::class);
	}
	public function brand(): BelongsTo {
		return $this->belongsTo( Brand::class);
	}

	public function orderItems(): HasMany {
		return $this->hasMany( OrderItem::class);
	}

}
