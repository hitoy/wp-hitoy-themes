<?php get_header();?>
<div class="content">
<main role="main">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article id="post-<?php the_ID();?>" class="post">
            <header><h2><a href="<?php the_permalink();?>"><?php the_title();?></a></h2></header>
            <div class="entry-content">
            <?php the_content('...阅读更多>>');?>
            </div>
            <footer class="entry-footer">
                <span class="pubdate"><time datetime="<?php the_time('Y-m-d');?>" pubdate="pubdate"><?php the_time('Y年m月d日');?></time></span>
                <span class="category"><?php the_category(', '); ?></span>
                <span class="comments"><?php comments_popup_link("添加评论","1条评论","%条评论")?></span>
            </footer>
    </article>
<?php endwhile; else : ?>
        <div class="errorbox">
                <?php _e('Sorry, no posts matched your criteria.'); ?>
        </div>
<?php endif; ?>
<nav class="navigation">
    <?php posts_nav_link(' '); ?>
</nav>
</main>
<?php get_sidebar();?>
</div>
<?php get_footer();?>
