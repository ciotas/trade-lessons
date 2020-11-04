<?php

namespace App\Admin\Repositories;

use App\Models\Type as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Type extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
