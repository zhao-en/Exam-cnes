/**
 * Author : en_zhao
 * Date   : 2018/4/16
 * Content: 注册
 */

var register_content_v = new Vue({
    el:"#register_content",
    data:{
        account:'',
        email:'',
        password:'',
        password_c:''
    },
    methods:{
        register:function () {
            alert('注册测试成功');
        }
    }

});