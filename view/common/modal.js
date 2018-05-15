/**
 * Author : en_zhao
 * Date   : 2018/5/7
 * Content: 插入模态框div
 */

var dom = document.querySelector("body");
dom.innerHTML += '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">\n' +
    '    <div class="modal-dialog">\n' +
    '        <div class="modal-content">\n' +
    '            <div class="modal-header">\n' +
    '                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>\n' +
    '                <h3 class="modal-title" id="myModalLabel"></h3>\n' +
    '            </div>\n' +
    '            <div class="modal-body" id="myModalBody"></div>\n' +
    '            <div class="modal-footer" id="myModalClose">\n' +
    '                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>\n' +
    '            </div>\n' +
    '        </div><!-- /.modal-content -->\n' +
    '    </div><!-- /.modal-dialog -->\n' +
    '</div>';