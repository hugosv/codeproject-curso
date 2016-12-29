<?php


namespace CodeProject\Services;

use CodeProject\Repositories\ProjectFileRepository;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectFileValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Filesystem\Filesystem;




/**
 * Class ProjectFileService
 * @package CodeProject\Services
 */
class ProjectFileService
{

    /**
     * @var ProjectFileRepository
     */
    private $repository;
    /**
     * @var ProjectRepository
     */
    private $projectRepository;
    /**
     * @var ProjectFileValidator
     */
    private $validator;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Storage
     */
    private $storage;
    /**
     * @var ProjectService
     */
    private $projectService;

    /**
     * ProjectFileService constructor.
     * @param ProjectFileRepository $repository
     * @param ProjectRepository $projectRepository
     * @param ProjectFileValidator $validator
     * @param Filesystem $filesystem
     * @param Storage $storage
     */
    public function __construct(ProjectFileRepository $repository, ProjectRepository $projectRepository, ProjectFileValidator $validator,
                                Filesystem $filesystem, Storage $storage, ProjectService $projectService)

    {
        $this->repository = $repository;
        $this->projectRepository = $projectRepository;
        $this->validator = $validator;
        $this->filesystem = $filesystem;
        $this->storage = $storage;
        $this->projectService = $projectService;
    }

    public function create(array $data)
    {
        try {
            $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);

            $project = $this->projectRepository->skipPresenter()->find($data['project_id']);
            $projectFile = $project->files()->create($data);

            $this->storage->put($projectFile->getFileName(), $this->filesystem->get($data['file']));

            return $projectFile;

        }  catch (ValidatorException $e) {
            return [
                'error' => true,
                'message' => $e->getMessageBag(),
            ];
        }
    }

    public function update(array $data, $id)
    {
        try {
            $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);

            return $this->repository->update($data, $id);

        } catch (ValidatorException $e) {
            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }

    }

    public function delete($id)
    {
        $projectFile = $this->repository->skipPresenter()->find($id);

        if($this->storage->exists($projectFile->getFileName()))
        {
            $this->storage->delete($projectFile->getFileName());
            return $projectFile->delete();
        }
    }

    public function getFilePath($id)
    {
        $projectFile = $this->repository->skipPresenter()->find($id);
        return $this->getBaseUrl($projectFile);
    }

    public function getFileName($id)
    {
        $projectFile = $this->repository->skipPresenter()->find($id);
        return $projectFile->getFileName();
    }

    private function getBaseUrl($projectFile)
    {
        switch ($this->storage->getDefaultDriver()) {
            case 'local':
                return $this->storage->getDriver()->getAdapter()->getPathPrefix() . '/' . $projectFile->getFileName();
        }

    }

    public function checkProjectOwner($projectFileId)
    {
        $userId =  Authorizer::getResourceOwnerId();
        $projectId = $this->repository->skipPresenter()->find($projectFileId)->project_id;

        return $this->projectService->isOwner($projectId, $userId);
    }

    public function checkProjectMember($projectFileId)
    {
        $userId =  Authorizer::getResourceOwnerId();
        $projectId = $this->repository->skipPresenter()->find($projectFileId)->project_id;

        return $this->projectService->isMember($projectId, $userId);

    }

    public function checkProjectPermissions($projectFileId)
    {
        if($this->checkProjectOwner($projectFileId) or $this->checkProjectMember($projectFileId)) {
            return true;
        }
        return false;
    }
}