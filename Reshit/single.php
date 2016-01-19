<?php get_header();?>
<div class="content">
<main role="main">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article class="single">
        <header><h1><?php the_title();?></h1></header>
        <footer><span class="pubdate"><time datetime="<?php the_time('Y-m-d');?>" pubdate="pubdate"><?php the_time('Y年m月d日');?></time></span>&nbsp;<span class="category">分类:&nbsp;<?php the_category(', '); ?></span></footer>
        <div class="entry-content">
            <?php the_content();?>
        </div>
    </article>
<?php endwhile; endif ?>
<?php require_once(dirname(__FILE__).'/comments.php');?>
</main>
<?php get_sidebar();?>
</div>
<?php get_footer();?>

