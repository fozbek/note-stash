<?php


namespace App\Repositories;


use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function findAll(?array $criteria = null);

    public function findOneBy(array $criteria);

    public function create(array $data);

    public function update(array $data, ?array $criteria = null);

    public function delete($id);
}
