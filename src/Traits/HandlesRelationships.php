<?php

namespace Swis\LaravelApi\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Resources\Json\ResourceCollection;
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
        BelongsToMany::class,
        BelongsTo::class,
    ];

    public function getRelationships($model): array//TODO: Skipt relaties die geen return type hebben
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

            if ($item->$include instanceof Collection) {
                $included = BaseApiResource::collection($item->$include);

                // Find nested relationship. For example: user->permissions->users
                $includedNestedRelationships =
                    $this->includeCollectionRelationships($included, $this->findNestedRelationships($includes, $include));
            } else {
                $included = BaseApiResource::make($item->$include);
                // Find nested relationship. For example: user->permissions->users
                $includedNestedRelationships =
                    $this->includeRelationships($included, $this->findNestedRelationships($includes, $include));
            }

            if ($included->toArray('') !== []) {
                $relationshipResources[] = $included;
            }

            if ($includedNestedRelationships !== []) {
                $relationshipResources[] = $includedNestedRelationships;
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

        foreach ($array as $items) {
            if ($items instanceof ResourceCollection || is_array($items)) {
                foreach ($items as $item) {
                    $mergedArray[] = $item;
                }
                continue;
            }

            $mergedArray[] = $items;
        }

        $mergedArray = $this->removeDuplicates($mergedArray);

        return $mergedArray;
    }

    protected function removeDuplicates($items)
    {
        $tempArray = [];
        $relations = [];

        foreach ($items as $item) {
            if (!isset($item->resource)) {
                continue;
            }

            if (in_array($item->attributesToArray(), $tempArray, true)) {
                continue;
            }

            $tempArray[] = $item->attributesToArray();
            $relations[] = $item;
        }

        return $relations;
    }
}
