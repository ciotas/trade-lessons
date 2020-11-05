<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Jobs\CoverVideoJob;
use App\Models\Lesson;
use App\Models\Video;
use App\Services\AliCloud;
use App\Services\PlayToken;
use Illuminate\Http\Request;
use Symfony\Component\Translation\Exception\InvalidResourceException;

class VideoController extends Controller
{
    public function test(Request $request)
    {
//        $aliCloud = new AliCloud();
//        return $res = $aliCloud->deleteVideos('06a673d646c8467da5956be9c7bfab8f');
    }
    /**
     * @param Request $request
     * @param AliCloud $aliCloud
     * 视频处理回调
     */
    public function acceptVodReturn(Request $request, AliCloud $aliCloud)
    {
        // 开始鉴权
        $timestamp = $request->header('X-Vod-Timestamp');
        $sign = $request->header('X-Vod-Signature');
        $sign_local = md5(route('videos.return.back').'|'.$timestamp.'|'.env('ALIYUN_VOD_RETURN_URL_AUTH_KEY'));
        if ($sign == $sign_local) {
            \Log::info('验证成功！');
            \Log::info($request->all());
            switch ($request->EventType) {
                // 视频上传完成
                case 'FileUploadComplete':
                    \Log::info('视频上传完成！');
                    try {
                        // 发起视频转码
                        \Log::info('发起视频转码！');
                        $aliCloud->submitTranscodeJobs($request->VideoId);
                        // 通知前台
                        $this->notifyVideoChange($request->VideoId, 'status', Video::VOD_STATUS_TRANSFER_CODE);
                    } catch (\Exception $e) {
                        \Log::error('发起视频转码错误：'.$e->getCode().'|'.$e->getMessage());
                    }
                    break;
                // 转码完成
                case 'TranscodeComplete':
                    \Log::info('转码完成！');
                    // 通知前台
                    if ($request->Status == 'success') {
                        $this->notifyVideoChange($request->VideoId, 'status', Video::VOD_STATUS_TRANSFER_DONE);
                    }
                    break;
                // 视频截图完成
                case 'SnapshotComplete':
                    \Log::info('视频截图完成！');
                    if ($request->Status == 'success') {
                        $this->notifyVideoChange($request->VideoId, 'coverUrl', $request->CoverUrl);
                    }
                    break;
                // 媒体删除完成
                case 'DeleteMediaComplete':
                    \Log::info('媒体删除完成！');
                    if ($request->Status == 'success') {
                        $videoId = $request->MediaId;
                        // 删除转码配置
                        Video::where('Video_id', $videoId)->delete();
                    }
                    break;
            }
        }
    }

    private function notifyVideoChange($videoId, $key, $val)
    {
        $videoFollow = VideoFollow::where('videoId', $videoId)->first();
        if ($videoFollow) {
            $videoFollow->$key = $val;
            $videoFollow->save();
        } else {
            VideoFollow::create([
                'videoId' => $videoId,
                $key => $val
            ]);
        }
    }

    /**
     * @param Request $request
     * @param PlayToken $playToken
     * @param AliCloud $aliCloud
     * @return false|string
     * 解密服务
     */
    public function vodDecrypt(Request $request, PlayToken $playToken, AliCloud $aliCloud)
    {
        \Log::info('开始解密播放～');
        \Log::info($request->all());

        if ($request->Ciphertext && $request->MtsHlsUriToken) {
            $ciphertext = $request->Ciphertext;
            $token = $request->MtsHlsUriToken;
            // 查询videoId
            if ($playToken->validateToken($token)) {
                try {
                    \Log::info('token验证成功');
                    $response = $aliCloud->vodDecrypt($ciphertext);
                    \Log::info('解密成功'.$response);
                    return base64_decode($response->Plaintext);
                } catch (\Exception $e) {
                    \Log::error('视频解密错误：'.$e->getCode()."|".$e->getMessage());
                }
            } else {
                \Log::info('token验证失败');
            }
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws InvalidRequestException
     * @throws \Illuminate\Validation\ValidationException
     * 上传视频
     */
    public function upload(Request $request, AliCloud $aliCloud)
    {
        $data = $this->validate($request, [
            'lesson_id' => ['required'],
            'ext' => ['required']
        ]);

        $lesson = Lesson::with(['type', 'tags'])->find($data['lesson_id']);
        $extension =  $request->ext;
        // 获取目录
        $cateId = $lesson->type->typeId ?? -1;

        $params = [
            'title'=> $lesson->name,
            'filename'=> $lesson->name.'.'.$extension, // 必须带扩展名，且扩展名不区分大小写
            'coverUrl'=> '',
            'tags'=> implode(',', $lesson->tags->pluck('name')->toArray()),
            'description'=> mb_substr(strip_tags($lesson->brief), 0, 100),
            'cateId' => $cateId,
            'templateGroupId' => env('ALIYUN_TEMPLATE_GROUP_ID'),
            'callbackURL'=> '',
            'callbackType'=> env('VOD_CALLBACK_TYPE', 'http'),
            'extend' => []
        ];
        try {
            return $aliCloud->createUploadVideo($params, false);
        } catch (\Exception $e) {
            \Log::error('上传失败:'.$e->getCode().'|'.$e->getMessage());
            return response()->json([
                'status'=>'fail',
                'message'=> $e->getMessage(),
            ]);
        }
    }

    public function refreshUpload(Request $request, AliCloud $aliCloud)
    {
        $data = $this->validate($request, [
            'videoId' => ['required'],
        ]);
        try {
            return $aliCloud->refreshUploadVideo($data['videoId']);
        } catch (\Exception $exception) {
            return response()->json([
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]);
        }

    }

    public function updateVideoId(Request $request)
    {
        $video = Video::find($request->video_id);
        if ($video) {
            if ($video->videoId) {
                dispatch(new CoverVideoJob($video->videoId));
            }
            $video->videoId = $request->videoId;
            $video->save();
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error', 'message' => '此video不存在']);
    }

    public function deleteVideos(Request $request, AliCloud $aliCloud)
    {
        $video_ids = explode(',', $request->video_ids);
        $videoIds = Video::whereIn('id', $video_ids)->pluck('Video_id')->toArray();
        $videoIds = array_filter($videoIds);
        if (!empty($videoIds)) {
            $aliCloud->deleteVideos(implode(',', $videoIds));
        }
        Video::whereIn('id', $video_ids)->delete();
        return response()->json(['status'=>'success', 'message'=>'删除成功']);
    }

}
