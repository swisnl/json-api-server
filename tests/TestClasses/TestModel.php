<?php

namespace Tests\TestClasses;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    protected $fillable = [
        'title', 'body',
    ];

    public $schema = TestSchema::class;
    public $repository = TestRepository::class;
}
