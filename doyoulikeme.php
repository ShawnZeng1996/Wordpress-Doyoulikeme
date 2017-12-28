<?php
/*
 * Plugin Name: Do you like me
 * Plugin URI: https://shawnzeng.com/wordpress-plugin-do-you-like-me.html
 * Description: Add a 'do you like me' to your blog.
 * Version: 1.0
 * Author: <a href="http://i.mouto.org">卜卜口</a>开发 | 由<a href="https://shawnzeng.com">Shawn</a>移植至WordPress 
 * Author URI: 
 * License: GPLv2
 * Copyright 2017 Shawn (email : admin@shawnzeng.com)
 */

// 声明常量来存储插件版本号 和 该插件最低要求WordPress的版本
define('LIKE_VERSION_NUM', '1.0');
define('LIKE_MINIMUM_WP_VERSION', '4.0');
define('LIKE_URL', plugins_url('', __FILE__));
define('LIKE_PATH', dirname(__FILE__));

require LIKE_PATH . '/widget_doyoulikeme.php';

// 声明全局变量$wpdb 和 数据表名常量
global $wpdb;
define('VOTES_NUM', $wpdb->prefix . 'votes_num');
define('VOTES_IP', $wpdb->prefix . 'votes_ip');

// 插件激活时，运行回调方法创建数据表, 在WP原有的options表中插入插件版本号
register_activation_hook(__FILE__, 'like_activation_cretable');
function like_activation_cretable() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $sql_1 = "CREATE TABLE ".VOTES_NUM." (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        likes int(10) NOT NULL DEFAULT '0',
        PRIMARY KEY  (id)
    ) $charset_collate;";
    $sql_2 = "CREATE TABLE ".VOTES_IP." (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        ip varchar(40) DEFAULT '' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql_1 );
    dbDelta( $sql_2 );
	
    // update_option()方法，在options表里如果不存在更新字段，则会创建该字段,存在则更新该字段
    update_option('like_version_num', LIKE_VERSION_NUM);
	
    $data['id'] = '1';
    $data['likes'] = '0';
    $wpdb->insert(VOTES_NUM, $data);
}

// 当加载插件时，运行回调方法检查插件版本是否有更新,
add_action('plugins_loaded', 'like_update_db_check');
function like_update_db_check() {
    // 获取到options表里的插件版本号 不等于 当前插件版本号时，运行创建表方法，更新数据库表
    if (get_option('like_version_num') != LIKE_VERSION_NUM) {
        like_activation_cretable();
    }
}

// 插件停用时，运行回调方法删除数据表，删除options表中的插件版本号
register_deactivation_hook(__FILE__, 'like_deactivation_deltable');
function like_deactivation_deltable() {
    delete_option('like_version_num');
}

?>
