<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Tagable extends Model
{
	use HasDateTimeFormatter;

	protected $fillable = ['lesson_id', 'tag_id'];
}
