<?php

namespace App;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use Translatable;
    public $translationModel = 'SampleTranslation';

    public $fillable = [
        // TODO: Write down fillables
    ];

    public $hidden = [
            // TODO: Write down hidden
        ];

    public $translatedAttributes = [
        // TODO: Write down translatable values
    ];

    public function getRules($id = null): array
    {
        return [
        //TODO write down rules
       ];
    }
}
