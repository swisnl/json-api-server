<?php

namespace Tests\TestClasses;

use Illuminate\Database\Eloquent\Model;
use Swis\LaravelApi\Models\ModelContract;

class TestModel extends Model implements ModelContract
{
    public $id = 0;
    protected $fillable = [
        'title', 'body',
    ];

    public function getRules($id = null): array
    {
        return [];
    }

    public function getRelationships(): array
    {
        return [];
    }

    public function getRepository(): string
    {
        return TestRepository::class;
    }

    public function getTranslatable()
    {
    }
}
