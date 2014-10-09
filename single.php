<?php get_header();?>
<div class="main">
<div class="content">
<?php if (have_posts()) : the_post(); update_post_caches($posts); ?>
	<div class="post">
		<h1><?php the_title()?></h1>
		<div class="postmeta"><time datetime="<?php the_time('Y-m-d')?>"><?php the_time('Y年m月d日')?></time>&nbsp;分类: <?php the_category(', ')?> &nbsp;<span class="view_count">共<?php post_views(get_the_ID());?></span>
<div class="share">
	<div class="bdsharebuttonbox"><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"></a><a href="#" class="bds_bdysc" data-cmd="bdysc" title="分享到百度云收藏"></a><a href="#" class="bds_copy" data-cmd="copy" title="分享到复制网址"></a><a href="#" class="bds_more" data-cmd="more"></a></div>
</div>
</div>
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
<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"16"},"share":{},"image":{"viewList":["weixin","tqq","tsina","sqq","bdysc","copy"],"viewText":"分享到：","viewSize":"16"},"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["weixin","tqq","tsina","sqq","bdysc","copy"]}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
