<aside>
<section class="widget">
<h2>最新文章</h2>
<ul>
<?php $posts=get_posts('numberposts=5&orderby=post_date');
foreach ($posts as $post){
	echo '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
}
?>
</ul>
</section>
<section class="widget ">
<h2><?php _e('Categories')?></h2>
<ul class="categories">
<?php  
$args = array('orderby' => 'name','parent' => 0);
$categories = get_categories( $args );
foreach ( $categories as $category ) {
	echo '<li><a href="' . get_category_link( $category->term_id ) . '">' . $category->name . '<span class="num">'.$category->category_count.'</span></a></li>';
}
?>
</ul>
</section>
<section class="widget">
<h2>标签云</h2>
<div id="tagcloud">
<?php wp_tag_cloud('smallest=12&largest=24&unit=px&order=RAND&orderby=count'); ?>
</div>
</section>
<section class="widget">
<h2>热门文章</h2>
<ul>
<?php
$popular_post=get_popular_post(5);
foreach ($popular_post as $link=>$title){
	echo '<li><a href="'.$link.'">'.$title.'</a></li>';
}
?>
 </ul>
</section>
<section class="widget">
<h2>友情链接</h2>
<ul class="f_link">
	<?php the_friendly_link();?>
</ul>
</section>
</aside>
