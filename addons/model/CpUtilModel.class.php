<?php
/**
 * Created by PhpStorm.
 * User: ouyangyu
 * Date: 15-5-1
 * Time: 下午4:18
 */

class CpUtilModel extends Model {




    public function readExcel($filePath,Array $indexArray) {

        tsload(ADDON_PATH.'/library/PHPExcel/IOFactory.class.php');
        $objPHPExcel = PHPExcel_IOFactory::load($filePath);
        /**读取excel文件中的第一个工作表*/
        $currentSheet = $objPHPExcel->getActiveSheet();
        /**取得最大的列号*/
        $allColumn = $currentSheet->getHighestColumn();
        /**取得一共有多少行*/
        $allRow = $currentSheet->getHighestRow();


        $allColumn = chr(64+count($indexArray));

        //循环读取每个单元格的内容。注意行从1开始，列从A开始,基于从第三行开始
        //field字段是指第二行的，2.$rowIndex;
        for($rowIndex=2;$rowIndex<=$allRow;$rowIndex++){
            for($colIndex='A';$colIndex<=$allColumn;$colIndex++){
                $addr = $colIndex.$rowIndex;
                //$fieldAddr = $colIndex.'2';
                $cell = $currentSheet->getCell($addr)->getValue();
                if(empty($cell)) {
                    return false;
                }
                //$field = $currentSheet->getCell($fieldAddr)->getValue();
                if($cell instanceof PHPExcel_RichText)     //富文本转换字符串
                    $cell = $cell->__toString();
                else {
                    $cell = strval($cell);
                }

                $data[$rowIndex-2][$indexArray[$colIndex]] = $cell;
            }
        }

        return $data;

    }



    public function upload($type) {
        import('ORG.Net.UploadFile');
        // 实例化上传类
        $upload = new UploadFile();
        // 设置附件上传大小
        //$upload->maxSize  = 3145728 ;
        // 设置附件上传类型
        $upload->allowExts  = array('xls','xlsx');
        // 设置附件上传目录
        $mkdir = UPLOAD_PATH.'/'.date('Y',time()).'/'.date('m',time()).date('d',time())."/$type/";

        //$upload->savePath =  $mkdir;

        if(!$upload->upload($mkdir)) {// 上传错误提示错误信息
            $result['status'] = false;
            $result['data'] = "上传失败！";
        }else{// 上传成功
            //上传成功 获取上传文件

            //文件上传信息
            $info = $upload->getUploadFileInfo();
            //上传文件数量
            //$fileCount = count($info);
            $result['status'] = true;
            $result['data'] = $mkdir.$info[0]['savename'];
            return $result;
            /*for ($i = 0; $i < $fileCount; $i++) {
                $savename=$info[$i]['savename'];
                $file = $mkdir.$savename;

            }*/

        }
    }

    /**
     * 下载文件
     * @param string $file
     *               被下载文件的路径
     * @param string $name
     *               用户看到的文件名
     */
    public function download($file,$name=''){
        $fileName = $name ? $name : pathinfo($file,PATHINFO_FILENAME);
        $filePath = realpath($file);

        $fp = fopen($filePath,'rb');

        if(!$filePath || !$fp){
            header('HTTP/1.1 404 Not Found');
            echo "Error: 404 Not Found.(server file path error)<!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding -->";
            exit;
        }

        $fileName = $fileName .'.'. pathinfo($filePath,PATHINFO_EXTENSION);
        $encoded_filename = urlencode($fileName);
        $encoded_filename = str_replace("+", "%20", $encoded_filename);

        header('HTTP/1.1 200 OK');
        header( "Pragma: public" );
        header( "Expires: 0" );
        header("Content-type: application/octet-stream");
        header("Content-Length: ".filesize($filePath));
        header("Accept-Ranges: bytes");
        header("Accept-Length: ".filesize($filePath));

        $ua = $_SERVER["HTTP_USER_AGENT"];
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } else if (preg_match("/Firefox/", $ua)) {
            header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
        }

        // ob_end_clean(); <--有些情况可能需要调用此函数
        // 输出文件内容
        fpassthru($fp);
        exit;
    }
}