<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectFileRepository;
use CodeProject\Services\ProjectFileService;
use Illuminate\Http\Request;

class ProjectFileController extends Controller
{

    /**
     * @var ProjectFileRepository
     */
    private $repository;

    /**
     * @var ProjectFileService
     */
    private $service;

    /**
     * @param ProjectFileRepository $repository
     * @param ProjectFileService $service
     */
    public function __construct(ProjectFileRepository $repository, ProjectFileService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function index($id)
    {
        return $this->repository->findWhere(['project_id' => $id]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return bool
     */
    public function store(Request $request)
    {
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        $data['file'] = $file;
        $data['extension'] = $extension;
        $data['name'] = $request->name;
        $data['project_id'] = $request->project_id;
        $data['description'] = $request->description;

        return $this->service->create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return mixed
     */
    public function showFile($id)
    {
        if ($this->service->checkProjectPermissions($id) == false) {
            return ['error' => 'Access Forbidden'];
        }

        $filePath = $this->service->getFilePath($id);
        $fileContent = file_get_contents($filePath);
        $file64 = base64_encode($fileContent);

        return [
            'file' => $file64,
            'size' => filesize($filePath),
            'name' => $this->service->getFileName($id),
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return mixed
     */
    public function show($id, $fileId)
    {
        return $this->repository->find($fileId);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return mixed
     */
    public function update(Request $request, $id, $fileId)
    {
        if($this->service->checkProjectOwner($fileId) == false) {
            return['error' => 'Access Forbiden'];
        }
        return $this->service->update($request->all(), $fileId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return mixed
     */
    public function destroy($id, $fileId)
    {
        if ($this->service->checkProjectOwner($fileId) == false) {
            return ['error' => 'Access Forbiden'];
        }
        $this->service->delete($fileId);
    }

}
