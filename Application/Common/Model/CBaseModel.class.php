<?php

/**
 * 基类扩展-模型
 */
namespace Common\Model;

use Admin\Model\AdminModel;


class CBaseModel extends BaseModel {
    function __construct($table) {
        parent::__construct($table);
    }

    /**
     * 添加或编辑
     *
     * @param $data
     * @param string $error
     * @param bool $is_sql
     * @return bool|int|mixed|string
     */
    public function edit($data, &$error='',$is_sql=false) {
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

    /**
     * 格式化编辑的数据
     *
     * @param $data '要格式话的数据
     * @param int $id 编号
     * @param string $table 带前缀的表名
     * @return array :multitype:Ambigous <number, string, Ambigous <string, Ambigous <number, unknown>>>
     */
    public function formatData(&$data, $id=0, $table="") {
        $dataList = array();
        $tables = $table ? explode(",", $table) : array($this->getTableName());
        $newData = array();
        foreach ($tables as $table) {
            $tempData = array();
            $fieldInfoList = $this->getFieldInfoList($table);
            foreach ($fieldInfoList as $field=>$fieldInfo) {
                if ($field == "id") continue;
                //对强制
                if (isset($data[$field])) {
                    if ($fieldInfo['type']=="int") {
                        $newData[$field] = (int) $data[$field];
                    } else {
                        $newData[$field] = (string) $data[$field];
                    }
                }
                if (!isset($data[$field]) && in_array($field, array('upd_time','add_time'))) {
                    continue;
                }
                //插入数据-设置默认值
                if (!$id && !isset($data[$field])) {
                    $newData[$field] = $fieldInfo['default'];
                }
                if (isset($newData[$field])) {
                    $tempData[$field] = $newData[$field];
                }
            }
            $dataList[] = $tempData;
        }
        $data = $newData;
        return $dataList;
    }
    
    /**
     * 获取字段信息列表
     */
    public function getFieldInfoList($table="") {
        $table = $table ? $table : $this->getTableName();
        $fieldList = $this->query("SHOW FIELDS FROM {$table}");
        $infoList = array();
        foreach ($fieldList as $row) {
            if ((strpos($row['type'], "int") === false) || (strpos($row['type'], "bigint") !== false)) {
                $type = "string";
                $default = $row['default'] ? $row['default'] : "";
            } else {
                $type = "int";
                $default = $row['default'] ? $row['default'] : 0;
            }
            $infoList[$row['field']] = array(
                'type'=>$type,
                'default'=>$default
            );
        }
        return $infoList;
    }

    /**
     * 获取信息
     * @param $id
     * @param bool $flag
     * @return mixed
     */
    public function getInfo($id,$flag=false) {
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

            //'order_time' => string '1598398048' (length=10)
            if(isset($info['order_time']) && $info['order_time']) {
                $info['format_order_time'] = date('Y-m-d H:i:s',$info['order_time']);
            }

            //'receive_time' => string '1598398048' (length=10)
            if(isset($info['receive_time']) && $info['receive_time']) {
                $info['format_receive_time'] = date('Y-m-d H:i:s',$info['receive_time']);
            }

            // 'end_time' => string '1598398999' (length=10)
            if(isset($info['end_time']) && $info['end_time']) {
                $info['format_end_time'] = date('Y-m-d H:i:s',$info['end_time']);
            }

            //   'sign_time' => string '1598398048' (length=10)
            if(isset($info['sign_time']) && $info['sign_time']) {
                $info['format_sign_time'] = date('Y-m-d H:i:s', $info['sign_time']);
            }
            
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

    public function getAdmin($id){
        $admin = new AdminModel();
        $username = $admin->field('username')->where("id={$id}")->find();
        return $username['username'];
    }
    /**
     * 获取系统操作人名称
     */
    public function getSystemAdminName($adminId) {
        if(!$adminId) return '';
        $adminMod = new AdminModel();
        $adminList = $adminMod->getAll();
        return $adminList[$adminId]['realname'];
    }
    
    /**
     * 获取单条数据
     */
    public function getRowByAttr($map=[], $fields="*", $id=false) {
        //设置默认查询条件
        $map['mark'] = 1;
        if($id) {
            $map['id'] = array('neq',$id);
        }

        $info = $this->field($fields)->where($map)->find();
        return $info;
    }
    
    /**
     * 获取总数
     */
    public function getCount($map=[],$is_sql=false) {
        //查询条件
        if(is_array($map)) {
            $map['mark'] = 1;
        }else{
            $map .= " AND mark=1 ";
        }
        $count = $this->where($map)->count();
        if($is_sql) echo $this->_sql();
        return (int)$count;
    }
    
    /**
     * 计算总和
     */
    public function getSum($map=[],$field,$is_sql=false) {
        //查询条件
        if(is_array($map)) {
            $map['mark'] = 1;
        }else{
            $map .= " AND mark=1 ";
        }
        $result = $this->where($map)->sum($field);
        if($is_sql) echo $this->_sql();
        return $result;
    }
    
    /**
     * 获取分页数据
     */
    public function pageData() {
        
    }

    /**
     * 通用删除方法【物理删除】
     *
     * @param $id
     * @param bool $is_sql
     * @return bool|false|int|mixed|string
     */
    public function drop($id,$is_sql=false){
        //$rs = $this->delete($id);
        $result = $this->where("id={$id}")->setField('mark','0');
        if($is_sql) echo $this->_sql();
        if($result!==false) {
            //删除成功
            $this->_cacheDelete($id);
        }
        return $result;
    }
}