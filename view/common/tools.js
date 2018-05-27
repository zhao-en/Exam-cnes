/**
 * Author : en_zhao
 * Date   : 2018/5/7
 * Content: 工具
 */

var tools = new Vue({

    data:{
        serverUrl:'http://39.108.224.62/swustcnes/public/index.php/'
    },

    methods:{

        /**
         * 无关闭按钮
         * @param title
         * @param msg
         */
        warningC(title , msg) {
            $("#myModalLabel").html(title);
            $("#myModalBody").html(msg);
            $(function() {
                $('#myModal').modal({
                    keyboard: true
                })
            });
        },

        /**
         * 有关闭按钮
         * @param title
         * @param msg
         */
        warning(title , msg) {
            $("#myModalLabel").html(title);
            $("#myModalBody").html(msg);
            $("#myModalClose").empty();
            $(function() {
                $('#myModal').modal({
                    keyboard: true
                })
            });
        },

        /**
         * 判断是否为空
         * @param str
         * @returns {boolean}
         */
        isNull(str){
            return (str === null || str === undefined || str === '' || str === [])
        },

        /**
         * fetch请求
         */
        postFetch(module , data , callback){
            fetch(tools.serverUrl + module, {
                method:"post",//or 'GET'
                credentials: "include",//or "include","same-origin":只在请求同域中资源时成功，其他请求将被拒绝。
                headers: {
                    'cache-control': 'no-cache',
                    'content-type': 'application/x-www-form-urlencoded'
                },
                body:data
            }).then(function (response) {
                return response.json()
            }).then(function (data) {
                callback(data);
            }).catch(function (err) {
                tools.warningC("提示","连接失败")
            })
        },
        /**
         * fetch json
         */
        postJsonFetch(module , data , callback){
            fetch(tools.serverUrl + module, {
                method:"post",//or 'GET'
                credentials: "include",//or "include","same-origin":只在请求同域中资源时成功，其他请求将被拒绝。
                mode:'cors',
                headers: {
                    'cache-control': 'no-cache',
                    'content-type': 'application/json'
                },
                body:JSON.stringify(data)
            }).then(function (response) {
                return response.json()
            }).then(function (data) {
                callback(data);
            }).catch(function (err) {
                tools.warningC("提示","连接失败")
            })
        },
        /**
         * 上传文件
         */
        imgFetch(module , data , callback){
            fetch(tools.serverUrl + module, {
                method:"post",//or 'GET'
                body:data,
                credentials: "include",//or "include","same-origin":只在请求同域中资源时成功，其他请求将被拒绝。
            }).then(function (response) {
                return response.json()
            }).then(function (data) {
                callback(data);
            }).catch(function (err) {
                tools.warningC("提示","连接失败")
            })
        },
        /**
         * 获取url参数
         */
        getQueryString(name){
            var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if(r!=null)
                return  decodeURI(r[2]);
            return null;
        },

        /**
         * 清除所有cookies
         */
        clearAllCookie(){
            var keys=document.cookie.match(/[^ =;]+(?=\=)/g);
            if (keys) {
                for (var i = keys.length; i--;)
                    document.cookie=keys[i]+'=0;expires=' + new Date( 0).toUTCString()
            }
        },

        /**
         * 读取cookies
         */
        getCookie(ckey){
            var arr1 = document.cookie.split('; ');
            for(var i=0;i<arr1.length;i++){
                var arr2 = arr1[i].split('=');
                if(arr2[0] === ckey){
                    return decodeURI(arr2[1]);
                }
            }
        },

        /**
         * 获取一个可以上传的图片路径
         */
        getObjectURL(file) {
            var url = null;
            if (window.createObjectURL != undefined) { // basic
                url = window.createObjectURL(file);
            } else if (window.URL != undefined) { // mozilla(firefox)
                url = window.URL.createObjectURL(file);
            } else if (window.webkitURL != undefined) { // webkit or chrome
                url = window.webkitURL.createObjectURL(file);
            }
            return url;
        },
        //获取图片大小
        getImageSize(id,callback){
            var objUrl = tools.getObjectURL($("#" + id)[0].files[0]);
            img = new Image();
            img.src = objUrl;
            img.onload = function () {
                callback(this);
            };
        },

        //时间格式化
        formatDate(date, fmt) {
            if (/(y+)/.test(fmt)) {
                fmt = fmt.replace(RegExp.$1, (date.getFullYear() + '').substr(4 - RegExp.$1.length));
            }
            var o = {
                'M+': date.getMonth() + 1,
                'd+': date.getDate(),
                'h+': date.getHours(),
                'm+': date.getMinutes(),
                's+': date.getSeconds()
            };
            for (var k in o) {
                if (new RegExp(`(${k})`).test(fmt)) {
                    var str = o[k] + '';
                    fmt = fmt.replace(RegExp.$1, (RegExp.$1.length === 1) ? str : tools.padLeftZero(str));
                }
            }
            return fmt;
        },

        padLeftZero(str) {
            return ('00' + str).substr(str.length);
        }
    }

});