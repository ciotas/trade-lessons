<?php
namespace App\Services;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use AlibabaCloud\Kms\Kms;
use AlibabaCloud\Sts\Sts;
use AlibabaCloud\Vod\Vod;
use OSS\OssClient;

class AliCloud
{
    private $accessKeyId;
    private $accessKeySecret;
    private $client;

    public function __construct()
    {
        $this->regionId = env('ALIYUN_VOD_REGION', 'cn-shanghai');
        $this->accessKeyId = env('ALIYUN_ACCESS_ID', 'test');
        $this->accessKeySecret = env('ALIYUN_ACCESS_KEY', 'test');
        $this->client = $this->initVodClient();
    }

    private function initVodClient() {
        return AlibabaCloud::accessKeyClient($this->accessKeyId, $this->accessKeySecret)
            ->regionId($this->regionId)
            ->connectTimeout(1)
            ->timeout(3)
            ->asDefaultClient();
    }

    /**
     * Notes: 初始化点播
     * User: zzy
     * Date: 2020/1/15 * Time: 10:34 * Version: * @param $uploadAuth
     * @param $uploadAddress
     */
    private function initOssClient($uploadAuth, $uploadAddress)
    {
        $ossClient = new OssClient($uploadAuth['AccessKeyId'], $uploadAuth['AccessKeySecret'], $uploadAddress['Endpoint'],
            false, $uploadAuth['SecurityToken']);
        $ossClient->setTimeout(86400 * 7); // 设置请求超时时间，单位秒，默认是5184000秒, 建议不要设置太小，如果上传文件很大，消耗的时间会比较长
        $ossClient->setConnectTimeout(10); // 设置连接超时时间，单位秒，默认是10秒
        return $ossClient;
    }


    /**
     * 通过视频ID直接获取媒体文件（支持视频和音频）的播放地址。
     * @param $videoId
     * @return \AlibabaCloud\Client\Result\Result
     * @throws ClientException
     * @throws ServerException
     */
    public function getPlayInfo($videoId) {
        return Vod::v20170321()->getPlayInfo()
            ->withVideoId($videoId)    // 指定接口参数
            ->withAuthTimeout(3600*24)
            ->format('JSON')  // 指定返回格式
            ->request();      // 执行请求
    }

    /**
     * @param $videoId
     * @return \AlibabaCloud\Client\Result\Result
     * @throws ClientException
     * @throws ServerException
     * 获取原视频地址
     */
    public function getMezzanineInfo($videoId)
    {
        return Vod::v20170321()->getMezzanineInfo()
            ->withVideoId($videoId)->request();
    }

    public function getStsInfo($client_name)
    {
        AlibabaCloud::accessKeyClient(env('ALIYUN_ACESSKEY_ID'), env('ALIYUN_ACESSKEY_SECRET'));
        return Sts::v20150401()->assumeRole()
            ->withRoleArn(env('ALIYUN_VOD_STS_ARN'))
            ->withRoleSessionName($client_name)
            ->withPolicy('{
             "Statement":[
                {
                    "Action":
                    [
                        "oss:*",
                        "vod:*",
                        "sts:*",
                        "mts:*"
                    ],
                    "Effect": "Allow",
                    "Resource": "*"
                    }
                ],
          "Version": "1"
        }')->connectTimeout(60)
            ->timeout(65)
            ->request();
    }

