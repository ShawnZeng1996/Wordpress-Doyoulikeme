<?php 
// 如果 uninstall 不是从 WordPress 调用，则退出 
if( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

delete_option('like_version_num');
?>