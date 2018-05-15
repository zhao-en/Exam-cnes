/**
 * Author : en_zhao
 * Date   : 2018/5/7
 * Content:
 */

$.ajax({
    type : "get",
    url : "http://127.0.0.1/swustcnes/public/index.php/admin/Permission/htmlPermission",
    async : false,
    dataType:'json',
    success : function(data){
        if(data.res === false){
            window.location.href = './../404.html';
        }
    }
});