    public function buildEncryptConfig()
    {
        try {
            $response = Kms::v20160120()->generateDataKey()
                ->withKeyId(env('ALIYUN_VOD_SERVICE_KEY'))
                ->withKeySpec("AES_128")->request();
        } catch (\Exception $e) {
            \Log::error('获取Encrypt参数失败：'.$e->getCode().'|'.$e->getMessage());
            return null;
        }

        $encryptConfig = array();
        # 解密接口地址，该参数需要将每次生成的密文秘钥与接口URL拼接生成，表示每个视频的解密的密文秘钥都不一样；注意您需要自己部署解密服务
        $encryptConfig["DecryptKeyUri"] = route('vod.decrypt').'?Ciphertext='. $response->CiphertextBlob;
        //秘钥服务的类型，目前只支持KMS
        $encryptConfig["KeyServiceType"] = "KMS";
        # Ciphertext作为解密接口的参数名称，可自定义，此处只作为参考
        $encryptConfig["CipherText"] = $response->CiphertextBlob;

        return \GuzzleHttp\json_encode($encryptConfig);
    }

    public function submitTranscodeJobs($videoId)
    {
        return Vod::v20170321()->submitTranscodeJobs()
            ->withVideoId($videoId)
            ->withTemplateGroupId(env('ALIYUN_TEMPLATE_GROUP_ID'))
            ->withEncryptConfig($this->buildEncryptConfig())
            ->request();
    }

    public function vodDecrypt($ciphertext)
    {
        return Kms::v20160120()->decrypt()
            ->withCiphertextBlob($ciphertext)
            ->request();
    }

    /*
    * 获取视频上传地址和凭证
    * @param client 发送请求客户端
    * @return CreateUploadVideoResponse 获取视频上传地址和凭证响应数据
     * 返回RequestId、VideoId、UploadAddress、UploadAuth
    **/
//    public function createUploadVideo($videoInfo, $localFile, $isCallBack = false) {
//        $userData = array(
//            "MessageCallback"=>['CallbackURL'=>$videoInfo['callbackURL'], 'CallbackType'=> $videoInfo['callbackType']],
//            "Extend"=> $videoInfo['extend'], //自定义返回字段
//            'AccelerateConfig'=> ['Type'=> 'oss', 'Domain'=>'https://oss-accelerate.aliyuncs.com']
//        );
//
//        $request = Vod::v20170321()->createUploadVideo()
//            ->withTitle($videoInfo['title']) //标题
//            ->withFileName($videoInfo["filename"]) //视频源文件名
//            ->withDescription($videoInfo['description'])
//            ->withCoverURL($videoInfo['coverUrl'])
//            ->withTags($videoInfo['tags'])
//            ->withCateId($videoInfo['cateId']) //20000000459
//            ->withTemplateGroupId($videoInfo['templateGroupId']); //转码模板组ID  81e7ceecbf30825b5a0ebd73dcf5bbad
//        if ($isCallBack) {
//            $request = $request->withUserData(json_encode($userData));
//        }
//        $result = $request->request();
//
//        $uploadAddress = json_decode(base64_decode($result->UploadAddress), true);
//        $uploadAuth = json_decode(base64_decode($result->UploadAuth), true);
//        $ossClient = $this->initOssClient($uploadAuth, $uploadAddress);
//
//        $this->multipartUploadFile($ossClient, $uploadAddress, $localFile);
//        return ['Video_id' => $result->VideoId, 'filename'=> $uploadAddress['FileName']];
//
//    }

    public function createUploadVideo($videoInfo, $isCallBack = false) {
        $userData = array(
            "MessageCallback"=>['CallbackURL'=>$videoInfo['callbackURL'], 'CallbackType'=> $videoInfo['callbackType']],
            "Extend"=> $videoInfo['extend'], //自定义返回字段
            'AccelerateConfig'=> ['Type'=> 'oss', 'Domain'=>'https://oss-accelerate.aliyuncs.com']
        );

        $request = Vod::v20170321()->createUploadVideo()
            ->withTitle($videoInfo['title']) //标题
            ->withFileName($videoInfo["filename"]) //视频源文件名
            ->withDescription($videoInfo['description'])
            ->withCoverURL($videoInfo['coverUrl'])
            ->withTags($videoInfo['tags'])
            ->withCateId($videoInfo['cateId']) //20000000459
            ->withTemplateGroupId($videoInfo['templateGroupId']); //转码模板组ID  81e7ceecbf30825b5a0ebd73dcf5bbad
        if ($isCallBack) {
            $request = $request->withUserData(json_encode($userData));
        }
        return $request->request();
    }

