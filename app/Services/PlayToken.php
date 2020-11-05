<?php
namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PlayToken
{
    // 令牌生成
    public function generateToken()
    {
        $base = env('ALIYUN_USER_ID').'_'.Str::random(10);
        $token = encrypt($base);
        $expiredAt = now()->addMinutes(5);
        $this->saveToken($token, $expiredAt);
        return $token;
    }

    public function saveToken($token, $expiredAt)
    {
        Cache::put($token, Carbon::now(), $expiredAt);
    }

    /**
     * @param $token
     * 解密接口在返回播放秘钥前，需要先校验Token的合法性和有效性
     */
    public function validateToken($token)
    {
        if (Cache::has($token)) {
            $base = decrypt($token);
            $data = explode('_', $base);
            if (is_array($data) && count($data) > 0
                && $data[0] = env('ALIYUN_USER_ID')
                    && Str::length($data[1]) == 10
            ) {
                Cache::forget($token);
                return true;
            }
        }
        return false;
    }

}
