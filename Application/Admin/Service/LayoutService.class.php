<?php

/**
 * 布局-服务类
 */
namespace Admin\Service;
use Admin\Model\ServiceModel;
use Admin\Model\LayoutModel;
class LayoutService extends ServiceModel {
    function __construct() {
        parent::__construct();
        $this->mod = new LayoutModel();
    }
    
    /**
     * 获取数据列表
     * @see \Admin\Model\BaseModel::getList()
     */
    function getList() {
        
        //TODO...
        
        return parent::getList();
    }
    
    /**
     * 添加或编辑
     * @see \Admin\Model\BaseModel::edit()
     */
    function edit() {
        $data = I('post.', '', 'trim');
        $image = trim($data['image']);
        if(!$data['id'] && !$image) {
            return message('请上传封面',false);
        }
        
        if(strpos($image, "temp")) {
            $data['image'] = \Zeus::saveImage($image, 'layout');
        }
        
        return parent::edit($data);
    }
    
}