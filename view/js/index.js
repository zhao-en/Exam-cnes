/**
 *author : zhaozhen
 *time   ： 2018.4.16
 **/

/**
 * Vue
 **/

var login_content_v = new Vue({
    el:'#login_content',
    data:{
        account:'',
        password:''
    },
    methods:{
        login:function () {
            alert('登陆测试成功');
        }
    }
});