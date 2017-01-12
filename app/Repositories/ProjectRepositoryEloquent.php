<?php

namespace CodeProject\Repositories;

use CodeProject\Presenters\ProjectPresenter;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use CodeProject\Entities\Project;

/**
 * Class ProjectRepositoryEloquent
 * @package namespace CodeProject\Repositories;
 */
class ProjectRepositoryEloquent extends BaseRepository implements ProjectRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Project::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria( app(RequestCriteria::class) );
    }

    /**
     * @param $projectId
     * @param $userId
     * @return bool
     */
    public function isOwner($projectId, $userId)
    {
        if (count($this->skipPresenter()->findWhere(['id' => $projectId, 'owner_id' => $userId] ) ))
        {
            return true;
        }

        return false;
    }

    /**
     * @param $projectId
     * @param $userId
     * @return array
     */
    public function hasMember($projectId, $userId)
    {
        return $this->skipPresenter()->find($projectId)->members()->find($userId) ? true : false;
    }

    public function findWithOwnerAndMember($userId)
    {
        return $this->scopeQuery(function ($query) use($userId)
        {
            return $query->select('projects.*')
                ->join('project_members', 'project_members.project_id', '=', 'projects.id')
                ->where('project_members.member_id', '=', $userId)
                ->union($this->model->query()->getQuery()->where('owner_id', '=', $userId));
        })->all();
    }

    /**
     * @return mixed
     */
    public function presenter()
    {
        return projectPresenter::class;
    }

}