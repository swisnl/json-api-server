<?php

namespace Tests\TestClasses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestModelWithRelationships extends Model
{
    protected $fillable = [
        'title', 'body',
    ];

    public $schema = TestSchema::class;
    public $repository = TestRepositoryWithRelationships::class;

    public function testModels(): HasMany
    {
        return $this->hasMany(TestModel::class);
    }
}
