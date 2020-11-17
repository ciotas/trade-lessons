<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
	use HasDateTimeFormatter;

	protected $fillable = ['name', 'videoId', 'lesson_id', 'duration', 'cover_url', 'sort'];

    const VOD_STATUS_READY    = 'ready';
    const VOD_STATUS_UPLOADING = 'uploading';
    const VOD_STATUS_TRANSFER_CODE = 'transfer_code';
    const VOD_STATUS_TRANSFER_DONE = 'transfer_done';

    public static $vodStatusMap = [
        self::VOD_STATUS_READY => '准备上传',
        self::VOD_STATUS_UPLOADING => '上传中',
        self::VOD_STATUS_TRANSFER_CODE => '转码中',
        self::VOD_STATUS_TRANSFER_DONE => '转码完成'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
