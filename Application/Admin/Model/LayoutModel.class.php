<?php

/**
 * 布局管理-模型
 */
namespace Admin\Model;
use Common\Model\CBaseModel;
class LayoutModel extends CBaseModel {
    function __construct() {
        parent::__construct('layout');
    }
    
    //自动验证
    protected $_validate = array(
        array('page_id', 'require', '请选择页面位置！', self::EXISTS_VALIDATE, '', 3),
        array('loc_id', 'require', '页面位置编号不能为空！', self::EXISTS_VALIDATE, '', 3),
        array('type', 'require', '请选择推荐类型！', self::EXISTS_VALIDATE, '', 3),
        array('type_id', 'require', '请选择推荐内容！', self::EXISTS_VALIDATE, '', 3),
    );
    
    
    /**
     * 获取缓存信息
     * (non-PHPdoc)
     * @see \Common\Model\CBaseModel::getInfo()
     */
    function getInfo($id) {
        $info = parent::getInfo($id,true);
        if($info) {
            
            //图片
            if($info['image']) {
                $info['image_url'] = IMG_URL . $info['image'];
            }
            
            //类型名称
            if($info['type']) {
                $info['type_name'] = C('SYSTEM_RECOMM_TYPE')[$info['type']];
            }
            
            //获取推荐对象
            if($info['type']==1) {
                //CMS文章
                
            }else{
                //TODO...
            }
            
            //页面位置
            if($info['page_id']) {
                $itemInfo = M("item")->find($info['page_id']);
                $info['page_name'] = $itemInfo['name'];
            }
            
            //页面编号
            $locInfo = M("layoutDesc")->where([
                'page_id'   =>$info['page_id'],
                'loc_id'    =>$info['loc_id']
            ])->find();
            if($locInfo) {
                $info['loc_name'] = $locInfo['loc_desc'] . "=>" . $info['loc_id'];
            }
            
        }
        return $info;
    }
    
}