<?php

namespace Tests\TestClasses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Swis\LaravelApi\Models\ModelContract;

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

    public function getSchema(): string
    {
        return TestSchema::class;
    }

    public function getRepository(): string
    {
        return TestRepositoryWithRelationships::class;
    }

    public function getTranslatable()
    {
    }
}
