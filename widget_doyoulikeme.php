<?php
class Like_Widget extends WP_Widget {
 
    function __construct() {
    	parent::__construct(
 
        // 小工具ID
        'do_you_like_me',
 
        // 小工具名称
        __('Do you like me?', 'doyoulikeme' ),
 
        // 小工具选项
        array (
            'description' => __( 'Do you like me?', 'doyoulikeme' )
        )
 
    );
      
    }
 
    function form( $instance ) {
?>
    	<p>
        	当前版本没有任何设置哦~
        </p>
<?php
    }
 
    function update( $new_instance, $old_instance ) {    
      	$instance = $old_instance;
    	return $instance;
    }
 
    function widget( $args, $instance ) {
		extract( $args );
    	echo $before_widget; 
?>
			<div class="textwidget custom-html-widget">
                <div class='like-vote'>
                    <p class='like-title'>Do you like me?</p>
                    <div class='like-count'>
                        <i class="demo-icon icon-heart"></i><span></span>
                    </div>
                </div>
            </div>
<?php
      	echo $after_widget; 
    }
 
}

function like_widget_init() {
 
    register_widget( 'Like_Widget' );
 
}
add_action( 'widgets_init', 'like_widget_init' );

add_action('wp_enqueue_scripts', 'like_scripts');
function like_scripts() {
    wp_enqueue_style('like-style', LIKE_URL . '/css/like.css', array(), LIKE_VERSION_NUM, 'all');
    wp_enqueue_script('like-script', LIKE_URL . '/js/like.js',  array('jquery') , LIKE_VERSION_NUM ,true);
}
?>