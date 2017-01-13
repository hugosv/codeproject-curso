<?php


namespace CodeProject\Transformers;


use CodeProject\Entities\Project;
use League\Fractal\TransformerAbstract;

class ProjectTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['members'];

    public function transform(Project $project)
    {
        return [
            'id' => $project->id,
            'client_id' => $project->client_id,
            'name' => $project->name,
            'owner_id' => $project->owner_id,
            'owner' => $project->owner->name,
            'description' => $project->description,
            'progress' => (int) $project->progress,
            'status' => $project->status,
            'due_date' => $project->due_date,
            'client' => $project->client->name,
            'is_member' => $project->owner_id != \Authorizer::getResourceOwnerId()
        ];
    }

    public function includeMembers(Project $project)
    {
        return $this->collection($project->members, new MemberTransformer());
    }

    public function includeClient(project $project)
    {
        return $this->item($project->client, new ClientTransformer());
    }
}