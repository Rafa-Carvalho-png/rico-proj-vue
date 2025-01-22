<?php

namespace App\Repository;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface AbstractRepositoryInterface
{
    public function insert(array $data): Model;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function find(int $id): Model;
    public function filter(array $filter): Collection;
    public function all(): Collection;
}
