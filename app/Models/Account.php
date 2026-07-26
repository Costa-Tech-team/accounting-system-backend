<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    /** @use HasFactory<\Database\Factories\AccountFactory> */
    use HasFactory;

    protected $fillable = [
        'account_type_id',
        'parent_id',
        'code',
        'name',
        'is_active',
        'is_operable',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id')->orderBy('code');
    }


    public function childrenRecursive(): HasMany
    {
        return $this->children()
            ->with([
                'childrenRecursive',
            ]);
    }

    public function journalEntryLines(): HasMany
    {
        return $this->hasMany(JournalEntryLine::class);
    }
}
