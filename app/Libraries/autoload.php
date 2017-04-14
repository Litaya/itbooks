<?php

$dictionary = __DIR__."/WechatModules";
$dic_handler = opendir($dictionary);

while (($filename = readdir($dic_handler)) !== false){
	if ($filename != "." && $filename != "..") {
		$module_class_name = explode(".",$filename)[0];

		$module_models = \App\Models\WechatModuleModel::where('module',$module_class_name)->get();

		# 如果还未存库
		if(sizeof($module_models)==0){
			$class_name = 'App\Libraries\WechatModules\\'.$module_class_name;
			$module = new $class_name;
			$weight = $module->weight();
			$name   = $module->name();
			\App\Models\WechatModuleModel::create([
				'name'   => $name,
				'weight' => $weight,
				'module' => $module_class_name
			]);
		}
	}
}
