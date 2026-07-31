<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountType extends Model
{
    /** @use HasFactory<\Database\Factories\AccountTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'normal_balance',
        'is_active',
    ];

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
