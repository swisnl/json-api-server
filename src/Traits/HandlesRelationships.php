<?php

namespace Swis\LaravelApi\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HandlesRelationships
{
    protected $relationshipTypes = [
        MorphToMany::class,
        HasMany::class,
        HasManyThrough::class,
        HasOne::class,
        HasOneOrMany::class,
        MorphMany::class,
        MorphOne::class,
        MorphPivot::class,
        MorphTo::class,
    ];

    public function getRelationships(Model $model): array//TODO: Skipt dit geen relaties die geen return type hebben?
    {
        $relations = [];

        if ($model->getRelations()) {
            return $model->getRelations();
        }

        //TODO: ook op permissies checken welke relaties ze mogen zien.
        $class = new \ReflectionClass(get_class($model));
        foreach ($class->getMethods() as $method) {
            $returnType = $method->getReturnType();

            //TODO: niet alleen op ! checken maar beter afvangen
            if (!$returnType) {
                continue;
            }

            if (in_array(pathinfo($returnType)['basename'], $this->relationshipTypes)) {
                $relations[] = $method->getName();
            }
        }

        return $relations;
    }
}
