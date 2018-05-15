/**
 * Author : en_zhao
 * Date   : 2018/4/22
 * Content: common navbar
 */

var navbarHtml = "<nav class=\"navbar navbar-default\" role=\"navigation\">\n" +
    "    <div class=\"container-fluid\" id='vm_nav'>\n" +
    "        <div class=\"col-lg-3 text-left col-sm-12 col-xs-12\">\n" +
    "            <div class=\"navbar-header\">\n" +
    "                <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\"#navbar-collapse\">\n" +
    "                    <span class=\"sr-only\">切换导航</span>\n" +
    "                    <span class=\"icon-bar\"></span>\n" +
    "                    <span class=\"icon-bar\"></span>\n" +
    "                    <span class=\"icon-bar\"></span>\n" +
    "                </button>\n" +
    "                <a class=\"navbar-brand\" href=\"#\">welcome,赵震 <span class=\"btn-default this_zhuxiao\" v-on:click='loginOut'>注销</span></a>\n" +
    "            </div>\n" +
    "        </div>\n" +
    "        <div class=\"col-lg-6 col-lg-offset-1 col-sm-12 col-xs-12\">\n" +
    "            <div class=\"collapse navbar-collapse\" id=\"navbar-collapse\">\n" +
    "                <ul class=\"nav navbar-nav\">\n" +
    "                    <li><a class=\"btn btn-link\" href=\"./index_index.html\">首页信息</a></li>\n" +
    "\n" +
    "                    <li class=\"dropdown\">\n" +
    "                        <a class=\"dropdown-toggle btn btn-link this_gerenxinxi\" href=\"#\" data-toggle=\"dropdown\">\n" +
    "                            信息管理\n" +
    "                            <b class=\"caret\"></b>\n" +
    "                        </a>\n" +
    "                        <ul class=\"dropdown-menu\" style=\"border-radius: 5px\">\n" +
    "                            <li class=\"text-center\"><a href=\"./my_info.html\" style=\"border-radius: 3px\">信息查看</a></li>\n" +
    "                            <li class=\"text-center\"><a href=\"./my_save_password.html\">修改密码</a></li>\n" +
    "                            <li class=\"text-center\"><a href=\"./my_save_password.html\">用户列表</a></li>\n" +
    "                        </ul>\n" +
    "                    </li>\n" +
    "\n" +
    "                    <li class=\"dropdown\">\n" +
    "                        <a class=\"dropdown-toggle btn btn-link this_kaoshimokuai\" href=\"#\" data-toggle=\"dropdown\">\n" +
    "                            考试管理\n" +
    "                            <b class=\"caret\"></b>\n" +
    "                        </a>\n" +
    "                        <ul class=\"dropdown-menu\">\n" +
    "                            <li><span class=\"this_erjicaidan\">-习题练习</span></li>\n" +
    "                            <li class=\"text-center\"><a href=\"#\">添加习题</a></li>\n" +
    "                            <li class=\"text-center\"><a href=\"#\">习题列表</a></li>\n" +
    "                            <li class=\"text-center\"><a href=\"#\">练习反馈</a></li>\n" +
    "                            <li class=\"divider\"></li>\n" +
    "                            <li><span class=\"this_erjicaidan\">-考前测评</span></li>\n" +
    "                            <li class=\"text-center\"><a href=\"#\">添加测评</a></li>\n" +
    "                            <li class=\"text-center\"><a href=\"#\">测评查看</a></li>\n" +
    "                            <li class=\"text-center\"><a href=\"#\">测评反馈</a></li>\n" +
    "                            <li class=\"divider\"></li>\n" +
    "                            <li><span class=\"this_erjicaidan\">-正式考试</span></li>\n" +
    "                            <li class=\"text-center\"><a href=\"#\">添加考试</a></li>\n" +
    "                            <li class=\"text-center\"><a href=\"#\">考试查看</a></li>\n" +
    "                            <li class=\"text-center\"><a href=\"#\">考试反馈</a></li>\n" +
    "                        </ul>\n" +
    "\n" +
    "                    </li>\n" +
    "                    <li class=\"dropdown\">\n" +
    "                        <a class=\"dropdown-toggle btn btn-link this_kaoshimokuai\" href=\"#\" data-toggle=\"dropdown\">\n" +
    "                            资料管理\n" +
    "                            <b class=\"caret\"></b>\n" +
    "                        </a>\n" +
    "                        <ul class=\"dropdown-menu\">\n" +
    "                            <li class=\"text-center\"><a href=\"./down_files_list.html\">资料浏览</a></li>\n" +
    "                            <li class=\"text-center\"><a href=\"./down_files_update.html\">资料上传</a></li>\n" +
    "                        </ul>\n" +
    "\n" +
    "                    </li>\n" +
    "                    <li class=\"dropdown\">\n" +
    "                        <a class=\"dropdown-toggle btn btn-link this_kaoshimokuai\" href=\"#\" data-toggle=\"dropdown\">\n" +
    "                            公告管理\n" +
    "                            <b class=\"caret\"></b>\n" +
    "                        </a>\n" +
    "                        <ul class=\"dropdown-menu\">\n" +
    "                            <li class=\"text-center\"><a href=\"./notice_list.html\">公告管理</a></li>\n" +
    "                            <li class=\"text-center\"><a href=\"./notice_edit.html\">公告编辑</a></li>\n" +
    "                        </ul>\n" +
    "\n" +
    "                    </li>\n" +
    "                </ul>\n" +
    "            </div>\n" +
    "        </div>\n" +
    "    </div>\n" +
    "</nav>";

$(".this_navbar").html(navbarHtml);