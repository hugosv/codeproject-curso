<?php

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;

use Prettus\Validator\Exceptions\ValidatorException;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\Factory as Storage;

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
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Storage
     */
    private $storage;

    /**
     * @param ProjectRepository $repository
     * @param ProjectValidator $validator
     * @param Filesystem $filesystem
     * @param Storage $storage
     */
    public function __construct(ProjectRepository $repository, ProjectValidator $validator, Filesystem $filesystem, Storage $storage)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->filesystem = $filesystem;
        $this->storage = $storage;
    }

    public function all()
    {
        return $this->repository->with(['client', 'owner'])->all();
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
        if (count($this->repository->skipPresenter()->findWhere(['id' => $projectId, 'owner_id' => $userId] ) ))
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

    public function createFile(array $data)
    {
        $project = $this->repository->skipPresenter()->find($data['project_id']);
        $projectFile = $project->files()->create($data);

        $this->storage->put($projectFile->project_id . '_' . $projectFile->id . "." . $data['extension'], $this->filesystem->get($data['file']));

    }

    public function deleteFile($projectId, $fileId)
    {
        // Busca o arquivo
        $file = $this->repository->skipPresenter()->find($projectId)->files->find($fileId);

        // Caso não encontre, retorna erro
        if($file == null)
        {
            return [
                'error'   => true,
                'message' => 'File not found!',
            ];
        }

        // Pega o nome do arquivo e deleta do filesystem
        $fileName = $file->project_id . '_' . $file->id . '.' . $file->extension;
        $this->storage->delete($fileName);

        // Deleta o arquivo do banco de dados
        if( $file->delete() )
        {
            return [
                'message' => 'File deleted success'
            ];
        }

    }


}