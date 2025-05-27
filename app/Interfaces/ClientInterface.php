<?php
namespace App\Interfaces;
interface ClientInterface
{
    public function all();
    public function show($id);
    public function create(array $data);
    public function update(array $data);
    public function delete(array $data);
}
