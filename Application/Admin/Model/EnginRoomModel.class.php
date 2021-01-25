<?php


namespace Admin\Model;

use Common\Model\CBaseModel;
use Think\Model;

class EnginRoomModel extends CBaseModel
{
    protected $builfModel;
    /**
     * @var BuildingModel
     */
    private $buildModel;

    public function __construct($table = "engin_room")
    {
        parent::__construct($table);
        $this->buildModel = new BuildingModel();
    }

    /**
     * @desc 获取信息
     * @param $id
     * @param false $flag
     * @return mixed
     */
    public function getInfo($id, $flag = false)
    {
        $info = $this->getFuncCache("info", $id);
        if($info) {
            //添加时间
            if(isset($info['add_time']) && $info['add_time']) {
                $info['format_add_time'] = date('Y-m-d H:i:s',$info['add_time']);
            }

            //更新时间
            if(isset($info['upd_time']) && $info['upd_time']) {
                $info['format_upd_time'] = date('Y-m-d H:i:s',$info['upd_time']);
            }

            // 获取楼层
            $building_name = $this->buildModel->field('name')->where(['id' => $info['building_id']])->find()['name'];

            if ($building_name)
            {
                $info['building_name'] = $building_name;
            }

            // 机房负责任呢
            $info['format_admin_name'] = $this->getSystemAdminName($info['admin_id']);

            //获取系统操作人信息
            if($flag) {
                //添加人
                if($info['add_user']) {
                    $info['format_add_user'] = $this->getSystemAdminName($info['add_user']);
                }

                //更新人
                if($info['upd_user']) {
                    $info['format_upd_user'] = $this->getSystemAdminName($info['upd_user']);
                }
            }

        }
        return $info;
    }

    /**
     * @desc 更新 ｜ 新增
     * @param $data
     * @param string $error
     * @param false $is_sql
     * @return mixed|bool|int|string
     */
    public function edit($data, &$error = '', $is_sql = false)
    {
        $id = (int)$data['id'];
        if($id) {
            if(empty($data['upd_time'])) {
                $data['upd_time'] = time();
            }
            if (empty($data['upd_user'])) {
                $data['upd_user'] = (int)$_SESSION['adminId'];
            }
        } else {
            if(empty($data['add_time'])) {
                $data['add_time'] = time();
            }
            if (empty($data['add_user'])) {
                $data['add_user'] = (int)$_SESSION['adminId'];
            }
        }

        //格式化表数据
        $this->formatData($data, $id);

        //数据表验证
        if(!$this->create($data)) {
            $error = $this->getError();
            return 0;
        }

        //数据入库处理
        if($id) {
            //修改数据
            $result = $this->where("id={$id}")->save($data);
            $rowId = $id;
            if($is_sql)
                echo $this->_sql();
        }else{
            //新增数据
            $result = $this->add($data);
            $rowId = $result;
            if($is_sql) echo $this->_sql();

        }
        if($result!==false) {
            //重置缓存
            $data['id'] = $rowId;
            file_put_contents(TEMP_PATH . "1.txt", json_encode($data));
            $this->_cacheReset($rowId, $data, $id);
        }
        return $rowId;
    }
}