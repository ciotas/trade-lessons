<?php

namespace App\Admin\Repositories;

use App\Models\Tagable as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Tagable extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
