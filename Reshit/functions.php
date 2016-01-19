<?php
//主题安装时需要初始化的主题添加选项
function theme_install_callback($old_theme_name, $old_theme=false){
	add_option("home_keywords","","yes");
	add_option("home_description","","yes");
	add_option("global_add_text","","yes");
	add_option("friendly_link","","yes");
}
//主题卸载时需要调用的函数
function theme_unstall_callback(){
	delete_option('home_keywords');
	delete_option('home_description');
	delete_option('global_add_text');
	delete_option('friendly_link');
}
//获取访问次数最多的文章
function get_popular_post($count=5){
	global	$wpdb;
	$ids= $wpdb->get_results("select post_id from $wpdb->postmeta where meta_key='views' ORDER BY meta_value DESC limit $count");
	$link=array();
	$title=array();
	foreach($ids as $id){
		array_push($link,get_permalink($id->post_id));
		array_push($title,get_post_field("post_title",$id->post_id));
	}
	return array_combine($link,$title);
}
//导航菜单的生成
function the_head_menu(){
	wp_nav_menu(array('theme_location'=>'header-menu','container_class'=>'primary','container'=>'nav','items_wrap'=>'<ul>%3$s</ul>','fallback_cb'=>'defalutmenu')); 
}
//当系统没有指定菜单的时，默认的菜单样式
function defalutmenu(){
	$menutext='<ul><li><a href="'.home_url() .'">'.__('Home').'</a></li></ul>';
	echo $menutext;
}
//代码展示插件
function code_show($content){
	$content=str_replace("&#038;","&",$content);
	$exp="/(<pre[^>]*>)([\s\S]*?)(?=<\/pre>)(<\/pre>)/i";
	if(preg_match_all($exp,$content,$matches)){
		foreach ($matches[2] as $match){
			$codehtml=htmlspecialchars($match);
			$replacement=$codehtml;
			$content=str_replace($match,$replacement,$content);
		}
	}
	return $content;
}
//外链提示图标
function external_show($content){
	$exp="/<a.*?href=([^\s>]+)/i";
	$addstyle = ' rel="external nofollow" class="external"';
	if(preg_match_all($exp,$content,$matches)){
		foreach ($matches[1] as $match){
			$href=trim($match,"\",',\n,\r,\t");
			if(preg_match("/^\w/",$href)&&!strpos($href,trim($_SERVER['HTTP_HOST']))){
				$content = str_replace($match,$match.$addstyle,$content);
			}
		}
	}
	$content  = str_replace($addstyle.$addstyle,$addstyle,$content);
	return $content;
}
//异步加载留言
function asyn_loading_comments(){
	if($_POST['action']=='asyn_loading_comments'){
		$page=(int) $_POST["page"];
		$page=($page-1)*12;
		$post_id=(int) $_POST["post_id"];
		$comments = get_comments("number=12&offset=".$page."&status=approve&post_id=".$post_id);
		header("Content-Type: application/json");
		if($comments){
			$jsondata=array();
			foreach($comments as $comment){
                $parent=loads_parent_comments($comment,2);
				$comment_arr= array('cid'=>$comment->comment_ID,'curl'=>$comment->comment_author_url,'cauthor'=>$comment->comment_author,'cdate'=>$comment->comment_date,'content'=>$comment->comment_content,'avatar'=>get_avatar($comment,96,"",$comment->comment_author),'parentcoment'=>$parent);
				array_push($jsondata,$comment_arr);
			}
			echo json_encode($jsondata);
		}else{
			echo "null";
		}
		exit();
	}else {
		return ;
	}
}

//获取子评论
function loads_parent_comments($comment,$count){
    if(empty($comment->comment_parent)) return '';
    if($count == 1){
        $arr=get_comment($comment->comment_parent,OBJECT);
        return array('cid'=>$arr->comment_ID,'curl'=>$arr->comment_author_url,'cauthor'=>$arr->comment_author,'cdate'=>$arr->comment_date,'content'=>$arr->comment_content,'avatar'=>get_avatar($arr,96,"",$arr->comment_author),'parentcoment'=>null);
    }
    else{
        $arr=get_comment($comment->comment_parent,OBJECT);
        $count--;
        return array('cid'=>$arr->comment_ID,'curl'=>$arr->comment_author_url,'cauthor'=>$arr->comment_author,'cdate'=>$arr->comment_date,'content'=>$arr->comment_content,'avatar'=>get_avatar($arr,96,"",$arr->comment_author),'parentcoment'=>loads_parent_comments($arr,$count));
    }
}


