<?php
namespace App\Interfaces;

interface CRUDInterface
{
    public function all($model);
    public function show($model,$id);
    public function create($model,array $data);
    public function update($model,array $data,$id);
    public function delete($model,$id);
}
