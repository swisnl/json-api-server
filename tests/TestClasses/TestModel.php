<?php

namespace Tests\TestClasses;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    public $id = 0;
    protected $fillable = [
        'title', 'body',
    ];

    public function getRules($id = null): array
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
