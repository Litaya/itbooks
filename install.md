## 应用配置
git clone到本地之后，需要进行以下配置

### 安装依赖
	shuquan(master)> composer install
	shuquan(master)> npm install

### 给storage，bootstrap/cache目录加写权限
	shuquan(master)> chmod -R a+w storage
	shuquan(master)> chmod -R a+w bootstrap/cache
另外，需要单独给storage/logs/laravel.log添加写权限
	
	shuquan(master)> chmod a+w storage/logs/laravel.log

### 修改Auth的Login逻辑（WechatAuth也相同）
由于本项目修改了原本框架的Login逻辑（Login之后讲permission解析，并加入到session中）, 而`vendor`又默认添加在了	`.gitignore`里面，因此，需要手动在 `vendor/laravel/framework/src/Illuminate/Foundation/Auth/AuthenticatesUsers.php`中添加几行代码

首先

	use App\Libraries\PermissionManager;

其次搜索 `if ($this->attemptLogin($request)) {`所在位置（我们的代码要添加在这里），可以看到框架原本的代码是这样的：

	if ($this->attemptLogin($request)) {
		return $this->sendLoginResponse($request);
	}

我们需要将这段代码修改为：

[2017-03-01 版本(作废，见2017-04-08)]
	if ($this->attemptLogin($request)) {
		// 增添以下三行
		$user = Auth::user();
		$permission = PermissionManager::resolve($user->permission_string);
		$request->session()->put("permission",$permission);
		
		return $this->sendLoginResponse($request);
	}

[2017-04-08 版本] 因采用了新的admin表，故需要将此段代码修改为
	    if ($this->attemptLogin($request)) {
			/* 旧版本已有的 */
            $user = Auth::user();
		    $permission = PermissionManager::resolve($user->permission_string);
		    $request->session()->put("permission",$permission);


            /* 2017-04-08 新增的修改 */
            if(!empty($user->permission_string) and strlen($user->permission_string) > 0){
                $admin = \App\Models\Admin::where('user_id', '=', $user->id)->first();
                $request->session()->put("adminrole", $admin->role);
				switch($admin->role){
					case "DEPTADMIN":
						$code = \App\Models\Department::find($admin->department_id)->code;
						$request->session()->put("admindept", $admin->department_id);
						$request->session()->put("admindeptcode", $code);
						break;
					case "REPRESENTATIVE":
						$request->session()->put("admindist", $admin->district_id);
						break;
				}
            }

            return $this->sendLoginResponse($request);
        }
[2017-04-14] 使用了laravel-scout-mysql-driver，可能因为版本冲突的问题，需要改相关框架内容

    # /vendor/damiantw/laravel-scout-mysql-driver/src/Services/IndexService.php 第5，12行
    将 DetectsApplicationNamespace 改为 AppNamespaceDetectorTrait
    
    

### 在.env中添加微信验证的配置信息
在配置完常规的.env内容后，需要添加微信验证的配置信息。

	APP_ID=xxx
	APP_SECRET=xxx
	APP_TOKEN=shuquan

> APP_ID, APP_SECRET 可以在微信公众号左侧 `开发 > 基本配置`中找到

