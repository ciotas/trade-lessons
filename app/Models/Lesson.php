<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Lesson extends Model
{
	use HasDateTimeFormatter;

	protected $fillable = ['name', 'price', 'crossed_price', 'type_id', 'cover_img', 'brief'];

    public function type()
    {
        return $this->belongsTo(Type::class);
	}

    public function tags()
    {
        return $this->belongsToMany(Tag::class, Tagable::class);
	}

}
