<?php

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectMemberRepository;
use CodeProject\Validators\ProjectMemberValidator;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class ProjectService
 * @package CodeProject\Services
 */
class ProjectMemberService
{

    /**
     * @var ProjectMemberRepository
     */
    protected $repository;

    /**
     * @var ProjectMemberValidator
     */
    protected $validator;

    /**
     * @param ProjectMemberRepository $repository
     * @param ProjectMemberValidator $validator
     */
    public function __construct(ProjectMemberRepository $repository, ProjectMemberValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * @param array $data
     * @return array|mixed
     */
    public function create(array $data)
    {
        try {
            $this->validator->with($data)->passesOrFail();

            return $this->repository->create($data);
        } catch (ValidatorException $e) {

            return [
                "error" => true,
                "message" => $e->getMessageBag()
            ];
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $projectMember = $this->repository->skipPresenter()->find($id);

        return $projectMember->delete();
    }

}