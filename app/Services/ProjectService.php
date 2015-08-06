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

    /**
     * @param $id
     * @return array|mixed
     */
    public function find($id)
    {
        try {

            return $this->repository->find($id);

        } catch ( ModelNotFoundException $e )
        {

            return [
                'error'   => true,
                'message' => 'Project does not exist'
            ];

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
}