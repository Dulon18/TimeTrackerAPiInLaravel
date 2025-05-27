<?php
namespace App\Services;

use App\Interfaces\CRUDInterface;

class CRUDService implements CRUDInterface
{
    public function all($model)
    {
        return $model::orderBy('id', 'desc')->get();
    }
    public function show($model,$id)
    {
        $data = $model::where('id',$id)->first();
        return $data;
    }
    public function create($model,array $data)
    {
        return $model::create($data);
    }
    public function update($model,array $attributes,$id)
    {
        $data = $model::find($id);
        if ($data !== null) {
            $data->update($attributes);
        }
        return $data;
    }
    public function delete($model,$id)
    {
        $data = $model::find($id);
        if ($data !== null) {
            $data->delete();
        }
        return $data;
    }
}
