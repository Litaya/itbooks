
@extends("layouts.frame")

@section("title", "详细资料")

@section("content")

@include("userinfo._sub_header")

<!-- QQ号，地区，地址，工作单位 -->

<p><strong>详细资料</strong></p>

{!! Form::model($userinfo, ["route"=>"userinfo.detail.save", "method"=>"post"]) !!}

<small>
{{ Form::label("realname", "真实姓名") }}
{{ Form::text("realname", null, ["class"=>"form-control"])}}


{{ Form::label("qqnumber", "QQ号") }}
{{ Form::text("qqnumber", null, ["class"=>"form-control"])}}


{{ Form::label("workplace", "工作单位") }}
{{ Form::text("workplace", null, ["class"=>"form-control"])}}



<div class="row">
{{ Form::label("province", "省份", ["class"=>"col-xs-2 control-label form-spacing-top"])}}
<div class="col-xs-8">
<select id="province-select" name="province" class="form-control form-spacing-top"></select>
</div>
</div>

<div class="row">
{{ Form::label("city", "城市", ["class"=>"col-xs-2 control-label form-spacing-top"])}}
<div class="col-xs-8">
<select id="city-select" name="city" class="form-control form-spacing-top">
<option value="" selected>请先选择省份</option>
</select>
</div>
</div>


{{ Form::label("address", "地址", ["class"=>"form-spacing-top"]) }}
{{ Form::text("address", null, ["class"=>"form-control"])}}

<div class="col-xs-6 form-spacing-top">
{{ Form::submit("保存", ["class"=>"btn btn-primary btn-block"]) }}
</div>

<div class="col-xs-6 form-spacing-top">
<button class="btn btn-default btn-block">跳过</button>
</div>

</small>
{!! Form::close() !!}

<script>

$(document).ready(function(){

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

                var province = {{!empty($userinfo->province_id) ? $userinfo->province_id:-1}};
                if(province != -1){
                    s.selectedIndex = province;
                    getCities();
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

                var city = {{!empty($userinfo->city_id) ? $userinfo->city_id:-1}};
                if(city != -1){
                    $("#city-select option[value='']").removeAttr("selected");
                    $("#city-select option[value='"+city+"']").attr("selected", true);
                }
            }
        );
    }


    getProvinces();
    
});

</script>

@endsection