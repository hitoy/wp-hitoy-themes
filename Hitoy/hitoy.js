require.config({
    paths:{
              'jquery':'jquery.min' 
          }
})
require(['jquery'], function ($){
    $(function(){
        //code display
        $(".content").find("pre").each(function(){
            var code=$.trim($(this).html());
            var code_arr=code.split("\n");
            var code_c="<div class=\"codebox\"><ul class=\"codedisplay\">";
            for (var i=0;i<code_arr.length;i++){
                var linenum=i+1;
                if(i%2==1){
                    code_c+="<li style=\"background:#fff;margin:0\"><code>"+code_arr[i]+"</code></li>"
                }else{
                    code_c+="<li style=\"background:#efefef;margin:0\"><code>"+code_arr[i]+"</code></li>"
                }
            }
            code_c+="</ul></div>";
            $(this).html(code_c);
        });

        //back to top function
        var scrollbtn="<div id='go_top_btn' title='返回顶部'></div>";
        $(scrollbtn).appendTo("body");
        $("#go_top_btn").click(function(){
            $("body,html").animate({scrollTop:0},300);
        });
        $(window).scroll(function(){
            var topd=$(window).scrollTop();
            var height=$(window).height();
            if(topd>height/2){
                $("#go_top_btn").css("display","block");
            }else{
                $("#go_top_btn").css("display","none");
            }
        })

        //Weixin CR Code Show
        $("#feeds li:eq(1)").click(function(){
            //如果对象已经插入，则停止相应	
            if($('#weixincode').length=0) return
            //插入图片
            var imgsrc=$(this).find("img").attr("codeimg");
        var weixin_code='<img id="weixincode" src="'+imgsrc+'" width="146" height="146" style="position:absolute;display:none" title="点击关闭"/>';
        $(weixin_code).appendTo("body");
        //计算图片位置并显示
        var offsetdata=$(this).offset();
        var offtop=offsetdata.top;
        var offleft=offsetdata.left;
        var weiwidth=$('#weixincode').width();
        var weiheight=$('#weixincode').height();
        var positiontop=offtop-(weiheight-$(this).height())/2+"px";
        var positionleft=offleft-(weiwidth-$(this).width())/2+"px";
        $('#weixincode').css({"left":positionleft,"top":positiontop}).show(30);
        $('#weixincode').click(function(){
            $('#weixincode').remove();
        });
        });

        //Message Reply Function
        $("#comment_list").delegate("span","click",function(){
            var commentid=$(this).parents(".comment_body").attr('id').replace("comment-","");
            $('#respond #comment_parent').val(commentid);
            $("#respond .comment_body").remove();
            $("#respond .closereplay").remove();
            $(this).parents(".comment_body").clone().prependTo("#respond").css("border-bottom","none");
            $('<div style="float:right;width:28px;height:28px;font-size:28px;font-weight:bold;color:white;cursor:pointer;position:absolute;right:5px;top:5px;z-index:168"title="关闭回复" class="closereplay">X</div>').prependTo("#respond");
            $("#respond .comment_body .comment_action").remove();
            $("#respond .comment_body").removeAttr("id");
            $("#respond").css({"border-radius":"5px","background":"#97C8F3"});
            $("#respond .comment-reply-title").text("回复此留言");
            $("#comments textarea[name='comment']").focus();
            $("#respond .closereplay").click(function(){
                $(this).remove();
                $("#respond").removeAttr('style').find(".comment_body").remove();
                $('#respond #comment_parent').val("0");
                $("#respond .comment-reply-title").text("发表评论");
            })
        });

        //ajax submit comments
        $(".form-submit input#submit").click(function(){
            var name=$.trim($(".comment-form input[name='author']").val());
            var email=$.trim($(".comment-form input[name='email']").val());
            var url=$.trim($(".comment-form input[name='url']").val());
            var comment=$.trim($(".comment-form textarea[name='comment']").val());//+"<div class=\"innermsg\">"+$("#respond .comment_body").html()+"</div>";
            var comment_post_ID=$(".comment-form input[name='comment_post_ID']").val();
            var comment_parent=$(".comment-form input[name='comment_parent']").val();
            var verification=new Date().getTime();
            if(name==""&&$("p.logged-in-as").length==0){
                $(".comment-form input[name='author']").focus();
            }else if(!email.match(/[^\s\n]+\@[^\s\n]+\.(\w{2,4})$/)&&$("p.logged-in-as").length==0){
                $(".comment-form input[name='email']").focus();
            }else if(comment==""){
                $(".comment-form textarea[name='comment']").focus();
            }else{
                $.ajax({
                    type:"post",
                    url:$("#comments .comment-form").attr('action'),
                    data:{'author':name,'email':email,'url':url,'comment':comment,'comment_post_ID':comment_post_ID,'comment_parent':comment_parent,'verification':verification},
                    dataType:"html",
                    success:function(){
                        function fulldisplay(arg){
                            if(arg<10) return "0"+arg;
                            return arg;
                        }
                        var now=new Date();
                        var nowdate=now.getFullYear()+"-"+fulldisplay(now.getMonth())+"-"+fulldisplay(now.getDate())+"  "+fulldisplay(now.getHours())+":"+fulldisplay(now.getMinutes())+":"+fulldisplay(now.getSeconds());
                        var	html='<li class="comment_body" id="comment-'+comment_post_ID+'"><div class="avatar"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAIAAAD8GO2jAAAABnRSTlMAAAAAAABupgeRAAAAGUlEQVRIie3BMQEAAADCoPVP7WENoAAAAG4MIAAByKKI/gAAAABJRU5ErkJggg==" height="96" width="96"/></div><div class="comment_info"><a href="'+url+'" rel="external nofollow" class="url">'+name+'</a><span class="com_date">'+nowdate+'</span></div><div class="comment_content">'+comment+'<br/><center>您的评论正在审核中...</center></div></li>';
                        $(html).prependTo('#comment_list');
                        $("#respond").removeAttr('style').find(".comment_body").remove();
                        $("#respond .closereplay").remove();
                        $(".comment-form textarea[name='comment']").val("");
                        $('#respond #comment_parent').val("0");
                        $("#respond .comment-reply-title").text("发表评论");
                    },
                    error:function(a,b,c){
                              alert("提交失败，请稍后重试!");
                          }
                })
            }
            return false;
        })

        //comments qiantao
        function showlist(data){
            if(!data.parentcoment){
                return '<div class="innermsg"><div class="avatar">'+data.avatar+'</div><div class="comment_info"><cite><a href="'+data.curl+'" rel="external nofollow" class="url">'+data.cauthor+'</a></cite><span class="com_date">'+data.cdate+'</span></div><div class="comment_content">'+data.content+'</div></div>';
            }else{
                return '<div class="innermsg"><div class="avatar">'+data.avatar+'</div><div class="comment_info"><cite><a href="'+data.curl+'" rel="external nofollow" class="url">'+data.cauthor+'</a></cite><span class="com_date">'+data.cdate+'</span></div><div class="comment_content">'+data.content+showlist(data.parentcoment)+'</div></div>';
            }
        }

        //Load the comments	ajax 
        var page=2;
        $('.more_comments').click(function(){
            $.ajax({
                type: "post",
                url: commentajaxurl,	
                data: {'page':page,'post_id':post_id,'action':'asyn_loading_comments'},
                success:function(data){
                    if(data){
                        var html="";
                        for (var i in data){
                            if(!data[i].parentcoment){
                                html+='<li class="comment_body" id="comment-'+data[i].cid+'"><div class="avatar">'+data[i].avatar+'</div><div class="comment_info"><cite><a href="'+data[i].curl+'" rel="external nofollow" class="url">'+data[i].cauthor+'</a></cite><span class="com_date">'+data[i].cdate+'</span></div><div class="comment_content">'+data[i].content+'</div><div class="comment_action"><span>回复此留言</span></div></li>';
                            }else{
                                var internal=showlist(data[i]).replace(/^<div[\s]class="innermsg">/i,'').replace(/<\/div>$/i,'');
                                html += '<li class="comment_body" id="comment-'+data.cid+'">'+internal+'<div class="comment_action"><span>回复此留言</span></div></li>';
                            }
                        }
                        //alert(html);
                        $(html).appendTo('#comment_list');
                        if(data.length < 12)
                $('.more_comments').text("没有了...").fadeOut(1000);
                    }else{
                        $('.more_comments').text("没有了...").fadeOut(1000);
                    }
                },
                dataType:"json"
            })
            page++;
        })
    });
});
