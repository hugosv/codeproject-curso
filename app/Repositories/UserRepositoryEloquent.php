<?php

namespace CodeProject\Repositories;

use CodeProject\Entities\User;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    protected $fieldSearchable = [
        'name'
    ];

    /**
     * @return mixed
     */
    public function model()
	{
		return User::class;
	}

	public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}