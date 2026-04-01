<?php

namespace App\Models;

use Database\Factories\ParticipantFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['name', 'phone', 'address', 'notes'])]
class Participant extends Model
{
    /** @use HasFactory<ParticipantFactory> */
    use HasFactory;

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return HasOne<Saving, $this>
     */
    public function savingRecord(): HasOne
    {
        return $this->hasOne(Saving::class);
    }

    /**
     * @return HasMany<SavingTransaction, $this>
     */
    public function savingTransactions(): HasMany
    {
        return $this->hasMany(SavingTransaction::class);
    }

    public function token(): HasOne
    {
        return $this->hasOne(ParticipantToken::class);
    }
}
