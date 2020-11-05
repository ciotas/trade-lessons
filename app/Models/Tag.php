<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
	use HasDateTimeFormatter;

	protected $fillable = ['name'];

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, Tagable::class);
	}
}
