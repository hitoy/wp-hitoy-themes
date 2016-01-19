<?php get_header(); ?>
<div class="main">
<div class="content">
<h1 class="category"><?php _e("Tags");?> › <?php single_tag_title();?></h1>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
		<h2><a class="title" href="<?php the_permalink();?>" rel="bookmark"><?php the_title();?></a></h2>
		<div class="description">
		<?php the_content('',FALSE,''); ?>	
		</p>
			</div>
			<div class="info">
				<span class="date"><?php the_time(__('Y-m-d')) ?></span>
				<span class="comments"><?php comments_popup_link()?></span>
				<span class="views"><?php post_views(get_the_ID());?></span>
				<a class="more" href="<?php the_permalink()?>"><?php _e('Read more...')?></a>
			</div>
		</div>
<?php endwhile; else : ?>
	<div class="errorbox">
		<?php _e('Sorry, no posts matched your criteria.'); ?>
	</div>
<?php endif; ?>
<div class="page">
<?php posts_nav_link(); ?>
</div>
</div>
<?php get_sidebar()?>
</div>
<?php get_footer()?>
