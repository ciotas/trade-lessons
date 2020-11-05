<section class="content">
    <div>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <form class="form-horizontal">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="fileUpload{{$id}}" aria-describedby="inputGroupFileAddon01">
                            <label class="custom-file-label" for="fileUpload{{$id}}">选择视频</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <p class="status">上传状态: </p>
                            <div class="spinner" style="width: 60px; display: inline-block" id="spinner{{$id}}">
                                <div class="rect1"></div>
                                <div class="rect2"></div>
                                <div class="rect3"></div>
                                <div class="rect4"></div>
                                <div class="rect5"></div>
                            </div>
                            <div id="status{{$id}}"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <div class="progress-group">
                                <p>上传进度</p>
                                <span class="progress-number" id="auth-progress{{$id}}">0</span>
                                <div class="progress sm">
                                    <div class="progress-bar progress-bar-aqua" id="auth-progress-bar{{$id}}" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <div class="btn-group">
                                <button type="button" id="authUpload{{$id}}" class="btn btn-primary" disabled="true">开始上传</button>
                                <button type="button"  id="pauseUpload{{$id}}" class="btn btn-primary" disabled="true">暂停上传</button>
                                <button type="button"  id="resumeUpload{{$id}}" class="btn btn-primary" disabled="true">恢复上传</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" id="uploadSuccess{{$id}}" onclick="window.location.reload()" class="btn btn-success pull-right">上传完成</button>
                    </div>
                    <!-- /.box-footer -->
                </form>

            </div>
            <div class="col-md-1">
            </div>
        </div>
    </div>
</section>
