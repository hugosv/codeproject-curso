<?php


namespace CodeProject\Transformers;


use Carbon\Carbon;
use CodeProject\Entities\Project;
use League\Fractal\TransformerAbstract;

class ProjectTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['members', 'client', 'tasks', 'files', 'notes'];

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
            'is_member' => $project->owner_id != \Authorizer::getResourceOwnerId(),
            'tasks_count' => $project->tasks->count(),
            'tasks_opened' => $this->countTasksOpened($project),
            'evaluate' => $this->evaluateProgress($project)
        ];
    }

    public function includeMembers(Project $project)
    {
        return $this->collection($project->members, new MemberTransformer());
    }

    public function includeClient(Project $project)
    {
        $transformer = new ClientTransformer();
        $transformer->setDefaultIncludes([]);

        return $this->item($project->client, $transformer);
    }

    public function includeTasks(Project $project)
    {
        return $this->collection($project->tasks, new ProjectTaskTransformer());
    }

    public function includeFiles(Project $project)
    {
        return $this->collection($project->files, new ProjectFileTransformer());
    }

    public function includeNotes(Project $project)
    {
        return $this->collection($project->notes, new ProjectNoteTransformer());
    }

    public function countTasksOpened(Project $project)
    {
        $count = 0;
        foreach($project->tasks as $o){
            if($o->status == 1){
                $count++;
            }
        }
        return $count;
    }

    public function evaluateProgress(Project $project)
    {
        $now = Carbon::now();
        $due_date = new Carbon($project->due_date);

        if ($project->status == 3){
            return [
                'progress_expected' => 100,
                'progress_label' => 'Concluído',
                'diff_days' => 0
            ];
        }
        elseif ($now >= $due_date)
        {
            return [
                'progress_expected' => 100,
                'progress_label'    => 'Atrasado',
                'diff_days' => $now->diff($due_date)->days * -1
            ];
        }

        $days_of_project = $due_date->diff($project->created_at)->days;
        $days_at_now = $now->diff($project->created_at)->days;

        $expected = intval((100 * $days_at_now)/$days_of_project);

        return [
            'progress_expected' =>  $expected,
            'progress_label' =>  $project->progress >= $expected ? 'Em dia' : 'Em atraso',
            'diff_days' => $due_date->diff($now)->days
        ];
    }
}