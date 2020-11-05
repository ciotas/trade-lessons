<?php
namespace App\Admin\Renderable;

use Dcat\Admin\Admin;
use Dcat\Admin\Support\LazyRenderable;

class VideoUpload extends LazyRenderable
{
// 这里写入需要加载的js和css文件路径
    public static $js = [
        'js/aliyun-upload-sdk/jquery.min.js',
        'https://g.alicdn.com/de/prismplayer/2.8.2/aliplayer-min.js',
        'js/aliyun-upload-sdk/aliyun-upload-sdk-1.5.0.min.js',
        'js/aliyun-upload-sdk/lib/es6-promise.min.js',
        'js/aliyun-upload-sdk/lib/aliyun-oss-sdk-5.3.1.min.js'
    ];

    public static $css = [
        'https://g.alicdn.com/de/prismplayer/2.8.2/skins/default/aliplayer-min.css',
        'css/spiner.css'
    ];

    protected function addScript($id, $lesson_id, $region, $ali_user_id, $createUrl, $refreshUrl, $updateVideoIdUrl)
    {
        Admin::script(
            <<<JS
            $('#spinner$id').hide()
            $('#uploadSuccess$id').attr('disabled', true)
            if (!FileReader.prototype.readAsBinaryString) {
                FileReader.prototype.readAsBinaryString = function (fileData) {
                  var binary = "";
                  var pt = this;
                  var reader = new FileReader();
                  reader.onload = function (e) {
                    var bytes = new Uint8Array(reader.result);
                    var length = bytes.byteLength;
                    for (var i = 0; i < length; i++) {
                      binary += String.fromCharCode(bytes[i]);
                    }
                    //pt.result  - readonly so assign binary
                    pt.content = binary;
                    pt.onload()
                  }
                  reader.readAsArrayBuffer(fileData);
                }
              }
              $(document).ready(function () {
                /**
                 * 创建一个上传对象
                 * 使用 UploadAuth 上传方式
                 */
                function createUploader () {
                  var uploader = new AliyunUpload.Vod({
                    timeout: 60000,
                    partSize: 1048576, // 1M
                    parallel: 5, //并行上传分片数
                    retryCount: 3, // 网络失败重试次数
                    retryDuration: 2, // 网络失败重试间隔 秒
                    region: '$region',
                    userId: '$ali_user_id',
                    // 添加文件成功
                    addFileSuccess: function (uploadInfo) {
                      console.log('addFileSuccess')
                      $('#authUpload$id').attr('disabled', false)
                      $('#resumeUpload$id').attr('disabled', false)
                      $('#status$id').html('<span class="badge badge-success">添加文件成功, 等待上传...</span>')
                      console.log("addFileSuccess: " + uploadInfo.file.name)
                    },
                    // 开始上传
                    onUploadstarted: function (uploadInfo) {
                      console.log('uploadInfo')
                      console.log(uploadInfo)
                      // 如果是 UploadAuth 上传方式, 需要调用 uploader.setUploadAuthAndAddress 方法
                      // 如果是 UploadAuth 上传方式, 需要根据 uploadInfo.videoId是否有值，调用点播的不同接口获取uploadauth和uploadAddress
                      // 如果 uploadInfo.videoId 有值，调用刷新视频上传凭证接口，否则调用创建视频上传凭证接口
                      // 注意: 这里是测试 demo 所以直接调用了获取 UploadAuth 的测试接口, 用户在使用时需要判断 uploadInfo.videoId 存在与否从而调用 openApi
                      // 如果 uploadInfo.videoId 存在, 调用 刷新视频上传凭证接口(https://help.aliyun.com/document_detail/55408.html)
                      // 如果 uploadInfo.videoId 不存在,调用 获取视频上传地址和凭证接口(https://help.aliyun.com/document_detail/55407.html)
                      if (!uploadInfo.videoId) {
                        createUpload(uploader, uploadInfo, $lesson_id, $id, '$createUrl', '$refreshUrl', '$updateVideoIdUrl')
                        $('#spinner$id').show()
                        var tip = '<span>文件开始上传...</span>'
                        $('#status$id').html(tip)

                        console.log("onUploadStarted:" + uploadInfo.file.name + ", endpoint:" + uploadInfo.endpoint + ", bucket:" + uploadInfo.bucket + ", object:" + uploadInfo.object)
                      }
                      else {
                        // 如果videoId有值，根据videoId刷新上传凭证
                        var refreshUrl = '$refreshUrl'
                        $.post(refreshUrl,
                          {videoId: uploadInfo.videoId},
                          function (data) {
                            if (data.code == 404) {
                              createUpload(uploader, uploadInfo, $lesson_id, $id, '$createUrl', '$refreshUrl', '$updateVideoIdUrl')
                            } else {
                              var uploadAuth = data.UploadAuth
                              var uploadAddress = data.UploadAddress
                              var videoId = data.VideoId
                              postVideoId($id, videoId)
                              uploader.setUploadAuthAndAddress(uploadInfo, uploadAuth, uploadAddress,videoId)
                            }
                          }, 'json')
                      }
                    },
                    // 文件上传成功
                    onUploadSucceed: function (uploadInfo) {
                      console.log('文件上传成功, 同步到数据库')
                      console.log(uploadInfo.videoId)
                      console.log("onUploadSucceed: " + uploadInfo.file.name + ", endpoint:" + uploadInfo.endpoint + ", bucket:" + uploadInfo.bucket + ", object:" + uploadInfo.object)
                      $('#spinner$id').hide()
                      $('#status$id').html('<span class="badge badge-success">文件上传成功!</span>')
                      $('#uploadSuccess$id').attr('disabled', false)
                    },
                    // 文件上传失败
                    onUploadFailed: function (uploadInfo, code, message) {
                      console.log("onUploadFailed: file:" + uploadInfo.file.name + ",code:" + code + ", message:" + message)
                      $('#spinner$id').hide()
                      $('#status$id').html('<span class="badge badge-danger">文件上传失败!请重新上传～</span>')
                    },
                    // 取消文件上传
                    onUploadCanceled: function (uploadInfo, code, message) {
                      console.log("Canceled file: " + uploadInfo.file.name + ", code: " + code + ", message:" + message)
                      $('#spinner$id').hide()
                      $('#status$id').html('<span class="badge badge-warning">文件上传已暂停!</span>')
                    },
                    // 文件上传进度，单位：字节, 可以在这个函数中拿到上传进度并显示在页面上
                    onUploadProgress: function (uploadInfo, totalSize, progress) {
                      console.log("onUploadProgress:file:" + uploadInfo.file.name + ", fileSize:" + totalSize + ", percent:" + Math.ceil(progress * 100) + "%")
                      var progressPercent = Math.ceil(progress * 100)
                      $('#auth-progress$id').text(progressPercent + '%')
                      $('#auth-progress-bar$id').css('width', progressPercent+'%')
                      var tip = '<span>文件上传中，请勿关闭浏览器...</span>'
                      $('#spinner$id').show()
                      $('#status$id').html(tip)
                    },
                    // 上传凭证超时
                    onUploadTokenExpired: function (uploadInfo) {
                      // 上传大文件超时, 如果是上传方式一即根据 UploadAuth 上传时
                      // 需要根据 uploadInfo.videoId 调用刷新视频上传凭证接口(https://help.aliyun.com/document_detail/55408.html)重新获取 UploadAuth
                      // 然后调用 resumeUploadWithAuth 方法, 这里是测试接口, 所以我直接获取了 UploadAuth
                      $('#status$id').html('<span class="badge badge-danger">文件上传超时!请重新上传～</span>')
                      $('#spinner$id').hide()
                      let refreshUrl = '$refreshUrl'
                      $.post(refreshUrl,
                        {videoId: uploadInfo.videoId},
                        function (data) {
                          var uploadAuth = data.UploadAuth
                          uploader.resumeUploadWithAuth(uploadAuth)
                          console.log('upload expired and resume upload with uploadauth ' + uploadAuth)
                        }, 'json')
                    },
                    // 全部文件上传结束
                    onUploadEnd: function (uploadInfo) {
                      $('#status$id').html('<span class="badge badge-success">文件上传完毕!</span>')
                      $('#spinner$id').hide()
                      console.log("onUploadEnd: uploaded all the files")
                    }
                  })
                  return uploader
                }

                var uploader = null

                $('#fileUpload$id').on('change', function (e) {
                  var file = e.target.files[0]
                  if (!file) {
                    swal({
                      title: "请先选择需要上传的文件😊",
                      text: "温馨提示",
                      type: "warning",
                    });
                    return
                  }
                  var Title = file.name
                  var userData = '{"Vod":{}}'
                  if (uploader) {
                    uploader.stopUpload()
                    $('#auth-progress{{$id}}').text('0')
                    $('#status{{$id}}').text("")
                  }
                  uploader = createUploader()
                  // 首先调用 uploader.addFile(event.target.files[i], null, null, null, userData)
                  console.log(uploader)
                  uploader.addFile(file, null, null, null, userData)
                  $('#authUpload$id').attr('disabled', false)
                  $('#pauseUpload$id').attr('disabled', true)
                  $('#resumeUpload$id').attr('disabled', true)
                })

                // 第一种方式 UploadAuth 上传
                $('#authUpload$id').on('click', function () {
                  // 然后调用 startUpload 方法, 开始上传
                  if (uploader !== null) {
                    uploader.startUpload()

                    $('#authUpload$id').attr('disabled', true)
                    $('#pauseUpload$id').attr('disabled', false)
                  }
                })

                // 暂停上传
                $('#pauseUpload$id').on('click', function () {
                  if (uploader !== null) {
                    uploader.stopUpload()
                    $('#resumeUpload$id').attr('disabled', false)
                    $('#pauseUpload$id').attr('disabled', true)
                  }
                })

                $('#resumeUpload$id').on('click', function () {
                  if (uploader !== null) {
                    uploader.startUpload()
                    $('#resumeUpload$id').attr('disabled', true)
                    $('#pauseUpload$id').attr('disabled', false)
                  }
                })
              })

              function postVideoId(video_id, videoId, updateVideoIdUrl) {
                $.post(
                  updateVideoIdUrl,
                  {'video_id': video_id, 'videoId': videoId },
                  function(data) {
                    console.log('return data')
                    console.log(data)
                  }
                );
              }

              function createUpload(uploader, uploadInfo, lesson_id, video_id, createUrl, refreshUrl, updateVideoIdUrl) {
                var exts = uploadInfo.file.name.split('.')
                var ext = exts.pop()
                console.log(createUrl)
                $.post(createUrl,
                  {lesson_id: lesson_id, 'ext': ext},
                  function(data) {
                    var uploadAuth = data.UploadAuth
                    var uploadAddress = data.UploadAddress
                    var videoId = data.VideoId
                    uploader.setUploadAuthAndAddress(uploadInfo, uploadAuth, uploadAddress, videoId)
                    postVideoId(video_id, videoId, updateVideoIdUrl)
                  }, 'json')
              }
JS
        );
    }

    public function render()
    {
        $id = $this->key;
        $lesson_id = $this->lesson_id;
        // 添加你的 JS 代码
        $this->addScript(
            $id,
            $lesson_id,
            env('ALIYUN_VOD_REGION'),
            env('ALIYUN_USER_ID'),
            route('video.upload'),
            route('video.refreshUpload'),
            route('videos.update.videoId')
        );
        return view('admin.video-upload', ['id'=>$id]);
    }
}
