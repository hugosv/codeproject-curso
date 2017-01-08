<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectMemberRepository;
use CodeProject\Services\ProjectMemberService;
use Illuminate\Http\Request;

/**
 * Class ProjectMemberController
 * @package CodeProject\Http\Controllers
 */
class ProjectMemberController extends Controller
{

    /**
     * @var ProjectMemberService
     */
    private $service;
    /**
     * @var ProjectMemberRepository
     */
    private $repository;

    /**
     * @param ProjectMemberRepository $repository
     * @param ProjectMemberService $service
     */
    public function __construct(ProjectMemberRepository $repository, ProjectMemberService $service)
    {

        $this->service = $service;
        $this->repository = $repository;
        $this->middleware('check-project-owner', ['except' => ['index', 'show']]);
        $this->middleware('check-project-permission', ['except' => ['store', 'destroy']]);
    }

    /**
     * @param $projectId
     * @return array
     */
    public function index($projectId)
    {
        return $this->repository->findWhere(['project_id' => $projectId]);
    }

    /**
     * @param Request $request
     * @param $projectId
     * @return array|mixed
     */
    public function store(Request $request, $projectId)
    {
        $data = $request->all();
        $data['project_id'] = $projectId;

        return $this->service->create($data);
    }

    /**
     * @param $projectId
     * @param $memberId
     * @return array
     */
    public function show($projectId, $memberId)
    {
        return $this->repository->find($memberId);
    }

    /**
     * @param $projectId
     * @param $memberId
     * @return array
     */
    public function destroy($projectId, $memberId)
    {
        $this->service->delete($memberId);
    }
}
