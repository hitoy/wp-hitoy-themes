//DOM查找库，By hito http://www.hitoy.org/
(function(){
    //getClass
    function getClass(fa,classname){
        if(!fa) return [];
        if(document.getElementByClassName){
            return fa.getElementByClassName(classname);
        }
        var dom = fa.getElementsByTagName("*");
        var classdom = new Array();
        for (var i =0 ; i<dom.length ; i++){
            if (dom[i].className === classname){
                classdom.push(dom[i]);
            }
        }
        return classdom;
    }
    //选择器
    function hjs(selector){
        if(selector.match(/^#./)){
            var id=selector.substr(1);
            return new hjs.fn.init(document.getElementById(id));
        } else if (selector.match(/^\./)){
            var clas=selector.substr(1);
            return new hjs.fn.init(getClass(document,clas));
        }else {
            return new hjs.fn.init(document.getElementsByTagName(selector));
        }
    };
    hjs.prototype.constructor=hjs;
    hjs.fn = hjs.prototype = {
        author:"Hito",
        init:function(object){
            if(!(object instanceof Array)){
                this[0]=object;
                this.length=1;
            }else{
            for(var i =0; i < object.length; i++){
                this[i]=object[i];
            }
            this.length=object.length;
	 }
            return this;
        }
    };
    hjs.fn.init.prototype=hjs.fn;

    hjs.prototype.child=function(selector){
        var target = new Array();
        for (var i = 0 ; i< this.length; i++){
            //拆解成DOM对象
            var domobj=this[i];
            if(selector.match(/^\../)){
                var clas=selector.substr(1);
                target =  target.concat(getClass(this[i],clas));
            }else if(selector.match(/^#./)){
                var id = selector.substr(1);
                var child = domobj.childNodes;
                for(var j =0 ;j < child.length; j++){
                    if(child[j].id === id){
                        target =  target.concat(child[j]);
                    }
                }
            }else{
                var child = domobj.childNodes;
                for(var j =0 ;j < child.length; j++){
                    if(child[j].nodeName.toLowerCase() === selector){
                        target =  target.concat(child[j]);
                    }
                }
            }
        }
        if(target.length){
            return new hjs.fn.init(target);
        }else{
            return [];
        }
    }
    window.hjs = hjs;
})();

function trim(str){
    if(String.prototype.trim){
        return str.trim();
    }else{
        return str.replace(/^\s*/).replace(/\s*$/);
    }
}

//代码展示功能
function codedisplay(code){
    var codehtml="<ul class=\"codebox\">";
    var codelist=trim(code).split('\n');

    for(var i =0 ; i < codelist.length; i++){
        codehtml += "<li><code>"+codelist[i]+"</code></li>";
    }
    codehtml += "</ul>";
    return trim(codehtml);
}

//comment回调函数,json格式
function appendcomment(comment){
    if(!comment['parentcoment']){
        var author = (comment.curl == "") ? comment.cauthor : '<a href="'+comment.curl+'" rel="external nofollow" class="url" target="_blank">'+comment.cauthor+'</a>';
        return '<div class="avatar">'+comment.avatar+'</div><address>'+author+'<time datetime="'+comment.cdate+'">'+comment.cdate+'</time></address><i>'+comment.content+'</i>';
    }else{
        var author = (comment.curl == "") ? comment.cauthor : '<a href="'+comment.curl+'" rel="external nofollow" class="url" target="_blank">'+comment.cauthor+'</a>';
        return '<div class="avatar">'+comment.avatar+'</div><address>'+author+'<time datetime="'+comment.cdate+'">'+comment.cdate+'</time></address><i>'+comment.content+'</i><div class="innermsg">'+appendcomment(comment['parentcoment'])+'</div>';
    }
}


var commentpage=2;
//发送函数
function sendmsg(url,postid,callback){
    var xmlhttp;
    if(window.XMLHttpRequest){
        xmlhttp=new XMLHttpRequest();
    }else{
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.open("POST",url,true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("action=asyn_loading_comments&post_id="+postid+"&page="+commentpage+"&callback="+callback);
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            commentpage++;
            msgobj = eval("("+xmlhttp.responseText+")");
            if(msgobj.length < 12 ) {
                hjs('#comment_list').child(".load-comment")[0].style.display="none";
            }
            //appendcomment(msgobj);
            for(var i =0; i< msgobj.length; i++){
                var article=document.createElement("article");
                article.className="comment_body";
                article.id="comment-"+msgobj[i].cid;
                article.innerHTML=appendcomment(msgobj[i])+'\n<div class="reply">回复此留言</div>';
                hjs("#comment_list")[0].insertBefore(article,hjs('#comment_list').child('.load-comment')[0]);
            }
        }else if(xmlhttp.readyState==4 && xmlhttp.status!=200){
            console.log('连接出错，请检查网络!');
        }
    }
}

//动作
document.addEventListener("DOMContentLoaded",function(){
    //替换代码
    var codebox = hjs(".entry-content").child('pre') || null;
    if(codebox){
        for(var i =0 ; i < codebox.length; i++){
            codebox[i].innerHTML=codedisplay(codebox[i].innerHTML);
        }
    }
    //异步加载留言
    var load= hjs('#comment_list').child(".load-comment")[0] || null;
    if(load){
        load.addEventListener("click",function(){
            var postid=load.getAttribute('data-commentid');
            sendmsg('//www.hitoy.org/',postid,'appendcomment');
        },false);
    }

    //回复留言
    var replycomment=hjs("#comment_list")[0] || null;
    if(replycomment){
        replycomment.addEventListener("click",function(ev){
            var e = ev || window.event;
            var target = e.target || e.srcElement;
            if(target.className == "reply"){
                var replyid=target.parentNode.id.substr(8);
                var replyname=target.parentNode.getElementsByTagName('address')[0].innerText.match(/\D+/);
                hjs(".form-submit").child("#comment_parent")[0].value=replyid;
                hjs("#respond").child("#reply-title")[0].innerHTML="回复"+replyname;
                document.body.scrollTop=hjs("#respond")[0].offsetTop;
            }
        },false);
    }

    //添加用于处理垃圾留言的验证
    var msgform = hjs("#respond-form")[0] || null;
    if(msgform){
        msgform.addEventListener("submit",function(ev){
		this.elements['verification'].value=new Date().getTime();
        });
    }

    document.removeEventListener("DOMContentLoaded",arguments.callee,false);
},false);
