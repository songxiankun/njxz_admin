<?php


namespace Admin\Controller;

use Admin\Model\DevicesModel;
use Admin\Service\DevicesService;
use Think\Upload;

/**
 * @brief 设备控制器
 * Class DevicesController
 * @package Admin\Controller
 */
class DevicesController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->service = new DevicesService();
        $this->mod = new DevicesModel();
    }

    /**
     * @brief 首页列表
     * @param array $data 数据
     */
    public function index($data = [])
    {
        // POST 请求
        if (IS_POST) {
            // 获取列表信息
            $message = $this->service->getList();
            // ajax 返回数据
            $this->ajaxReturn($message);
            return;
        }
        // 非POST请求 遍历传来的数组信息
        foreach ($data as $key => $value) {
            // 渲染数据到html
            $this->assign($key, $value);
        }
        // 渲染页面
        $this->render();
    }
    /**
     * @brief 设备编辑或更新
     * @param array $data
     */
    public function edit($data = array())
    {
        if(IS_POST) {
            $message = $this->service->edit();
            $this->ajaxReturn($message);
            return ;
        }
        // 非POST请求
        $id = I("get.id", 0);
        $info = array();
        if($id) {
            $info = $this->mod->getInfo($id);
        }else{
            foreach ($data as $key=>$val) {
                $info[$key] = $val;
            }
        }
        $this->assign('info',$info);
        $this->render();
    }


    /**
     * @brief   删除信息
     * @author  Songxk
     * @date    2020.06.05 22:43:09
     */
    public function drop()
    {
        // 接收post请求
        if(IS_POST) {
            $id = I('post.id');
            $info = $this->mod->getInfo($id);
            if($info) {
                $res = $this->mod->drop($id);
                if($res!==false) {
                    $this->ajaxReturn(message());
                    return ;
                }
            }
            $this->ajaxReturn(message($this->mod->getError(),false));
            return ;
        }
    }

    /**
     * @brief   批量删除信息
     * @author  Songxk
     * @date    2020.06.05 22:43:09
     */
    public function batchDrop()
    {
        if(IS_POST) {
            // 分割id
            $ids = explode(',', $_POST['id']);
            // 获取删除标志
            $changeAct = $_POST['changeAct'];
            if($changeAct == 0) {
                //删除
                $num = 0;
                foreach ($ids as $key => $val) {
                    $res = $this->mod->drop($val);
                    if($res!==false) $num++;
                }
                $message = message('本次共选择' . count($ids) . "个条数据,删除" . $num . "个");
                $this->ajaxReturn($message);
                return ;
            }else if($changeAct==1){
                //重置缓存
                foreach ($ids as $key => $val){
                    $this->mod->_cacheReset($val);
                }
                $message = message('重置缓存成功！');
                $this->ajaxReturn($message);
            }
            $this->ajaxReturn(message($this->mod->getError(),false));
            return ;
        }
    }

    /**
     * @brief  文件上传
     */
    public function import()
    {
        if (IS_POST) {  // post请求处理
            $upload = new Upload();
            $upload->maxSize = C('UPLOAD')['UPLOAD_FILE_SIZE'] * 1024;// 设置附件上传大小
            $upload->exts = ['xls','xlsx'];// 设置附件上传类型
            $upload->rootPath = FILE_PATH; // 设置附件上传根目录
            $upload->savePath = '/devices/';
            $upload->subName = "";
            $info = $upload->uploadOne($_FILES['file']);
            /**
             * array (size=9)
            'name' => string '用户表.xls' (length=13)
            'type' => string 'text/plain' (length=10)
            'size' => int 876
            'key' => int 0
            'ext' => string 'xls' (length=3)
            'md5' => string '1758acac9c9c58e2a43b8bacf1a88f63' (length=32)
            'sha1' => string '786c33ea828673843465b8cbaadbcde903ee112f' (length=40)
            'savename' => string '5eddb66c7d2dc.xls' (length=17)
            'savepath' => string '/temp/' (length=6)
             */
            if (!$info) {
                // 上传错误提示错误信息
                $this->ajaxReturn(message($upload->getError(), false));
            } else {
                $filePath = FILE_PATH . '/' .$info['savepath'] . $info['savename'];
                // 上传成功 获取上传文件信息
                // TODO excel数据操作 2020.06.08 待完成
                $readExcel = new ReadExcelController();
                // 读取数据 直接写入数据库
                $res = $readExcel->readExcel($filePath, 0);
                $this->ajaxReturn($res);
            }
        }
        // 渲染界面
        $this->render();
    }
}