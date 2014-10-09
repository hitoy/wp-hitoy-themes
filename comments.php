<?php
if ( post_password_required() ) return;
$fields=array(
	'author' =>'<li><label>昵称:</label><input type="text" name="author"/><span>*</span></li>',
	'email' => '<li><label>邮箱:</label><input type="text" name="email"/><span>*</span></li>',
	'url' => '	<li><label>网站:</label><input type="text" name="url"/></li>'
);
$commentsarr=array("id_form"=>"","id_submit"=>"submit","fields"=>$fields,'comment_field'=>'<li><label>评论:</label><textarea name="comment"></textarea></li>','comment_notes_after'=>'','comment_notes_before'=>'');
?>
<div id="comments">
<?php comment_form($commentsarr); ?>
<ul id="comment_list">
<?php
$post_id=get_the_id();
$comments = get_comments("number=12&offset=0&status=approve&post_id=".$post_id);
foreach ($comments as $comment){
?>
<li class="comment_body" id="comment-<?php echo $comment->comment_ID?>">
			<div class="avatar"><?php echo get_avatar($comment,96); ?></div>
			<div class="comment_info">
				<?php if($comment->comment_author_url!=""){?>
				<cite><a href="<?php echo $comment->comment_author_url ?>" rel="external nofollow" class="url" target="_blank"><?php echo $comment->comment_author ?></a></cite>
				<?php } else { echo "<cite>".$comment->comment_author."</cite>";}?>
				<span class="com_date"><?php echo $comment->comment_date ?></span>
			</div>
			<div class="comment_content"><?php echo $comment->comment_content.comment_multistage_display($comment->comment_parent,5);?></div><div class="comment_action"><span>回复此留言</span></div></li>
<?php }?>
</ul>
<?php 
	if(get_comments_number($post_id)>12){
		echo '<script>var commentajaxurl="'.get_admin_url().'admin-ajax.php";var post_id="'.$post_id.'";</script>';
		echo '<button class="more_comments" >更多评论>></button>';
	}
?>
</div>
