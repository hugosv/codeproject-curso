<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Entities\ProjectFile;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;
use Mockery\CountValidator\Exception;
use Validator;

class ProjectFileController extends Controller
{

    /**
     * @var ProjectRepository
     */
    private $repository;

    /**
     * @var ProjectService
     */
    private $service;

    /**
     * @param ProjectRepository $repository
     * @param ProjectService $service
     */
    public function __construct(ProjectRepository $repository, ProjectService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required'
            ]);

            if($validator->fails()) {
                return [
                    'error' => true,
                    'message' => 'File missing'
                ];
            }

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            $data = [
                'file' => $file,
                'extension' => $extension,
                'name' => $request->name,
                'project_id' => $request->project_id,
                'description' => $request->description,
            ];

            $this->service->createFile($data);

            return [
                'message' => 'File stored'
            ];

        } catch(Exception $e) {
            return [
                'error' => true,
                'message' => 'Error'
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, $fileId)
    {
        return $this->service->deleteFile($id, $fileId);
    }

}
