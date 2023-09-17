<?php

namespace App\Models;

use App\Models\ProjectTask;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'name'
    ];

    public function projectTasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class);
    }
}
