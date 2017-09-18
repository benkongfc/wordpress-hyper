<?php
$data = array();
global $post;
$data['post'] = $post;

$data['templates'] = array('header.mustache', basename(__FILE__, '.php').".mustache", "footer.mustache");

autoRender($data);