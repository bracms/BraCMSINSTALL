<?php
return [
	'dev_modules' => ["bra_admin" ,"bra_media" , "health", "wechat_mini" , "wechat_off"], #dev_modules
	'default_app' => "bra_admin", #default app for web
	'default_ctrl' => "index", #default controller for web
	'default_act' => "index", #default action for web
	'sql_debug' => false, # sql debug
    'list_mode' => 'false', # tabulor table
	'is_api' => true, #for cloud dev
    'login_module' => 'wechat_off', #for cloud dev
    'super_admin_ip' => '', # super admin IP address
	'white_list' => [], # for cloud dev, check for https://www.bracms.com for the latest dev server ip address
	'single_session' => [
		'web' => false , # web single login
		'api' => false   # api single login
	],
	'site_id' => 1 #default site id for multi site cloud dev
];
