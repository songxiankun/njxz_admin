<?php


namespace Admin\Service;

use Admin\Model\DevicesModel;
use Admin\Model\ServiceModel;

class DevicesService extends ServiceModel
{
    public function __construct()
    {
        parent::__construct();
        $this->mod = new DevicesModel();
    }

    /**
     * @param array $map    查询条件
     * @param string $sort  默认id升序
     * @return array
     */
    public function getList($map = array(), $sort = "id desc")
    {
        // 获取查询条件
        $params = I("post.num", 0);
        if ($params) {
            $map['num'] = array('like',"%$params%");
        }
        // 添加判断数据表mark的有效性
        $map['mark'] = 1;

        //获取数据
        $result = $this->mod
            ->where($map)->order($sort)
            ->page(PAGE,PERPAGE)->getField("id",true);

        $list = [];
        if(is_array($result)) {
            foreach ($result as $val) {
                $info = $this->mod->getInfo($val, true);
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
     * @brief 设备编辑或更新服务层
     * @param array $data
     * @param false $is_sql
     * @return array
     */
    public function edit($data = array(), $is_sql = false)
    {
        if(!$data) {
            $data = I('post.', '', 'trim');
        }

        // 数据格式化
        // 元->分
        $data['money'] = (isset($data['money']) && $data['money']) ? \Zeus::formatToCent($data['money']) : 0;
        // 获取时间检测
        $data['achieve_time'] = (isset($data['achieve_time']) && $data['achieve_time']) ?
            strtotime($data['achieve_time']) : null;
        // 使用状态
        $data['is_use'] = (isset($data['is_use']) && $data['is_use'] == "on") ? 1 : 0;

        $error = '';
        $rowId = $this->mod->edit($data,$error,$is_sql);
        if($rowId) {
            return message();
        }
        return message($error,false);
    }
}