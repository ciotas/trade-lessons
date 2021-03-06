<?php

namespace App\Jobs;

use App\Services\AliCloud;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CoverVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $videoId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($videoId)
    {
        $this->videoId = $videoId;
        $this->aliCloud = new AliCloud();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(AliCloud $aliCloud)
    {
        try {
            $aliCloud->deleteVideos($this->videoId);
        } catch (\Exception $e) {
            \Log::error('删除视频失败'.$e->getMessage());
        }
    }
}
