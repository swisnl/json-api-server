<?php
namespace Swis\LaravelApi\Models;

interface ModelContract
{
    public function getRelationships();
    public function getSchema();
    public function getRepository();
    public function getTranslatable();
    public function getRules($id = null);
}