    private static function multipartUploadFile($ossClient, $uploadAddress, $localFile)
    {
        return $ossClient->multiuploadFile($uploadAddress['Bucket'], $uploadAddress['FileName'], $localFile);
    }

    /**
     * 刷新视频上传凭证
     * @param $videoId
     * @return \AlibabaCloud\Client\Result\Result
     * @throws ClientException
     * @throws ServerException
     * 返回：RequestId、UploadAuth、UploadAddress、VideoId
     */
    public function refreshUploadVideo($videoId) {
        return Vod::v20170321()
            ->refreshUploadVideo()
            ->withVideoId($videoId)
            ->request();
    }

    /**
     * 获取图片上传地址和凭证,该接口不会真正上传图片文件，
     * 您需在获得上传凭证和地址后，使用上传SDK进行文件上传（和视频上传相同）。
     * @param $imageType 图片类型: default(默认)、cover
     * @param $imageExt 图片文件扩展名： png(默认)、jpg、jpeg、gif
     *
     *
     */
    public function createUploadImage($imageInfo) {
        return Vod::v20170321()->createUploadImage()
            ->withImageType($imageInfo['imageType'])
            ->withImageExt($imageInfo['imageExt'])
            ->withTitle($imageInfo['title'])
            ->withTags($imageInfo['tags'])
            ->withCateId($imageInfo['cateId'])
            ->withDescription($imageInfo['description'])
            ->request();
    }

    /**
     * 获取辅助媒资(水印、字幕等)上传地址和凭证
     * @param $attachInfo
     * @return \AlibabaCloud\Client\Result\Result
     * @throws ClientException
     * @throws ServerException
     */
    public function createUploadAttachedMedia($attachInfo) {
        return  Vod::v20170321()->createUploadAttachedMedia()
            ->withBusinessType($attachInfo['businessType']) // 必填，业务类型：watermark、subtitle、material
            ->withMediaExt($attachInfo['mediaType'])// 必填，文件扩展名：水印：png、gif、apng、mov
            //字幕：srt、ass、stl、ttml、vtt
            //素材：jpg、gif、png、mp4、mat、zip
            ->withTitle($attachInfo['title'])
            ->withTitle($attachInfo['title'])
            ->withCateIds($attachInfo['cateIds']) // 多个用逗号分割
            ->withFileName($attachInfo['filename'])
            ->withTags($attachInfo['tags']) // 多个用逗号分割
            ->withDescription($attachInfo['description'])
            ->request();
    }

    /**
     * 基于源文件URL，拉取媒体文件进行上传。
     * @return \AlibabaCloud\Client\Result\Result
     * @throws ClientException
     * @throws ServerException
     */
    public function uploadMediaByURL($urls, $templateGroupId) {
        return Vod::v20170321()->uploadMediaByURL()
            ->withUploadURLs($urls) //必填，视频源文件url,需要urlencode，多个以逗号分隔，最多支持20个
            ->withTemplateGroupId($templateGroupId)
            ->request();
    }

    /**
     * 通过URL上传时返回的JobId或者上传时使用的URL获取URL上传信息，
     * JobIds和UploadURLs必须指定一个，二者同时传入时只处理JobIds。
     * @param $urls
     * @return \AlibabaCloud\Client\Result\Result
     * @throws ClientException
     * @throws ServerException
     */
    public function getURLUploadInfos($jobIds = '', $urls = '') {
        return Vod::v20170321()->getURLUploadInfos()
            ->withJobIds($jobIds) //JobId列表。多个用逗号分隔，最多支持10个
            ->withUploadURLs($urls)
            ->request();
    }

