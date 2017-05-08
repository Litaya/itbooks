@extends("layouts.frame")

@section("title", "教师信息")

@section("content")

    @include("userinfo._sub_header")

    <!-- 学校，院系，职称，证明材料/照片，(所授课程，学生人数)x3 -->

    <p><strong>教师信息</strong></p>

    {!! Form::model($userinfo, ["id"=>"info-form", "route"=>"userinfo.teacher.save", "method"=>"post", "files"=>true]) !!}

    <input id="sendrequest" type="hidden" name="sendrequest" value="false">

    <small>
        <div class="form-inline"><div class="form-group">
                {{ Form::label("realname", "真实姓名") }}
                {{ Form::text("realname", null, ["class"=>"form-control"])}}
            </div></div>

        <div class="form-inline"><div class="form-group">
                {{ Form::label("workplace", "工作单位") }}
                {{ Form::text("workplace", null, ["class"=>"form-control", "placeholder"=>"学校名称"])}}
            </div></div>

        <div class="form-inline"><div class="form-group">
                {{ Form::label("department", "院系") }}
                {{ Form::text("department", null, ["class"=>"form-control"]) }}
            </div></div>

        <div class="form-inline"><div class="form-group">
                {{ Form::label("position", "职务") }}
                {{ Form::select("position", ["院长"=>"院长", "副院长"=>"副院长", "主任"=>"主任", "副主任"=>"副主任", "教学秘书"=>"教学秘书", "普通教师"=>"普通教师", "其他"=>"其他"], null, ["class"=>"form-control", "placeholder"=>"请选择职务"]) }}
            </div></div>

        <div class="form-inline"><div class="form-group">
                {{ Form::label("jobtitle", "职称") }}
                {{ Form::select("jobtitle", ["教授"=>"教授", "副教授"=>"副教授", "讲师"=>"讲师", "助教"=>"助教", "其他"=>"其他"], null, ["class"=>"form-control", "placeholder"=>"请选择职称"]) }}
            </div></div>

        <hr>

        <div class="form-inline"><div class="form-group">
                {{ Form::label("course_name_1", "课程名称1")}}{{ Form::text("course_name_1", null, ["class"=>"form-control", "placeholder"=>"课程名称"])}}
                {{ Form::label("course_name_1", "学生人数1")}}{{ Form::number("number_stud_1", null,["class"=>"form-control", "placeholder"=>"课程人数"])}}
            </div></div>
        <div class="form-inline"><div class="form-group">
                {{ Form::label("course_name_2", "课程名称2")}}{{ Form::text("course_name_2", null, ["class"=>"form-control", "placeholder"=>"课程名称"])}}
                {{ Form::label("course_name_2", "学生人数2")}}{{ Form::number("number_stud_2", null, ["class"=>"form-control", "placeholder"=>"课程人数"])}}
            </div></div>
        <div class="form-inline"><div class="form-group">
                {{ Form::label("course_name_3", "课程名称3")}}{{ Form::text("course_name_3", null, ["class"=>"form-control", "placeholder"=>"课程名称"])}}
                {{ Form::label("course_name_3", "学生人数3")}}{{ Form::number("number_stud_3", null,["class"=>"form-control", "placeholder"=>"课程人数"])}}
            </div></div>

        <hr>

        @if(!empty($userinfo->img_upload))
            <img class="img-responsive" src="{{route('image', $userinfo->img_upload)}}" width="100" height="100"></img>
        @endif
        {{ Form::label("img_upload", "上传图片材料", ["class"=>"form-spacing-top"]) }}
        <small>(请上传教师证、校网个人主页截图等可供验证教师身份的图片)</small>
        {{ Form::file("img_upload", ["class"=>"form-control"])}}

        {{ Form::text('image_media_id',null,["id"=>"image_media_id","hidden"=>"hidden"]) }}
        <button class="btn btn-default btn-sm" onclick="chooseImage()">选择图片</button>

        {{ Form::submit("保存", ["class"=>"btn btn-primary btn-block form-spacing-top"])}}

        @if($userinfo->role =="teacher")
            <button class="btn btn-primary btn-block form-spacing-top" onclick="save_and_apply()">保存并申请认证</button>
        @endif
    </small>
    {!! Form::close() !!}

    <script>
        wx.config(<?php echo $js->config(array('chooseImage', 'uploadImage'), true) ?>);

        function chooseImage(){
            wx.chooseImage({
                count: 1, // 默认9
                sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                success: function (res) {
                    var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                    setTimeout(function () {
                        wx.uploadImage({
                            localId: localIds.toString(), // 需要上传的图片的本地ID，由chooseImage接口获得
                            isShowProgressTips: 1, // 默认为1，显示进度提示
                            success: function (res) {
                                var serverId = res.serverId; // 返回图片的服务器端ID
                                $("#image_media_id").val(serverId);
                            }
                        });
                    },100);
                }
            });
        }
        function save_and_apply()
        {
            $("#sendrequest").val("true");
            $("#info-form").submit();
        }
    </script>

@endsection