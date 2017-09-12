<?php

namespace Swis\tests\TestClasses;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    public $schema = TestSchema::class;
    public $repository = TestRepository::class;
}
