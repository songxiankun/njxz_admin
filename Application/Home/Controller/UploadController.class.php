<?php


namespace Home\Controller;

use Think\Upload;

/**
 * Class UploadController
 * @package Home\Controller
 */
class UploadController extends BaseController
{
    public function __construct($flag = true)
    {
        parent::__construct($flag);
    }

    /**
     * 单文件上传
     */
    function uploadImg()
    {
        $config = array(
            'maxSize'    =>    C('UPLOAD')['UPLOAD_IMG_SIZE'] * 1024,
            'rootPath'   =>    IMG_PATH,
            'savePath'   =>    '/web/',
            'saveName'   =>    array('uniqid',''),
            'exts'       =>    explode('|', C('UPLOAD')['UPLOAD_IMG_EXT']),
            'subName'    =>    "",
        );
        $upload = new Upload($config);  // 实例化上传类

        $info = $upload->uploadOne($_FILES['file']);
        if (!$info) {
            // 上传错误提示错误信息
            //$this->error($upload->getError());
            $this->ajaxReturn(message($upload->getError(), false));
        } else {
            // 上传成功 获取上传文件信息
            $filePath = $info['savepath'] . $info['savename'];
            if (strpos($filePath, IMG_URL) === FALSE) {
                $filePath = IMG_URL . $filePath;
            }
            $this->ajaxReturn(message('上传成功', true, $filePath));
        }
    }

    /**
     * Notes: 批量上传附件
     * User: songxk
     * Date: 2019-05-30
     * Time: 17:14
     * FuncName: uploadFile()
     */
    function uploadFile()
    {
        $upload = new Upload();                                                     // 实例化上传类
        $upload->maxSize = C('UPLOAD')['UPLOAD_FILE_SIZE'] * 1024;                  // 设置附件上传大小
        $upload->exts = explode('|', C('UPLOAD')['UPLOAD_IMG_EXT']);      // 设置附件上传类型
        $upload->rootPath = IMG_PATH; // 设置附件上传根目录
        $upload->savePath = '/web/';
        $upload->subName = "";
        $info = $upload->uploadOne($_FILES['file']);
        if (!$info) {
            // 上传错误提示错误信息
            $this->ajaxReturn(message($upload->getError(), false));
        } else {
            // 上传成功 获取上传文件信息
            $filePath = $info['savepath'] . $info['savename'];
            if (strpos($filePath, IMG_URL) === FALSE) {
                $filePath = IMG_URL . $filePath;
            }
            $data = [
                'fileName' => $info['name'],
                'savePath' => $filePath,
            ];
            $this->ajaxReturn(message('上传成功', true, $data));
        }
    }
}