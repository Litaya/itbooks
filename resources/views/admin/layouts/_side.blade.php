<h4> <i class="fa fa-th-large"></i> 全部功能 </h4>
<hr>
<ul>
    @if(in_array('BOOK',\App\Libraries\PermissionManager::getAdminModules()))
        <li><a href="{{ route('admin.book.index') }}"> <i class="fa fa-book push"></i>图书管理</a></li>
    @endif
    @if(in_array('USER',\App\Libraries\PermissionManager::getAdminModules()))
        <li><a href="{{ route('admin.user.index') }}"> <i class="fa fa-user push"></i>用户中心</a></li>
    @endif
    @if(in_array('DEPARTMENT',\App\Libraries\PermissionManager::getAdminModules()))
        <li><a href="{{ route('admin.department.index') }}"> <i class="fa fa-university push"></i>部门管理</a></li>
    @endif
</ul>

<h4> <i class="fa fa-cog" style="margin-top: 40px"></i> 常用功能 </h4>
<hr>
<ul>
    @if(in_array('BOOKREQ',\App\Libraries\PermissionManager::getAdminModules()))
        <li><a href="{{ route('admin.bookreq.index') }}"> <i class="fa fa-book push"></i>审核样书</a></li>
    @endif
    @if(in_array('USER',\App\Libraries\PermissionManager::getAdminModules()))
        <li><a href="#"> <i class="fa fa-user push"></i>审核教师</a></li>
    @endif
    @if(in_array('USER',\App\Libraries\PermissionManager::getAdminModules()))
        <li><a href="#"> <i class="fa fa-key push"></i>权限管理</a></li>
    @endif
</ul>