<?php

namespace Swis\JsonApi\Server\Repositories;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function getModelRelationships(): array;

    public function makeModel(): Model;

    public function getModelName();

    public function setParameters($query);

    public function paginate($query);

    public function findById($id);

    public function update(array $data, $id);

    public function destroy($id);
}
