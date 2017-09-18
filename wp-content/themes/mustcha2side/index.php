<?php
$data = array();
$q = new WP_Query("post_type=post");
while($q->have_posts()){
	global $post;
	$q->the_post();
	$post->my_excerpt = get_the_excerpt();
	$post->author = get_user_by( 'ID', $post->post_author)->display_name;
	$post->link = get_permalink();
 	$data['posts'][] = $post;
}

$data['templates'] = array('header.mustache', basename(__FILE__, '.php').".mustache", "footer.mustache");
$data['partials'] = array('post_summary' => 'post_summary.mustache');

autoRender($data);