<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class ProjectController extends Controller
{

    /**
     * @var ProjectRepository
     */
    private $repository;

    /**
     * @var ProjectService
     */
    private $service;

    /**
     * @param ProjectRepository $repository
     * @param ProjectService $service
     */
    public function __construct(ProjectRepository $repository, ProjectService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $userId = Authorizer::getResourceOwnerId();
        return $this->service->all($userId);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

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
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        if (!$this->checkProjectPermission($id))
        {
            return ['error' => 'Access Forbidden!'];
        }

        return $this->service->find($id);
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
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if ( !$this->checkProjectOwner($id) )
        {
            return ['error' => 'Access Forbidden!'];
        }
        return $this->service->update($request->all(), $id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if ( !$this->checkProjectOwner($id) )
        {
            return ['error' => 'Access Forbidden!'];
        }

        $retorno = $this->service->delete($id);

        if( $retorno === true ){

            return 'Deletado';
        }

        return $retorno;
    }

    /**
     * @param $projectId
     * @return mixed
     */
    private function checkProjectOwner($projectId)
    {
        $userId =  Authorizer::getResourceOwnerId();

        return $this->service->isOwner($projectId, $userId);
    }

    private function checkProjectMember($projectId)
    {
        $userId =  Authorizer::getResourceOwnerId();

        return $this->service->isMember($projectId, $userId);
    }

    private function checkProjectPermission($projectId)
    {
        if ( $this->checkProjectOwner($projectId) or $this->checkProjectMember($projectId) )
        {
            return true;
        }

        return false;
    }
}
