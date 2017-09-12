<?php

namespace Swis\sample;

use \Illuminate\Database\Eloquent\Model;
use Swis\sample\Repositories\SampleRepository;
use Swis\sample\Schemas\SampleSchema;

class Sample extends Model
{
    protected $fillable = [
        'title', 'body',
    ];

    protected $schema = SampleSchema::class;
    protected $repository = SampleRepository::class;
    protected $translatable = SampleTranslation::class;

    public function getSchema()
    {
        return $this->schema;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function getTranslatable()
    {
        return $this->translatable;
    }
}