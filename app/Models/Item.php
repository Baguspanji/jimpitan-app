<?php

namespace App\Models;

use Database\Factories\ItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['category_id', 'name', 'unit', 'weekly_price', 'type', 'bonus_desc'])]
class Item extends Model
{
    /** @use HasFactory<ItemFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Items included in this package.
     *
     * @return BelongsToMany<Item, $this>
     */
    public function packageItems(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'item_package', 'package_id', 'item_id');
    }

    /**
     * Packages that include this item.
     *
     * @return BelongsToMany<Item, $this>
     */
    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'item_package', 'item_id', 'package_id');
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isPackage(): bool
    {
        return $this->type === 'package';
    }
}
