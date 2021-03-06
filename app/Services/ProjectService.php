<?php

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;

use Prettus\Validator\Exceptions\ValidatorException;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\Factory as Storage;

use LucaDegasperi\OAuth2Server\Facades\Authorizer;

/**
 * Class ProjectService
 * @package CodeProject\Services
 */
class ProjectService
{

    /**
     * @var ProjectRepository
     */
    protected $repository;

    /**
     * @var ProjectValidator
     */
    protected $validator;

    /**
     * @param ProjectRepository $repository
     * @param ProjectValidator $validator
     * @param Filesystem $filesystem
     * @param Storage $storage
     */
    public function __construct(ProjectRepository $repository, ProjectValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function all($userId)
    {
        return $this->repository->with(['client', 'owner'])->findWhere(['owner_id' => $userId]);
    }

    /**
     * @param $id
     * @return array|mixed
     */
    public function find($id)
    {
        try {

            return $this->repository->with(['client', 'owner', 'notes'])->find($id);

        } catch ( \Exception $e )
        {

            if(get_class($e) == 'Illuminate\Database\Eloquent\ModelNotFoundException' )
            {
                return [
                    'error'   => true,
                    'message' => 'Project does not exist' . $id,
                ];
            }

        }
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        try {

            $this->validator->with($data)->passesOrFail();
            return $this->repository->create($data);

        } catch(ValidatorException $e) {

            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];

        }

    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id)
    {

        try {

            $this->validator->with($data)->passesOrFail();
            return $this->repository->update($data, $id);

        } catch(ValidatorException $e) {

            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];

        } catch ( \Exception $e ) {

            return [
                'error' => true,
                'message' => 'The project does not exist',
            ];
        }

    }

    /**
     * @param $id
     * @return array
     */
    public function delete($id)
    {
        try
        {
            $this->repository->skipPresenter()->find($id)->delete();
            return ['message' => 'Projeto removido com sucesso'];

        } catch (  \Exception $e ) {

            switch (get_class($e)) {

                case 'Illuminate\Database\Eloquent\ModelNotFoundException':
                    $message = 'The project does not exist';
                    break;

                case 'Illuminate\Database\QueryException':
                    $message = 'The project can not be deleted.';
                    break;

                default:
                    $message = $e->getMessage();
            }

            return [
                'error' => true,
                'message' => $message,
            ];

        }
    }

    /**
     * @param $projectId
     * @return array
     */
    public function getMembers($projectId)
    {
        if( count( $this->repository->findWhere(['id' => $projectId]) ) )
        {
            return $this->repository->find($projectId)->members;
        }

        return ['error' => 'Project does not exists'];

    }

    /**
     * @param $projectId
     * @param $userId
     * @return array
     */
    public function addMember($projectId, $userId)
    {
        try {

            $this->repository->find($projectId)->members()->attach($userId);
            return ['success' => true];

        } catch (\Exception $e) {

            return [
                "error" => true,
                "message" => $e->getMessage()
            ];

        }
    }

    /**
     * @param $projectId
     * @param $userId
     * @return array
     */
    public function removeMember($projectId, $userId)
    {
        try {

            $this->repository->find($projectId)->members()->detach($userId);
            return ['success' => true];

        } catch (\Exception $e) {
            return [
                "error" => true,
                "message" => $e->getMessage()
            ];
        }
    }

    /**
     * @param $projectId
     * @return mixed
     */
    public function checkProjectOwner($projectId)
    {
        $userId =  Authorizer::getResourceOwnerId();

        return $this->repository->isOwner($projectId, $userId);
    }

    public function checkProjectMember($projectId)
    {
        $userId =  Authorizer::getResourceOwnerId();

        return $this->repository->hasMember($projectId, $userId);
    }

    public function checkProjectPermission($projectId)
    {
        if ( $this->checkProjectOwner($projectId) or $this->checkProjectMember($projectId) )
        {
            return true;
        }

        return false;
    }


}