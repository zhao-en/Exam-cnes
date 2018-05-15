/**
 * Author : en_zhao
 * Date   : 2018/4/26
 * Content: 图片的放大缩小
 */
var imgsObj = $('.amplifyImg img');//需要放大的图像
if(imgsObj){
    $.each(imgsObj,function(){
        $(this).click(function(){
            var currImg = $(this);
            coverLayer(1);
            var tempContainer = $('<div class=tempContainer></div>');//图片容器
            with(tempContainer){//width方法等同于$(this)
                appendTo("body");
                var windowWidth=$(window).width();
                var windowHeight=$(window).height();
                //获取图片原始宽度、高度
                var orignImg = new Image();
                orignImg.src =currImg.attr("src") ;
                var currImgWidth= orignImg.width;
                var currImgHeight = orignImg.height;
                if(currImgWidth<windowWidth){//为了让图片不失真，当图片宽度较小的时候，保留原图
                    if(currImgHeight<windowHeight){
                        var topHeight=(windowHeight-currImgHeight)/2;
                        if(topHeight>35){/*此处为了使图片高度上居中显示在整个手机屏幕中：因为在android,ios的微信中会有一个title导航，35为title导航的高度*/
                            topHeight=topHeight-35;
                            css('top',topHeight);
                        }else{
                            css('top',0);
                        }
                        html('<img border=0 src=' + currImg.attr('src') + '>');
                    }else{
                        css('top',0);
                        html('<img border=0 src=' + currImg.attr('src') + ' height='+windowHeight+'>');
                    }
                }else{
                    var currImgChangeHeight=(currImgHeight*windowWidth)/currImgWidth;
                    if(currImgChangeHeight<windowHeight){
                        var topHeight=(windowHeight-currImgChangeHeight)/2;
                        if(topHeight>35){
                            topHeight=topHeight-35;
                            css('top',topHeight);
                        }else{
                            css('top',0);
                        }
                        html('<img border=0 src=' + currImg.attr('src') + ' width='+windowWidth+';>');
                    }else{
                        css('top',0);
                        html('<img border=0 src=' + currImg.attr('src') + ' width='+windowWidth+'; height='+windowHeight+'>');
                    }
                }
            }
            tempContainer.click(function(){
                $(this).remove();
                coverLayer(0);
            });
        });
    });
}
//使用禁用蒙层效果
function coverLayer(tag){
    with($('.over')){
        if(tag==1){
            css('height',$(document).height());
            css('display','block');
            css('opacity',1);
            css("background-color","#191919");
        }
        else{
            css('display','none');
        }
    }
}