    /**
     * 获取视频播放时所需的播放凭证。
     * @param $videoId
     *
     */
    public function getPlayAuth($videoId) {
        return Vod::v20170321()->getVideoPlayAuth()
            ->withVideoId($videoId) // 必填，视频ID
            ->withAuthInfoTimeout(1800) //播放凭证过期时间。取值范围：100~3000，默认值：100秒
//            ->withReAuthInfo() //CDN二次鉴权参数，为JSON字符串
            ->request();
    }

    /**
     * 获取上传视频信息
     * @param $videoId
     * @return mixed
     * @throws ClientException
     */
    public function getVideoInfo($videoId) {
        return Vod::v20170321()->getVideoInfo()
            ->withVideoId($videoId)
            ->request();
    }


    /**
     * 批量获取上传视频信息
     * @param $videoIds
     * @return \AlibabaCloud\Client\Result\Result
     * @throws ClientException
     * @throws ServerException
     */
    public function getVideoInfos($videoIds) {
        return Vod::v20170321()->getVideoInfos()
            ->withVideoIds($videoIds) //视频ID列表。多个用逗号分隔，最多支持20个。
            ->request();
    }


    /**
     * 修改视频信息
     * @param $videoId
     * @param $newVideoInfo
     * @return \AlibabaCloud\Client\Result\Result
     * @throws ClientException
     * @throws ServerException
     * 返回RequestId
     */
    public function updateVideoInfo($videoId, $newVideoInfo) {
        return Vod::v20170321()->updateVideoInfo()
            ->withVideoId($videoId)
            ->withTitle($newVideoInfo['title'])
            ->withDescription($newVideoInfo['description'])
            ->withCoverURL($newVideoInfo['coverUrl'])
            ->withCateId($newVideoInfo['cateId'])
            ->withTags($newVideoInfo['tags'])
            ->request();
    }


    /**
     * 删除完整视频
     * @param $videoIds
     * @return \AlibabaCloud\Client\Result\Result
     * @throws ClientException
     * @throws ServerException
     */
    public function deleteVideos($videoIds) {
        return Vod::v20170321()->deleteVideo()
            ->withVideoIds($videoIds) // 多个用逗号分割
            ->request();
    }

    /**
     * 创建视频分类
     * @param $cateName
     * @param int $parentId
     * @return \AlibabaCloud\Client\Result\Result
     * @throws ClientException
     * @throws ServerException
     */
    public function addCategory($cateName, $parentId=-1) {
        return Vod::v20170321()->addCategory()
            ->withCateName($cateName)
            ->withParentId($parentId)
            ->request();
    }

    /**
     * 修改视频分类
     * @param $cateId
     * @param $cateName
     * @return \AlibabaCloud\Client\Result\Result
     * @throws ClientException
     * @throws ServerException
     */
    public function updateCategory($cateId, $cateName) {
        return Vod::v20170321()->updateCategory()
            ->withCateId($cateId)
            ->withCateName($cateName)
            ->request();
    }

    /**
     * 删除分类，同时会删除其下级分类（包括二级分类和三级分类），请慎重操作
     * @param $cateId
     * @return \AlibabaCloud\Client\Result\Result
     * @throws ClientException
     * @throws ServerException
     */
    public function deleteCategory($cateId) {
        return Vod::v20170321()->deleteCategory()
            ->withCateId($cateId)
            ->request();
    }

    /**
     * 获取分类及子分类
     * @param int $cateId
     * @param int $pageNo
     * @param int $pageSize
     * @return \AlibabaCloud\Client\Result\Result
     * @throws ClientException
     * @throws ServerException
     */
    public function getCategories($cateId=-1, $pageNo=1, $pageSize=10) {
        return Vod::v20170321()
            ->getCategories()
            ->withCateId($cateId)
            ->withPageNo($pageNo) //子分类列表页号
            ->withPageSize($pageSize) //子分类列表页长。最大不超过100
            ->request();
    }

}

