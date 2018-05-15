/**
 * Author : en_zhao
 * Date   : 2018/4/16
 * Content: 找回密码
 */

var find_password_content_v = new Vue({
    el:'#find_password_content',
    data:{
        email:''
    },
    methods:{
        /**
         * 发送邮件
         */
        send_mail:function () {
            alert("发送邮件 测试成功");
        }
    }
});

