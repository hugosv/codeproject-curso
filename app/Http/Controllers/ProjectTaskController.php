<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Services\ProjectTaskService;
use CodeProject\Repositories\ProjectTaskRepository;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;

class ProjectTaskController extends Controller
{

    /**
     * @var ProjectTaskRepository
     */
    private $repository;
    /**
     * @var ProjectTaskService
     */
    private $service;

    /**
     * @param ProjectTaskRepository $repository
     * @param ProjectTaskService $service
     */
    public function __construct(ProjectTaskRepository $repository, ProjectTaskService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param integer $id
     * @return Response
     */
    public function index( $id )
    {
        return $this->repository->findWhere(['project_id' => $id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param $taskId
     * @return Response
     */
    public function show($id, $taskId)
    {
        return $this->repository->findWhere(['project_id' => $id, 'id' => $taskId]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param $task
     * @return Response
     */
    public function update(Request $request, $task)
    {
        return $this->service->update($request->all(), $task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $task
     * @return Response
     * @internal param int $id
     */
    public function destroy( $task )
    {
        if( $this->repository->delete($task) )
        {
            return 'Deletado';
        }
    }
}
