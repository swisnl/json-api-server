<?php

namespace sample;

use Illuminate\Database\Eloquent\Model;
use Sample\Repositories\SampleRepository;
use Sample\Schemas\SampleSchema;

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

    public function getRelationships()
    {
        return ['samples1', 'samples2'];
    }

    public function getRules($id = null)
    {
        return [];
    }
}
