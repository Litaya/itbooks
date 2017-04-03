@extends("layouts.frame")

@section("title", "注册")

@section("content")


<strong>请选择您的角色：</strong>
<input class="role-radio" type="radio" name="role" value="student" {{ (Session::has("last-picked-role") and (Session::get("last-picked-role") == "student")) ? "checked":"" }}> 学生
<input class="role-radio" type="radio" name="role" value="teacher" {{ (Session::has("last-picked-role") and (Session::get("last-picked-role") == "teacher")) ? "checked":"" }}> 高校教师
<input class="role-radio" type="radio" name="role" value="other"   {{ (Session::has("last-picked-role") and (Session::get("last-picked-role") == "other")) ? "checked":"" }}> 其他

<hr>


<div id="student-form" style="display: none;">
<small>
{!! Form::open(["route"=>"register.basic.save", "method"=>"post"]) !!}

{{ Form::hidden("role", "student", ["id"=>"role-pick"]) }}

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
</small>
</div>





<div id="other-form" style="display: none;">
<small>
{!! Form::open(["route"=>"register.basic.save", "method"=>"post"]) !!}

{{ Form::hidden("role", "other", ["id"=>"role-pick"]) }}

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
</small>
</div>




<div id="teacher-form" style="display: none;">
<small>
{!! Form::open(["route"=>"register.basic.save", "method"=>"post", "class"=>"form-horizontal"]) !!}

{{ Form::hidden("role", "teacher", ["id"=>"role-pick"]) }}

<div class="row">
{{ Form::label("realname", "真实姓名", ["class"=>"col-xs-3 control-label form-spacing-top"]) }}
<div class="col-xs-9">
{{ Form::text("realname", null, ["class"=>"form-control form-spacing-top"])}}
</div>
</div>

<div class="row">
{{ Form::label("email", "邮箱", ["class"=>"col-xs-3 control-label form-spacing-top"]) }}
<div class="col-xs-9">
{{ Form::email("email", null, ["class"=>"form-control form-spacing-top"]) }}
</div>
</div>


<div class="row">
{{ Form::label("qqnumber", "QQ号", ["class"=>"col-xs-3 control-label form-spacing-top"])}}
<div class="col-xs-9">
{{ Form::text("qqnumber", null, ["class"=>"form-control form-spacing-top"])}}
</div>
</div>



<div class="row">
{{ Form::label("phone", "手机号", ["class"=>"col-xs-3 control-label form-spacing-top"])}}  
<div class="col-xs-9">
{{ Form::text("phone", null, ["class"=>"form-control form-spacing-top", "placeholder"=>"（寄送样书需要）"])}}
</div>
</div>



<div class="row">

{{ Form::label("province", "省份", ["class"=>"col-xs-3 control-label form-spacing-top"])}}
<div class="col-xs-5">
<select id="province-select" name="province" class="form-control form-spacing-top"></select>
</div>
</div>
<div class="row">
{{ Form::label("city", "城市", ["class"=>"col-xs-3 control-label form-spacing-top"])}}
<div class="col-xs-5">
<select id="city-select" name="city" class="form-control form-spacing-top">
<option value="" selected>请先选择省份</option>
</select>
</div>
</div>



<div class="row">
{{ Form::label("workplace", "学校名称", ["class"=>"col-xs-3 control-label form-spacing-top"])}}
<div class="col-xs-9">
{{ Form::text("workplace", null, ["class"=>"form-control form-spacing-top"])}}
</div>
</div>
<div class="row">
{{ Form::label("department", "院系名称", ["class"=>"col-xs-3 control-label form-spacing-top"]) }}
<div class="col-xs-9">
{{ Form::text("department", null, ["class"=>"form-control form-spacing-top"])}}
</div>
</div>


<div class="row">
{{ Form::label("position", "职务", ["class"=>"col-xs-3 control-label form-spacing-top"]) }}
<div class="col-xs-5">
{{ Form::select("position", ["院长"=>"院长", "副院长"=>"副院长", "主任"=>"主任", "副主任"=>"副主任", "教学秘书"=>"教学秘书", "普通教师"=>"普通教师", "其他"=>"其他"], null, ["class"=>"form-control form-spacing-top", "placeholder"=>"请选择职务"]) }}
</div>
</div>


<div class="row">
{{ Form::label("jobtitle", "职称", ["class"=>"col-xs-3 control-label form-spacing-top"]) }}
<div class="col-xs-5">
{{ Form::select("jobtitle", ["教授"=>"教授", "副教授"=>"副教授", "讲师"=>"讲师", "助教"=>"助教", "其他"=>"其他"], null, ["class"=>"form-control form-spacing-top", "placeholder"=>"请选择职称"]) }}
</div>
</div>



<div class="row">
{{ Form::label("course_name_1", "教授课程", ["class"=>"col-xs-3 control-label form-spacing-top"]) }}
<div class="col-xs-5">
{{ Form::text("course_name_1", null, ["class"=>"form-control form-spacing-top"])}}
</div>
</div>


<div class="row">
{{ Form::label("number_stud_1", "学生人数", ["class"=>"col-xs-3 control-label form-spacing-top"]) }}
<div class="col-xs-5">
{{ Form::number("number_stud_1", null, ["class"=>"form-control form-spacing-top"])}}
</div>
</div>

{{ Form::submit("下一步", ["class"=>"btn btn-primary btn-block form-spacing-top"])}}

{!! Form::close() !!}

</small>
</div>

<script>

$(document).ready(function(){

    $(".role-radio").click(function(obj){
        $("#student-form").css("display", "none");
        $("#teacher-form").css("display", "none");
        $("#other-form").css("display", "none");

        switch(this.value){
            case "teacher": $("#teacher-form").css("display", ""); $("input#role-pick").val("teacher");  break;
            case "student": $("#student-form").css("display", ""); $("input#role-pick").val("student"); break;
            case "other": $("#other-form").css("display", "");     $("input#role-pick").val("other"); break;
        }
    });

    $("input[name='role']:checked").click();

    function getProvinces(){
        $.get("{{route('district.getprovinces')}}",
            function(data, status){
                var s = document.getElementById("province-select");
                for(var i = s.options.length-1; i>=0; i--) s.remove(i);
                var defaultOption = document.createElement("option");
                defaultOption.value = "";
                defaultOption.text  = "请选择";
                defaultOption.selected = "selected";

                s.onchange = getCities;
                s.appendChild(defaultOption);

                for(key in data){
                    var opt = document.createElement("option");
                    opt.text = data[key];
                    opt.value = key;
                    s.appendChild(opt);
                }
            }
        );
    }

    function getCities(){
        var s = document.getElementById("province-select");
        var v = s.options[s.selectedIndex].value;
        $.get("{{route('district.getcities')}}"+"?province_id="+v,
            function(data, status){
                s = document.getElementById("city-select");
                for(var i = s.options.length-1; i >= 0; i--) s.remove(i);
                var defaultOption = document.createElement("option");
                defaultOption.value = "";
                defaultOption.text = "请选择";
                defaultOption.selected = "selected";
                s.appendChild(defaultOption);

                for(key in data){
                    var opt = document.createElement("option");
                    opt.text = data[key];
                    opt.value = key;
                    s.appendChild(opt);
                }
            }
        );
    }

    getProvinces();

});




</script>


@endsection