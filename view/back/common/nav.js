/**
 * Author : en_zhao
 * Date   : 2018/5/7
 * Content: header vue
 */

var vm_header = new Vue({
    el:'#vm_nav',
    data:{

    },
    methods:{
        /**
         * 注销 清除cookies
         */
        loginOut(){
            //清除服务器缓存
            tools.postFetch('admin/User/loginOut','',function (data) {
                //清除cookies
                tools.clearAllCookie();
                window.location.href = "./../login.html" //这里要填入相对于pre的路径
            });
        }
    }
});