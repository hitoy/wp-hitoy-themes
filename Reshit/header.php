<!DOCTYPE HTML>
<html <?php language_attributes();?>>
<head>
<meta charset="<?php bloginfo('charset');?>">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="applicable-device" content="pc,mobile">
<meta name="referrer" content="always">
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
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_url'); ?>/style.css?v=1"/>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/hjs.js?v=1" defer></script>
</head>
<body>
<!--[if lt IE 9]><div class="notsupport">您的浏览器版本过低，本站不再支持，请使用高级浏览器!</div><![endif]-->
<header class="siteheader">
    <div class="top">
        <a href="/" rel="home"><?php bloginfo("name")?></a>
        <?php the_head_menu();?>

        <form action="/" method="GET">
            <input type="text" name="s" placeholder="搜索" required="required"/>
            <input type="submit" value=""/>
        </form>
    </div>
</header>
