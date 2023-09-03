<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsersTeam extends Model
{
    use HasFactory, HasUuids;
            /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'team_role'
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}