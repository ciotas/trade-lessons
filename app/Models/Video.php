<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
	use HasDateTimeFormatter;

	protected $fillable = ['name', 'videoId', 'lesson_id', 'duration', 'cover_url'];
}