//留言成功之后跳转
function comment_redirect(){
	return false;
}
//垃圾留言验证:基于JS时间
function comment_verify($commentdata){
	$postdate=isset($_POST['verification'])?$_POST['verification']:0;
	$postdate=round($postdate/1000);
	$nowdate=time();
	$lag=$nowdate-$postdate;
	if(is_admin()){
		return $commentdata;
	}else if($lag>86400 || $lag<-86400 ){
		wp_die("You have been banned for commenting, Please try again later!","Prohibit Access",array('response'=>403));
	}else{
		return $commentdata;
	}
}
//主题的后台设置选项
function theme_option(){
	add_theme_page("主题设置","主题选项","edit_theme_options","theme_options","theme_option_page");
}
function theme_option_page(){
	?>
		<div class="wrap">
		<h2>主题设置</h2>
		<form action="options.php" method="POST">
		<?php wp_nonce_field('update-options'); ?>  
		<input type='hidden' name='page_options' value='home_keywords,home_description,global_add_text,friendly_link'/>
		<input type="hidden" name="action" value="update" />   
		<table class="form-table">
		<tr><th scope="row">首页关键词</th><td><input name="home_keywords" type="text" style="width:80%" value="<?php echo get_option('home_keywords')?>"/></td></tr>
		<tr><th scope="row">网站描述</th><td><fieldset><p><label for="moderation_keys">如果添加描述，系统会自动把描述当中description添加到首页当中。</label><textarea name="home_description" class="large-text code" rows="5" cols="50"><?php echo get_option('home_description')?></textarea></p></fieldset></td></tr>
		<tr><th scope="row">全局JS代码</th><td><fieldset><label for="moderation_keys">可以把网站统计等全局JS代码添加到此处，系统会自动把这些代码加入到所有页面当中。</label><p><textarea name="global_add_text" class="large-text code" rows="10" cols="50"><?php echo get_option('global_add_text')?></textarea></p></fieldset></td></tr>
		<tr><th scope="row">友情链接</th><td><fieldset><label for="moderation_keys">把友情链接添加到下列文本框中，系统会自动在首页加入相应的友情链接。</label><p><textarea name="friendly_link" class="large-text code" rows="10" cols="50"><?php echo get_option('friendly_link')?></textarea></p></fieldset></td></tr>
		</table>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes') ?>"></p>
		<form></div>
		<?php
}
//全局JS代码添加到footer上
function the_global_add_text(){
	echo get_option('global_add_text');
}
//调用友情链接
function the_friendly_link(){
	$link=get_option('friendly_link');
	$arr=explode("</a>",$link);
	foreach ($arr as $slink){
		if($slink=='') continue;
		echo "<li>$slink</a></li>";
	}
}
//页面显示关键词
function the_keywords(){
	if(is_home()&&($home_keywords=get_option('home_keywords'))!=""){
		echo '<meta name="keywords" content="'.$home_keywords.'"/>'."\n";
	}else if(is_single()){
		$tags=get_the_tags();
		if($tags){
			$keywords=array();
			foreach ($tags as $tag){
				$keywords[]=$tag->name;
			}
			$single_keywords=implode(",",$keywords);
			echo '<meta name="keywords" content="'.$single_keywords.'"/>'."\n";
		}
	}
}
//页面显示描述
function the_description(){
	if(is_home()&&($home_description=get_option('home_description'))!=''){
		echo '<meta name="description" content="'.htmlspecialchars($home_description).'"/>'."\n";
	}else if(is_single()||is_page()){
		if ($post->post_excerpt) {
			$description =$post->post_excerpt;
		} else {
			global $post;
			$content=$post->post_content;
			$description = str_replace("\n","",mb_strimwidth(strip_tags($content),0,180));
		}
		echo '<meta name="description" content="'.$description.'"/>'."\n";
	}
}
//留言嵌套显示
function comment_multistage_display($comment_pid,$count){
	$count--;
	if($comment_pid>0 && $count>0){
		$parentcoment=get_comment($comment_pid,OBJECT);
		$content="<div class=\"avatar\">".get_avatar($parentcoment,96,"",$parentcoment->comment_author)."</div><address>";
		if($parentcoment->comment_author_url!=""){
			$content .= "<a href=\"".$parentcoment->comment_author_url."\" rel=\"external nofollow\" class=\"url\" target=\"_blank\">".$parentcoment->comment_author."</a>";
		}else{
			$content .= $parentcoment->comment_author;
		}
		$content .= "<time datetime=\"".$parentcoment->comment_date."\" pubdate=\"pubdate\">".$parentcoment->comment_date."</time></address><i>".$parentcoment->comment_content."</i>";
		if($parentcoment->comment_parent>0){
			return "<div class=\"innermsg\">".$content.comment_multistage_display($parentcoment->comment_parent,$count)."</div>";
		}else if($parentcoment){
			return "<div class=\"innermsg\">".$content."</div>";
		}else {
			return ;
		}
	}else {
		return;
	}
}

//去除get_avatar的width和height属性
function remove_dimensions_avatars($avatar) {
	$avatar = preg_replace("/(width|height)=\'\d*\'\s/", "",$avatar);
	return $avatar;
}

//初始化的动作
add_action('after_switch_theme','theme_install_callback');
register_nav_menus(array('header-menu' => __( '导航自定义菜单')));
add_action('init','asyn_loading_comments');

//进入后台时的动作
add_action('admin_menu','theme_option');
//查看文章时的动作
add_filter('the_content','code_show');
add_filter('the_content','external_show');

//留言时的动作
add_action('preprocess_comment','comment_verify');
//add_action('comment_post_redirect','comment_redirect');

//换掉主题时的操作
add_action('switch_theme','theme_unstall_callback');

add_filter('get_avatar', 'remove_dimensions_avatars', 10 );
