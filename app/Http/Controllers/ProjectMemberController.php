<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Services\ProjectService;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;
use CodeProject\Http\Controllers\Controller;

/**
 * Class ProjectMemberController
 * @package CodeProject\Http\Controllers
 */
class ProjectMemberController extends Controller
{

    /**
     * @var ProjectService
     */
    private $service;

    /**
     * @param ProjectService $service
     */
    public function __construct(ProjectService $service)
    {

        $this->service = $service;
    }

    /**
     * @param $projectId
     * @return array
     */
    public function index($projectId)
    {
        return $this->service->getMembers($projectId);
    }

    /**
     * @param $projectId
     * @param $memberId
     * @return array
     */
    public function store($projectId, $memberId)
    {
        return $this->service->addMember($projectId, $memberId);
    }

    /**
     * @param $projectId
     * @param $memberId
     * @return array
     */
    public function show($projectId, $memberId)
    {
        return $this->service->isMember($projectId, $memberId) ? ['message' => 'Is member' ] : [ 'message' => 'Is not member'];
    }

    /**
     * @param $projectId
     * @param $memberId
     * @return array
     */
    public function delete($projectId, $memberId)
    {
        return $this->service->removeMember($projectId, $memberId);
    }
}
