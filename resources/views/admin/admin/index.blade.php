@extends("admin.layouts.frame")

@section("title", "管理员列表")

@section("content")

<div class="row">
<div class="col-xs-12">
{!! Form::open(["route"=>"admin.admin.index", "method"=>"GET"]) !!}
{{ Form::text('search', null, ['placeholder'=>'用户名、邮箱、真实姓名']) }}
{{ Form::select('role', ["all"=>"全部", "SUPERADMIN"=>"超级管理员", "DEPTADMIN"=>"部门管理员", "REPRESENTATIVE"=>"地区代表", "NEWADMIN"=>"新管理员"], Input::get('role')) }}
{{ Form::submit('搜索') }}
</div>
{!! Form::close() !!}
</div>


<div class="row">

<div class="col-xs-12">
<table class="table">

<thead>
    <th>用户名</th>
    <th>邮箱</th>
    <th>管理员角色</th>
    <th>部门</th>
    <th>地区</th>
    <th>操作</th>
</thead>

<tbody>
@foreach($users as $user)
    <tr>
        <td>{{$user->username}}</td>
        <td>{{$user->email}}</td>
        <td>{{empty($user->role_translation) ? $user->role : $user->role_translation }}</td>
        <td>{{$user->dept_name}}</td>
        <td>{{$user->dist_name}}</td>
        <td>
        @if($user->id != Auth::id())
        <button class="btn-xs btn-default" id="change-role-btn-{{$user->id}}">修改角色</button>
        <button class="btn-xs btn-default" onclick="javascript:confirmAndDemote({{$user->id}});">取消管理员</button>
        @else
        <small style="color:#AAA;">您不能修改自己的管理员权限</small>
        @endif
        </td>
    </tr>

@endforeach

</tbody>

</table>

</div>

</div>

<div class="modal fade" id="change-role-modal"
tabindex="-1" role="dialog"
aria-labelledby="change-role-modal-label">
    <div class="modal-dialog" role="dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="change-role-label">修改角色</h4>
            </div>
            <div class="modal-body">

            <form action="{{route('admin.admin.changerole')}}" id="change-role-form" method="POST">
            
            <input type="hidden" name="id" id="change-role-id-input" value="">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <select name="role" id="newrole-select">
                <option value="SUPERADMIN">超级管理员</option>
                <option value="DEPTADMIN" id="option-dept-admin">部门管理员</option>
                <option value="EDITOR" id="option-editor">编辑</option>
                <option value="REPRESENTATIVE" id="option-repr">地区代表</option>
            </select>
            {{ Form::submit("确定", ['id'=>'change-role-submit', 'class'=>'btn-xs btn-primary'])}}
            </form>
            </div>
        </div>
    </div>
</div>


<form action="{{route('admin.admin.demote')}}" method="POST" id="demote-form">
<input type="hidden" name="id" id="demote-id-input" value="">
<input type="hidden" name="_token" value="{{csrf_token()}}">
</form>


<script>

function confirmAndDemote(id){
    var sure = confirm("若取消该管理员的所有权限，该管理员将变成普通用户。确认取消吗？");
    if(sure){
        document.getElementById("demote-id-input").value = id;
        document.getElementById("demote-form").submit();
    }
}

$(document).ready(function(){

    var all_departments;
    var all_provinces;
    var admin_role_mapping;

    /** 预加载所有需要填写的数据 **/
    $.get("{{route('api.admin.get_admin_role_mapping')}}", 
          function(data, status){
              admin_role_mapping = data;
              setButtonOnclick();
          });

    $.get("{{route('api.admin.get_all_departments')}}",
          function(data, status){
              all_departments = data;
          });

    $.get("{{route('api.admin.get_all_provinces')}}",
          function(data, status){
              all_provinces = data;
          });

    /** 改变模态页选项的核心函数 **/
    function updateModal(){
        // 删除部门选择项
        var dept_select = document.getElementById('dept-select');
        if(dept_select) dept_select.parentElement.removeChild(dept_select);
        // 删除地区选择项
        var district_select = document.getElementById('district-select');
        if(district_select) district_select.parentElement.removeChild(district_select);

        switch($('#newrole-select').val()){

            case "SUPERADMIN": 
                break;

            case "DEPTADMIN": // fall through on purpose
            case "EDITOR":
                // 创建部门选择项
                var parent = document.getElementById('newrole-select').parentElement;
                var dept_select = document.createElement('select');
                dept_select.id = "dept-select";
                dept_select.name = "dept_id";

                var default_opt = document.createElement('option');
                default_opt.value = "";
                default_opt.text = "- 请选择部门 -";
                default_opt.selected = "selected";

                dept_select.appendChild(default_opt);
                
                for(var key in all_departments){
                    var opt = document.createElement('option');
                    opt.value = key;
                    opt.text = all_departments[key];
                    dept_select.appendChild(opt);
                }

                var submit_btn = document.getElementById('change-role-submit');
                parent.insertBefore(dept_select, submit_btn);
                break;


            case "REPRESENTATIVE":
                // 创建地区选择项
                var parent = document.getElementById('newrole-select').parentElement;
                var district_select = document.createElement('select');
                district_select.id = "district-select";
                district_select.name = "district_id";

                var default_opt = document.createElement('option');
                default_opt.value = "";
                default_opt.text = "- 请选择省份 -";
                default_opt.selected = "selected";

                district_select.appendChild(default_opt);
                
                for(var key in all_provinces){
                    var opt = document.createElement('option');
                    opt.value = key;
                    opt.text = all_provinces[key];
                    district_select.appendChild(opt);
                }

                var submit_btn = document.getElementById('change-role-submit');
                parent.insertBefore(district_select, submit_btn);
                break;

            default: break;
        }
    }

    /** 选择不同的角色时，改变模态页的选项 **/
    $('#newrole-select').change(function(){
        updateModal();
    });

    /** 点击不同的修改角色按钮时，为USER_ID赋不同的值 **/
    function setButtonOnclick(){
        for(var i in admin_role_mapping){
            var btn = $("#change-role-btn-"+i);
            btn.click(function(){
                var name_split = $(this).attr("id").split("-");
                var id = parseInt(name_split[name_split.length - 1]);

                $('#change-role-id-input').val(id);
                
                var role_select = document.getElementById('newrole-select');
                role_select.options[role_select.selectedIndex].removeAttribute('selected');
                
                if(admin_role_mapping[id] == "NEWADMIN")
                {
                    var default_opt = document.createElement("option");
                    default_opt.val = "";
                    default_opt.text = "- 请选择角色 -";
                    default_opt.selected = "selected";
                    role_select.insertBefore(default_opt, role_select.options[0]);
                }
                else{
                    $("#newrole-select option[value='"+ admin_role_mapping[id] +"']").attr("selected", "selected");
                    updateModal();
                }

                // jQuery.noConflict();
                $("#change-role-modal").modal();
            });
        }
    }
})


</script>


@endsection