<?php


namespace App\Services;


interface AppServiceInterface
{
    public function getAll();

    public function getPaginated($search = null, $perPage = 15);

    public function getById($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);
}
