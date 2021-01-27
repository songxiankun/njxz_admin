<?php


namespace Admin\Service;

use Admin\Model\BuildingModel;
use Admin\Model\ServiceModel;

class BuildingService extends ServiceModel
{
    public function __construct()
    {
        parent::__construct();
        $this->mod = new BuildingModel();
    }

    /**
     * @desc 获取数据列表
     * @param array $map
     * @param string $sort
     * @return array
     */
    public function getList($map = array(), $sort = "id desc")
    {
        // 获取查询条件 查询楼明 模糊匹配
        $params = I("post.name", 0);
        if ($params) {
            $map['name'] = array('like',"%$params%");
        }
        // 必要条件
        $map['mark'] = 1;

        //获取数据
        $result = $this->mod
            ->where($map)->order($sort)
            ->page(PAGE,PERPAGE)->getField("id",true);

        $list = [];
        if(is_array($result)) {
            foreach ($result as $val) {
                $info = $this->mod->getInfo($val);
                $list[] = $info;
            }
        }

        //获取数据总数
        $count = $this->mod->where($map)->count();

        //分页设置
        $limit = PERPAGE;
        $Page = new \Think\Page($count, $limit);
        //分页跳转的时候保证查询条件
        foreach($map as $key=>$val) {
            $Page->parameter[$key] = urlencode($val);
        }
        $show = $Page->show();
        //设置返回值
        $message = array(
            "msg"   => '操作成功',
            "code"  => 0 ,
            "data"  => $list,
            "count" => $count,
        );
        return $message;
    }


    /**
     * @desc 新增 ｜ 修改
     * @param array $data
     * @param false $is_sql
     * @return array
     */
    public function edit($data=array(),$is_sql=false) {
        if(!$data) {
            $data = I('post.', '', 'trim');
        }
        $error = '';
        $data['status'] = 1;
        $rowId = $this->mod->edit($data,$error,$is_sql);
        if($rowId) {
            return message();
        }
        return message($error,false);
    }
}