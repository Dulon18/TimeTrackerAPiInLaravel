<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Services\CRUDService;
use App\Services\ResponseService;


class ProjectController extends Controller
{
    protected $crudService;
    protected ResponseService  $response;
    public function __construct(CRUDService $cRUDService,ResponseService  $response)
     {
        $this->crudService = $cRUDService;
        $this->response = $response;
    }
    public function list()
    {
        try {
            $projects = Project::with('client')->get()->map(function ($project) {
                return [
                    'id'=>$project->id,
                    'title'       => $project->title,
                    'description' => $project->description,
                    'clientName'  => $project->client->name ?? 'Unknown',
                    'status'      => $project->status,
                    'deadline'    => $project->deadline,
                ];
            });

            return $this->response->successResponse($projects, 'Project list retrieved successfully');
        } catch (\Throwable $e) {
            return $this->response->errorResponse($e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $project = $this->crudService->show(Project::class,$id);
            if($project == null)
            {
                $response = "Data is not Found";
                return $this->response->errorResponse($response,$project);
            }
            $project->load('client');
            $project = [
                'title'       => $project->title,
                'description' => $project->description,
                'clientName'  => $project->client->name ?? 'Unknown',
                'status'      => $project->status,
                'deadline'    => $project->deadline,
            ];
            return $this->response->successResponse($project, 'Project Info retreived  successfully');
        } catch (\Throwable $th) {
            return $this->response->errorResponse($th->getTraceAsString(),  $th->getCode() ?: 500);
        }
    }
    public function store(ProjectRequest $request)
    {
        try {
            $validated = $request->validated();
            $project = $this->crudService->create(Project::class, $validated);
            $project->load('client');

            $responseData = [
                'title'       => $project->title,
                'description' => $project->description,
                'clientName'  => $project->client->name ?? 'Unknown',
                'status'      => $project->status,
                'deadline'    => $project->deadline,
            ];
            return $this->response->successResponse($responseData, 'Project registered successfully');
        } catch (\Throwable $e) {
            return $this->response->errorResponse($e->getMessage(), 500);
        }
    }
    public function update(ProjectRequest $request,$id)
    {
        try {
            $validated = $request->validated();
            $project= $this->crudService->update(Project::class,$validated,$id);
            if($project == null)
            {
                $response = "Data is not Found";
                return $this->response->errorResponse($response,$project);
            }
            $project->load('client');
            $project = [
                'title'       => $project->title,
                'description' => $project->description,
                'clientName'  => $project->client->name ?? 'Unknown',
                'status'      => $project->status,
                'deadline'    => $project->deadline,
            ];
            return $this->response->successResponse($project, 'Project Info Update successfully');
         }
        catch (\Throwable $e) {
            return $this->response->errorResponse($e->getMessage(), 500);
        }
    }
    public function delete($id)
    {
        try {
            $project = $this->crudService->delete(Project::class,$id);
            if($project == null)
            {
                $response = "Data is not Found";
                return $this->response->errorResponse($response,$project);
            }
            return $this->response->successResponse($project, 'Project info deleted successfully');
         }
        catch (\Throwable $e) {
            return $this->response->errorResponse($e->getMessage(), 500);
        }
    }

}
