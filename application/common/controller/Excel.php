<?php
/**
 * Created by zhaozhen.
 * Desc: 读取Excel文件中的内容
 * Date: 2018/4/4
 * Time: 20:40
 */

namespace app\common\controller;

class Excel
{

    public static function readExcel($url)
    {
        $res = array('res' => false, 'info' => '');
        //检测文件是否存在
        if (!file_exists($url)) {
            return $res['info'] = "ERROR : {$url} 不存在";
        }
        //加载文件
        vendor('PHPExcel.Classes.PHPExcel');
        $phpExcel = new \PHPExcel();
        $PHPReader = new \PHPExcel_Reader_Excel2007();
        if (!$PHPReader->canRead($url)) {
            $PHPReader = new \PHPExcel_Reader_Excel5();
            if (!$PHPReader->canRead($url)) {
                return $res['info'] = 'No Excel';
            }
        }
        $phpExcel = $PHPReader->load($url);
        //读取excel文件中的第一个工作表
        $currentSheet = $phpExcel->getSheet(0);
        //取得最大的列号
        $allColumn = $currentSheet->getHighestColumn();
        //取得一共有多少行
        $allRow = $currentSheet->getHighestRow();
        //从第二行开始输出，因为excel表中第一行为列名
        for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
            //从第A列开始输出
            $res_i = array();
            for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
                $val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65, $currentRow)->getValue();
                //记录列名称
                if ($currentRow == 1) {
                    if ($val == null) {
                        continue;
                    } else {
                        $res['colName'][] = $val;
                        continue;
                    }
                }
                //记录数据
                $res_i[] = $val;
                if ($currentColumn == $allColumn) {
                    $res['data'][] = $res_i;
                }
            }
        }
        //成功读取
        $res['res'] = true;
        //过滤除去空数据
        return Excel::filter_null_data($res);
    }

    /*
     * 对null数据按照列名称进行过滤
     */
    public static function filter_null_data($data)
    {
        $colLength = count($data['colName']);
        foreach ($data['data'] as $index => $content) {
            foreach ($content as $index_ => $content_) {
                if ($index_ >= $colLength) {
                    unset($data['data'][$index][$index_]);
                }
            }
        }
        return $data;
    }
}