<?php

namespace Tests\TestClasses;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed title
 * @property string body
 */
class TestModel extends Model
{
    public $id = 0;
    public $timestamps = false;
    protected $fillable = [
        'title', 'body',
    ];

    public function getRules($id = null): array
    {
        return [];
    }
}
