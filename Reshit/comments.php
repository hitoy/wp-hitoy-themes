<?php
if(post_password_required()) return;

$fields=array(
        'author' =>'<p><label>昵称:<span>*</span></label><input type="text" name="author" required="required"/></p>',
        'email' => '<p><label>邮箱:<span>*</span></label><input type="email" name="email" required="required"/></p>',
        'url' => '<p><label>网站:</label><input type="url" name="url"/></p>'
);
$commentsarr=array("id_form"=>"respond-form","id_submit"=>"submit","fields"=>$fields,'comment_field'=>'<p><label>评论:</label><textarea name="comment" required="required"></textarea></p><input type="hidden" name="verification"/>','comment_notes_after'=>'','comment_notes_before'=>'');

?>
<div id="comments">
<?php comment_form($commentsarr); ?>

<section id="comment_list">
<?php
$post_id=get_the_id();
$comments = get_comments("number=12&offset=0&status=approve&post_id=".$post_id);

if(!empty($comments)){echo "<header><h3>评论列表:</h3></header>";}

foreach ($comments as $comment){
?>
    <article class="comment_body" id="comment-<?php echo $comment->comment_ID;?>">
    <div class="avatar"><?php echo get_avatar($comment,96,"",$comment->comment_author); ?></div>
    <address>
    <?php 
    if($comment->comment_author_url!=""){?>
        <a href="<?php echo $comment->comment_author_url ?>" rel="external nofollow" class="url" target="_blank"><?php echo $comment->comment_author ?></a>
   <?php }else{ ?>
        <?php echo $comment->comment_author;?>
    <?php }?>
        <time datetime="<?php echo $comment->comment_date ?>" pubdate="pubdate"><?php echo $comment->comment_date ?></time>
    </address>
    <i><?php echo $comment->comment_content;?></i>
    <?php echo comment_multistage_display($comment->comment_parent,3);?>
    <div class="reply">回复此留言</div>
    </article>
<?php
}
if(get_comments_number() > 12){
    echo "<button class=\"load-comment\" data-commentid=".$post_id.">加载更多</button>";
}
?> 
</section>
</div>
