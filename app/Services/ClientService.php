<?php
namespace App\Services;

use App\Models\Client;

class ClientService
{
    public function all()
    {
        return Client::orderBy('id', 'desc')->get();
    }
    public function show($id)
    {
        $data = Client::where('id',$id)->first();
        return $data;
    }

    public function create(array $data)
    {
        $data = Client::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'contact_person'=>$data['contact_person'],
        ]);
        return $data;
    }
    public function update(array $data,$id)
    {
        $client = Client::findOrFail($id);

        $client->update([
            'name'           => $data['name'] ?? $client->name,
            'email'          => $data['email'] ?? $client->email,
            'contact_person' => $data['contact_person'] ?? $client->contact_person,
        ]);

        return $client;
    }
    public function delete($id)
    {
        $client = Client::find($id);
        if($client !== null)
        {
            $client->delete();
        }
        return $client;
    }
}
