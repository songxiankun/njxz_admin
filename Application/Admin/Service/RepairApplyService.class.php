<?php


namespace Admin\Service;


use Admin\Model\AdminDepModel;
use Admin\Model\AdminOrgModel;
use Admin\Model\BuildingModel;
use Admin\Model\DevicesModel;
use Admin\Model\DevicesTypeModel;
use Admin\Model\EnginRoomModel;
use Admin\Model\RepairApplyModel;
use Admin\Model\ServiceModel;

/**
 * Class RepairApplyService  服务层
 * @package Admin\Service
 */
class RepairApplyService extends ServiceModel
{
    public function __construct()
    {
        parent::__construct();
        $this->mod = new RepairApplyModel();
    }

    /**
     * @desc 获取列表信息
     * @param array $map
     * @param string $sort
     * @return array
     */
    public function getList($map = array(), $sort = "id desc")
    {
        // TODO 查询条件
        $map['mark'] = 1;

        //获取数据
        $result = $this->mod
            ->where($map)->order($sort)
            ->page(PAGE,PERPAGE)->getField("id",true);

        $list = [];
        if(is_array($result)) {
            foreach ($result as $val) {
                $info = $this->mod->getInfo($val, true);
                $info = $this->formatData($info);
                //var_dump($info);die();
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
     * @desc 格式化数据
     * @param array $data
     * @return array
     */
    public function formatData($data = array())
    {
        if (empty($data))
        {
            return $data;
        }
        // 数据处理
        // 获取学校信息
        if (isset($data['organize_id']) && $data['organize_id'])
        {
            $org = new AdminOrgModel();
            $data['organize_name'] = $org->field('name')
                ->where(['mark' => 1, 'id' => $data['organize_id']])->find()['name'];
        }

        // 获取部门信息
        if (isset($data['department_id']) && $data['department_id'])
        {
            $dept = new AdminDepModel();
            $data['department_name'] = $dept->field('name')
                ->where(['mark' => 1, 'id'=>$data['department_id']])->find()['name'];
        }

        // 获取楼层信息
        if (isset($data['building_id']) && $data['building_id'])
        {
            $build = new BuildingModel();
            $data['building_name'] = $build->field('name')
                ->where(['mark'=>1,'id'=>$data['building_id']])->find()['name'];
        }
        // 获取楼层信息
        if (isset($data['engin_room_id']) && $data['engin_room_id'])
        {
            $engin = new EnginRoomModel();
            $data['engin_room'] = $engin->field('name')
                ->where(['mark'=>1,'id'=>$data['engin_room_id']])
                ->find()['name'];
        }

        // 获取详情信息
        if (isset($data['device_detail']) && $data['device_detail']) {

        }

        return $data;
    }
}