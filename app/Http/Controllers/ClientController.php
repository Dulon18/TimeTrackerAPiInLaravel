<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Stmt\TryCatch;

class ClientController extends Controller
{
    protected ClientService $clientService;
    protected ResponseService  $response;
    public function __construct(ClientService $clientService,ResponseService  $response) {
        $this->clientService = $clientService;
        $this->response = $response;
    }
    public function list()
    {
        try {
            $clientData = $this->clientService->all()->map(function ($client) {
                return [
                    'id'=>$client->id,
                    'name'=>$client->name,
                    'email'=>$client->email,
                    'contactPerson'=>$client->contact_person,
                ];
            });
            return $this->response->successResponse($clientData, 'Client List');
        } catch (\Throwable $th) {
            return $this->response->errorResponse($th->getTraceAsString(),  $th->getCode() ?: 500);
        }
    }

    public function show($id)
    {
        try {
            $clientData = $this->clientService->show($id);
            if($clientData == null)
            {
                $response = "Data is not Found";
                return $this->response->errorResponse($response,$clientData);
            }
            $client = [
                'name'=>$clientData->name,
                'email'=>$clientData->email,
                'contactPerson'=>$clientData->contact_person,
            ];
            return $this->response->successResponse($client, 'Client Info retreived  successfully');
        } catch (\Throwable $th) {
            return $this->response->errorResponse($th->getTraceAsString(),  $th->getCode() ?: 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required',
                'contact_person' => 'required',
            ]);
            $client = $this->clientService->create($validated);
            $client =[
                'clientName'=>$client->name,
                'clientEmail'=>$client->email,
                'clientContactPerson'=>$client->email,
            ];
            return $this->response->successResponse($client, 'Client registered successfully');
         }
         catch (ValidationException $e) {
                return $this->response->validationError($e->errors());}
        catch (\Throwable $e) {
            return $this->response->errorResponse($e->getMessage(), 500);
        }
    }
    public function update(Request $request,$id)
    {
        try {

            $client = $this->clientService->update($request->all(),$id);
            $client =[
                'clientName'=>$client->name,
                'clientEmail'=>$client->email,
                'clientContactPerson'=>$client->email,
            ];
            return $this->response->successResponse($client, 'Client Info Update successfully');
         }
        catch (\Throwable $e) {
            return $this->response->errorResponse($e->getMessage(), 500);
        }
    }
    public function delete($id)
    {
        try {
            $client = $this->clientService->delete($id);
            if($client == null)
            {
                $response = "Data is not Found";
                return $this->response->errorResponse($response,$client);
            }
            return $this->response->successResponse($client, 'Client info deleted successfully');
         }
        catch (\Throwable $e) {
            return $this->response->errorResponse($e->getMessage(), 500);
        }
    }
}
