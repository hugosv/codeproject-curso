<?php

namespace CodeProject\Services;


use CodeProject\Repositories\ClientRepository;
use CodeProject\Validators\ClientValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class ClientService
 * @package CodeProject\Services
 */
class ClientService
{

    /**
     * @var ClientRepository
     */
    protected $repository;

    /**
     * @var ClientValidator
     */
    protected $validator;

    /**
     * @param ClientRepository $repository
     * @param ClientValidator $validator
     */
    public function __construct(ClientRepository $repository, ClientValidator $validator)
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

        } catch ( ModelNotFoundException $e ) {

            return [
                'error' => true,
                'message' => 'Client does not exist'
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
                'message' => 'The client does not exist',
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
                    $message = 'The client does not exist';
                    break;

                case 'Illuminate\Database\QueryException':
                    $message = 'The client can not be deleted';
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