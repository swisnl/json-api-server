<?php

namespace Swis\JsonApi\Server\Traits;

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
use Swis\JsonApi\Server\Http\Resources\BaseApiResource;

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
            list($nestedInclude, $include) = $this->getNestedRelation($include);
            $included = null;
            if ($item->$include instanceof Collection) {
                $included = BaseApiResource::collection($item->$include);
                if ($nestedInclude) {
                    $relationshipResources[] = $this->includeCollectionRelationships($included, [$nestedInclude]);
                }
            } else {
                $included = BaseApiResource::make($item->$include);
                $object = str_before($nestedInclude, '.');
                if (isset($included->$object)) {
                    $relationshipResources[] = $this->handleIncludes($included, [$nestedInclude]);
                }
            }

            $relationshipResources[] = $included;
        }

        return $relationshipResources;
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

    /**
     * @param $include
     * @return array
     */
    protected function getNestedRelation($include): array
    {
        $nestedInclude = null;
        if (str_contains($include, '.')) {
            $nestedInclude = str_after($include, '.');
            $include = str_before($include, '.');
        }
        return array($nestedInclude, $include);
    }
}
