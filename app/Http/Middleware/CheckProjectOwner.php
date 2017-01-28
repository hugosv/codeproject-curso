<?php

namespace CodeProject\Http\Middleware;

use Closure;
use CodeProject\Services\ProjectService;
use Response;

class CheckProjectOwner
{
    /**
     * @var ProjectService
     */
    private $service;

    /**
     * @param ProjectRepository $repository
     */
    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $projectId = $request->route('id') ? $request->route('id') : $request->route('project');

        if ($this->service->checkProjectOwner($projectId) == false)
        {
            return Response::make(['error' => 'You are not the owner!' ], 403);
        }

        return $next($request);
    }
}