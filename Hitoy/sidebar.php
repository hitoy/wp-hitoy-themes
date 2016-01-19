<div class="side">
<div class="subscribe">
		<h3>订阅博客内容</h3>
		<ul id="feeds">
			<li><a href="<?php bloginfo('rss2_url')?>" rel="rss"><img src="<?php bloginfo('template_url'); ?>/images/feed_ico.png" title="订阅RSS" alt="RSS"/></a></li>
			<li><img src="<?php bloginfo('template_url'); ?>/images/weixin_ico.png" title="订阅到微信"  alt="Weixin ICO" codeimg="<?php bloginfo('template_url');?>/images/weixin_code.png"/></li>
		</ul>
</div>
<div class="widget">
		<h3>最新文章</h3>
		<ul>
<?php $posts=get_posts('numberposts=5&orderby=post_date');
foreach ($posts as $post){
	echo '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
}
?>
		</ul>
</div>
<div class="widget">
	<h3><?php _e('Categories')?></h3>
		<ul class="categories">
<?php  
$args = array('orderby' => 'name','parent' => 0);
$categories = get_categories( $args );
foreach ( $categories as $category ) {
	echo '<li><a href="' . get_category_link( $category->term_id ) . '">' . $category->name . '<span class="num">'.$category->category_count.'</span></a></li>';
}
?>
		</ul>
</div>
<div class="widget">
		<h3>标签云</h3>
		<div id="tagcloud">
	<?php wp_tag_cloud('smallest=12&largest=24&unit=px&order=RAND&orderby=count'); ?>
		</div>
	</div>
<div class="widget">
	<h3>热门文章</h3>
	<ul>
<?php
$popular_post=get_popular_post(5);
foreach ($popular_post as $link=>$title){
	echo '<li><a href="'.$link.'">'.$title.'</a></li>';
}
?>
	</ul>
</div>
<?php if(is_home()){?>
		<div class="widget">
			<h3>友情链接</h3>
			<ul class="f_link">
				<?php the_friendly_link();?>
			</ul>
		</div>
<?php }?>
</div>
<div style="clear:both"></div>
