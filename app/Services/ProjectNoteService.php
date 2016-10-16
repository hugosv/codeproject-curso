<?php

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectNoteRepository;
use CodeProject\Validators\ProjectNoteValidator;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class ProjectNoteService
 * @package CodeProject\Services
 */
class ProjectNoteService
{

    /**
     * @var ProjectNoteRepository
     */
    protected $repository;

    /**
     * @var ProjectNoteValidator
     */
    protected $validator;

    /**
     * @param ProjectNoteRepository $repository
     * @param ProjectNoteValidator $validator
     */
    public function __construct(ProjectNoteRepository $repository, ProjectNoteValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
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
                'message' => $e->getMessageBag(),
            ];

        }

    }

    /**
     * @param $projectId
     * @return mixed
     */
    public function all($projectId)
    {
        return $this->repository->findWhere( ['project_id' => $projectId] );
    }

    /**
     * @param $projectId
     * @param $noteId
     * @return array|mixed
     */
    public function find($projectId, $noteId)
    {
        try {

            return $this->repository->findWhere(['project_id' => $projectId, 'id' => $noteId])->first();

        } catch ( \Exception $e )
        {
            return [
                'error'   => true,
                'message' => 'Project does not exist'
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

        }

    }

    /**
     * @param $projectId
     * @param $noteId
     */
    public function delete($projectId, $noteId)
    {
        try {

            return $this->repository->findWhere(['project_id' => $projectId, 'id' => $noteId])->first()->delete() ? 'Deletado' : 'Não foi possível deletar';

        }
        catch (\Exception $e)
        {
            return [
                'error' => true,
                'message' => 'An error ocurred on delete'
            ];
        }


    }
}