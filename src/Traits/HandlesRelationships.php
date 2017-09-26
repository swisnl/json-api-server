<?php

namespace Swis\LaravelApi\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Swis\LaravelApi\Http\Resources\BaseApiResource;
use Swis\LaravelApi\Models\ModelContract;

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

    public function getRelationships($model): array//TODO: Skipt dit geen relaties die geen return type hebben?
    {
        $relations = [];

        if ($model instanceof ModelContract) {
            return $model->getRelationships();
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

    public function includeRelationships($item, $includes)
    {
        $relationshipResources = $this->handleIncludes($item, $includes);

        return $this->mergeInnerArrays($relationshipResources);
    }

    public function includeCollectionRelationships($items, $includes)
    {
        $relationshipResources = [];

        foreach ($items as $item) {
            $relationshipResources = array_merge($relationshipResources, $this->handleIncludes($item, $includes));
        }

        return $this->mergeInnerArrays($relationshipResources);
    }

    /**
     * Loops through all included tags. It checks for each include if there is a nested include.
     * And also runs that recursively through includeCollectionRelationships.
     *
     * @param $item
     * @param $includes
     *
     * @return array
     */
    protected function handleIncludes($item, $includes)
    {
        $relationshipResources = [];

        foreach ($includes as $include) {
            if (!$item->$include) {
                continue;
            }

            $included = BaseApiResource::collection($item->$include);

            // Find nested relationship. For example: user->permissions->users
            $includedRelationships =
                $this->includeCollectionRelationships($included, $this->findNestedRelationships($includes, $include));

            if ($includedRelationships !== []) {
                $relationshipResources[] = $includedRelationships;
            }

            if ($included->toArray('') !== []) {
                $relationshipResources[] = $included;
            }
        }

        return $relationshipResources;
    }

    /**
     * Checks if there are nested includes. For example: permissions.users.
     *
     * @param $includes
     * @param $include
     *
     * @return array
     */
    protected function findNestedRelationships($includes, $include)
    {
        $nestedRelationships = [];

        foreach ($includes as $value) {
            if (0 === strpos($value, $include.'.')) {
                $nestedRelationships[] = str_replace($include.'.', '', $value);
            }
        }

        return $nestedRelationships;
    }

    /**
     * Merges all arrays to be single level.
     *
     * @param $array
     *
     * @return array
     */
    protected function mergeInnerArrays($array)
    {
        $mergedArray = [];

        foreach ($array as $items) { //TODO: Teveel foreaches, zoek betere manier om collections te mergen naar array
            foreach ($items as $item) {
                $mergedArray[] = $item;
            }
        }

        $mergedArray = $this->removeDuplicates($mergedArray);

        return $mergedArray;
    }

    protected function removeDuplicates($items)
    {
        $tempArray = [];
        $relations = [];

        foreach ($items as $item) {
            $type = class_basename($item->resource);
            $id = $item->id;

            if (in_array([$type => $id], $tempArray, true)) {
                continue;
            }

            $tempArray[] = [$type => $id];
            $relations[] = $item;
        }

        return $relations;
    }
}
