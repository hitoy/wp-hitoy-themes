<?php get_header();?>
<div class="main">
<div class="content">
<?php if (have_posts()) : the_post(); update_post_caches($posts); ?>
	<div class="post">
		<h1><?php the_title()?></h1>
		<div class="postmeta"><time datetime="<?php the_time('Y-m-d')?>"><?php the_time('Y年m月d日')?></time>&nbsp;分类: <?php the_category(', ')?> &nbsp;<span class="view_count">共<?php post_views(get_the_ID());?></span>
</div>
		<div class="single">
		<?php the_content();?>
		</div>
	</div>
<?php endif; ?>
<?php require_once(dirname(__FILE__).'/comments.php'); ?>
</div>
<?php get_sidebar()?>
</div>
<?php get_footer()?>
