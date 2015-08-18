<?php

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;
use Prettus\Validator\Exceptions\ValidatorException;

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
     */
    public function __construct(ProjectRepository $repository, ProjectValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function all()
    {
        return $this->repository->with(['client', 'owner'])->all($columns = [ 'id', 'name', 'description', 'client_id', 'owner_id']);
    }

    /**
     * @param $id
     * @return array|mixed
     */
    public function find($id)
    {
        try {

            return $this->repository->with(['client', 'owner', 'notes'])->findWhere(['id' => $id]);

        } catch ( \Exception $e )
        {

            if(get_class($e) == 'Illuminate\Database\Eloquent\ModelNotFoundException' )
            {
                return [
                    'error'   => true,
                    'message' => 'Project does not exist' + $message ,
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
            return $this->repository->find($id)->delete();

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
     * @param $userId
     * @return bool
     */
    public function isOwner($projectId, $userId)
    {
        if ( count( $this->repository->findWhere(['id' => $projectId, 'owner_id' => $userId]) ) )
        {
            return true;
        }

        return false;
    }

    /**
     * @param $projectId
     * @return array
     */
    public function getMembers($projectId)
    {
        if( count( $this->repository->findWhere(['id' => $projectId]) ) )
        {
            return $this->repository->find($projectId)->members->all();
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
     * @param $userId
     * @return array
     */
    public function isMember($projectId, $userId)
    {
        try {

            return $this->repository->find($projectId)->members()->find($userId) ? true : false;

        } catch (\Exception $e) {

            return [
                "error" => true,
                "message" => $e->getMessage()
            ];

        }
    }

}