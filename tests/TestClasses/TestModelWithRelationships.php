<?php

namespace Tests\TestClasses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestModelWithRelationships extends Model
{
    protected $fillable = [
        'title', 'body',
    ];

    public function testModels(): HasMany
    {
        return $this->hasMany(TestModel::class);
    }

    public function getRules($id = null): array
    {
        return [];
    }
}
