@extends("layouts.frame")

@section("title", "注册")

@section("content")


<strong>请选择您的角色：</strong>
<input class="role-radio" type="radio" name="role" value="student" {{ old("role") == "student" ? "checked":"" }}> 学生
<input class="role-radio" type="radio" name="role" value="teacher" {{ old("role") == "teacher" ? "checked":"" }}> 高校教师
<input class="role-radio" type="radio" name="role" value="other"   {{ old("role") == "other" ? "checked":"" }}> 其他

<hr>


<div id="student-form" style="display: none;">
{!! Form::open(["route"=>"register.basic.save", "method"=>"post"]) !!}

{{ Form::hidden("role", "student") }}

{{ Form::label("realname", "姓名") }}
{{ Form::text("realname", null, ["class"=>"form-control"])}}

{{ Form::label("email", "邮箱") }}
{{ Form::email("email", null, ["class"=>"form-control"]) }}

{{ Form::label("school", "学校名称")}}
{{ Form::text("school", null, ["class"=>"form-control"])}}

{{ Form::label("department", "院系名称") }}
{{ Form::text("department", null, ["class"=>"form-control"])}}

{{ Form::submit("下一步", ["class"=>"btn btn-primary btn-block form-spacing-top"])}}

{!! Form::close() !!}
</div>





<div id="other-form" style="display: none;">
{!! Form::open(["route"=>"register.basic.save", "method"=>"post"]) !!}

{{ Form::hidden("role", "other") }}

{{ Form::label("realname", "姓名") }}
{{ Form::text("realname", null, ["class"=>"form-control"])}}

{{ Form::label("email", "邮箱") }}
{{ Form::email("email", null, ["class"=>"form-control"]) }}

{{ Form::label("workplace", "工作单位")}}
{{ Form::text("workplace", null, ["class"=>"form-control"])}}

{{ Form::label("jobtitle", "职务") }}
{{ Form::text("jobtitle", null, ["class"=>"form-control"])}}

{{ Form::submit("下一步", ["class"=>"btn btn-primary btn-block form-spacing-top"])}}

{!! Form::close() !!}
</div>




<div id="teacher-form" style="display: none;">
{!! Form::open(["route"=>"register.basic.save", "method"=>"post", "class"=>"form-horizontal"]) !!}

{{ Form::hidden("role", "teacher") }}

<div class="row">
{{ Form::label("realname", "真实姓名", ["class"=>"col-xs-2 control-label form-spacing-top"]) }}
<div class="col-xs-10">
{{ Form::text("realname", null, ["class"=>"form-control form-spacing-top"])}}
</div>
</div>

<div class="row">
{{ Form::label("email", "邮箱", ["class"=>"col-xs-2 control-label form-spacing-top"]) }}
<div class="col-xs-10">
{{ Form::email("email", null, ["class"=>"form-control form-spacing-top"]) }}
</div>
</div>


<div class="row">
{{ Form::label("qqnumber", "QQ号", ["class"=>"col-xs-2 control-label form-spacing-top"])}}
<div class="col-xs-10">
{{ Form::text("qqnumber", null, ["class"=>"form-control form-spacing-top"])}}
</div>
</div>



<div class="row">
{{ Form::label("phone", "手机号", ["class"=>"col-xs-2 control-label form-spacing-top"])}}  
<div class="col-xs-10">
{{ Form::text("phone", null, ["class"=>"form-control form-spacing-top", "placeholder"=>"（寄送样书需要）"])}}
</div>
</div>



<div class="row">
{{ Form::label("province", "省份", ["class"=>"col-xs-2 control-label form-spacing-top"])}}
<div class="col-xs-4">
{{ Form::text("province", null, ["class"=>"form-control form-spacing-top"])}}
</div>
{{ Form::label("city", "城市", ["class"=>"col-xs-2 control-label form-spacing-top"])}}
<div class="col-xs-4">
{{ Form::text("city", null, ["class"=>"form-control form-spacing-top"])}}
</div>
</div>



<div class="row">
{{ Form::label("workplace", "学校名称", ["class"=>"col-xs-2 control-label form-spacing-top"])}}
<div class="col-xs-10">
{{ Form::text("workplace", null, ["class"=>"form-control form-spacing-top"])}}
</div>
{{ Form::label("department", "院系名称", ["class"=>"col-xs-2 control-label form-spacing-top"]) }}
<div class="col-xs-10">
{{ Form::text("department", null, ["class"=>"form-control form-spacing-top"])}}
</div>
</div>





<div class="row">
{{ Form::label("jobtitle", "职称", ["class"=>"col-xs-2 control-label form-spacing-top"]) }}
<div class="col-xs-10 form-spacing-top">
{{ Form::text("jobtitle", null, ["class"=>"form-control form-spacing-top"])}}
</div>
</div>



<div class="row">
{{ Form::label("course_name_1", "教授课程", ["class"=>"col-xs-2 control-label form-spacing-top"]) }}
<div class="col-xs-5">
{{ Form::text("course_name_1", null, ["class"=>"form-control form-spacing-top"])}}
</div>
{{ Form::label("number_stud_1", "学生人数", ["class"=>"col-xs-2 control-label form-spacing-top"]) }}
<div class="col-xs-3">
{{ Form::number("number_stud_1", null, ["class"=>"form-control form-spacing-top"])}}
</div>
</div>

{{ Form::submit("下一步", ["class"=>"btn btn-primary btn-block form-spacing-top"])}}

{!! Form::close() !!}
</div>

<script>

$(document).ready(function(){

    $(".role-radio").click(function(obj){
        $("#student-form").css("display", "none");
        $("#teacher-form").css("display", "none");
        $("#other-form").css("display", "none");

        switch(this.value){
            case "teacher": $("#teacher-form").css("display", ""); break;
            case "student": $("#student-form").css("display", ""); break;
            case "other": $("#other-form").css("display", ""); break;
        }
    });

    //$("input[name='role']:checked").click();

});

</script>

@endsection