<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SampleTranslation extends Model
{
    protected $table = 'sample_translations';

    public $fillable = [
        //TODO: Same as model's translatedAttributes
    ];
}
