<!DOCTYPE HTML>
<html <?php language_attributes();?>>
<head>
<meta charset="<?php bloginfo('charset');?>">
<title><?php if(is_home()&&!is_paged()){
	bloginfo("name");echo ' - ';bloginfo('description');
}else if(is_single()){
	the_title();echo ' - ';bloginfo('name');
}else if(is_page()){
	the_title();echo ' - ';bloginfo('name');
}else if(is_category()){
	single_cat_title();echo ' - ';bloginfo('name');
}else if(is_404()){
	echo "我勒个去，页面找不到了 - 404 Not Found";
}else if(is_search()){
	the_search_query();
}else if(is_tag()){
	single_tag_title();echo ' - ';bloginfo('name');
}else if(is_paged()){
	echo '第'.get_query_var('paged').'页 -> ';bloginfo("name");bloginfo('description');
}
?></title>
<?php the_keywords();?>
<?php the_description();?>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/style.css?v=1"/>
<script type="text/javascript" data-main="<?php bloginfo('template_url'); ?>/script/hitoy" src="<?php bloginfo('template_url'); ?>/script/require.js?v=2.1.11" defer async="async"></script>
</head>
<body>
<div class="head">
	<h1><?php bloginfo("name")?></h1>
	<h2><?php bloginfo('description')?></h2>
</div>
<?php the_head_menu();?>
