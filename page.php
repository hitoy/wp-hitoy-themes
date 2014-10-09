<?php get_header();?>
<div class="main">
<div class="content">
<?php if (have_posts()) : the_post(); update_post_caches($posts); ?>
<div class="page_box">
<h1><?php the_title()?></h1>
<div class="single">
	<?php the_content()?>
</div>
</div>
<?php endif; ?>
<?php require_once(dirname(__FILE__).'/comments.php'); ?>
</div>
<?php get_sidebar()?>
</div>
<?php get_footer()?>
