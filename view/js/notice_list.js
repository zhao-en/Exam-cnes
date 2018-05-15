/**
 * author : zhaozhen
 * time   : 2018.4.16
 */

/**
 * ready
 */
$(function () {

});

/**
 * vue
 * notice_content
 */
var notice_list_v = new Vue({
    el:'#notice_list',
    data:{
        notice_list_body:'<tr>\n' +
        '    <td>01</td>\n' +
        '    <td><a href="#">计算机科学与技术学院计算机网络课程公告01</a></td>\n' +
        '    <td>赵震</td>\n' +
        '    <td>2017-10-21</td>\n' +
        '</tr><tr>\n' +
        '    <td>01</td>\n' +
        '    <td><a href="#">计算机科学与技术学院计算机网络课程公告01</a></td>\n' +
        '    <td>赵震</td>\n' +
        '    <td>2017-10-21</td>\n' +
        '</tr><tr>\n' +
        '    <td>01</td>\n' +
        '    <td><a href="#">计算机科学与技术学院计算机网络课程公告01</a></td>\n' +
        '    <td>赵震</td>\n' +
        '    <td>2017-10-21</td>\n' +
        '</tr><tr>\n' +
        '    <td>01</td>\n' +
        '    <td><a href="#">计算机科学与技术学院计算机网络课程公告01</a></td>\n' +
        '    <td>赵震</td>\n' +
        '    <td>2017-10-21</td>\n' +
        '</tr><tr>\n' +
        '    <td>01</td>\n' +
        '    <td><a href="#">计算机科学与技术学院计算机网络课程公告01</a></td>\n' +
        '    <td>赵震</td>\n' +
        '    <td>2017-10-21</td>\n' +
        '</tr><tr>\n' +
        '    <td>01</td>\n' +
        '    <td><a href="#">计算机科学与技术学院计算机网络课程公告01</a></td>\n' +
        '    <td>赵震</td>\n' +
        '    <td>2017-10-21</td>\n' +
        '</tr><tr>\n' +
        '    <td>01</td>\n' +
        '    <td><a href="#">计算机科学与技术学院计算机网络课程公告01</a></td>\n' +
        '    <td>赵震</td>\n' +
        '    <td>2017-10-21</td>\n' +
        '</tr>',
        notice_list_page:'<li><a href="#">&laquo;</a></li>\n' +
        '                    <li><a href="javascript:void(0)" onclick="aClick()">1</a></li>\n' +
        '                    <li><a class="" href="#">2</a></li>\n' +
        '                    <li><a class="" href="#">...</a></li>\n' +
        '                    <li><a class="" href="#">5</a></li>\n' +
        '                    <li><a class="" href="#">...</a></li>\n' +
        '                    <li><a class="" href="#">10</a></li>\n' +
        '                    <li><a class="" href="#">&raquo;</a></li>'

    }
});

