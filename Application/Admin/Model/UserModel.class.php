<?php


namespace Admin\Model;

use Common\Model\CBaseModel;

class UserModel extends CBaseModel
{
    public function __construct($table = 'user')
    {
        parent::__construct($table);
    }


    public function getInfo($id, $flag = false)
    {
        // 获取信息
        $info = parent::getInfo($id);
        if ($info) {
            //头像
            if ($info['avatar']) {
                $info['avatar_url'] = IMG_URL . $info['avatar'];
            }

            //最后登陆日期
            if ($info['last_login_time']) {
                $info['format_last_login_time'] = date('Y-m-d H:i:s', $info['last_login_time']);
            }

            //添加时间
            if (isset($info['add_time']) && $info['add_time']) {
                $info['format_add_time'] = date('Y-m-d H:i:s', $info['add_time']);
            }

            //更新时间
            if (isset($info['upd_time']) && $info['upd_time']) {
                $info['format_upd_time'] = date('Y-m-d H:i:s', $info['upd_time']);
            }

            // 状态  1正常 2停用'
            if (isset($info['status']) && $info['status']) {
                $user_status = "";
                switch ($info['status']) {
                    case 1:
                        $user_status = "正常";
                        break;
                    case 2:
                        $user_status = "停用";
                        break;
                }
                $info['user_status'] = $user_status;
            }

            //获取系统操作人信息
            if ($flag) {
                //添加人
                if ($info['add_user']) {
                    $info['format_add_user'] = $this->getSystemAdminName($info['add_user']);
                }

                //更新人
                if ($info['upd_user']) {
                    $info['format_upd_user'] = $this->getSystemAdminName($info['upd_user']);
                }

                unset($info['token']);
                unset($info['password']);
            }
        }
        return $info;
    }

    public function edit($data, &$error = '', $is_sql = false)
    {
        $id = (int)$data['id'];
        if ($id) {   // 更新
            if (empty($data['upd_time'])) {
                $data['upd_time'] = time();
            }
            if (empty($data['upd_user'])) {
                $data['upd_user'] = (int)$_SESSION['adminId'];
            }
        } else {
            if (empty($data['add_time'])) {
                $data['add_time'] = time();
            }
            if (empty($data['add_user'])) {
                $data['add_user'] = (int)$_SESSION['adminId'];
            }
        }

        //格式化表数据
        $this->formatData($data, $id);

        //数据表验证
        if (!$this->create($data)) {
            $error = $this->getError();
            return 0;
        }

        //数据入库处理
        if ($id) {
            //修改数据
            $result = $this->where("id={$id}")->save($data);
            $rowId = $id;
            if ($is_sql)
                echo $this->_sql();
        } else {
            //新增数据
            $result = $this->add($data);
            $rowId = $result;
            if ($is_sql) echo $this->_sql();

        }
        if ($result !== false) {
            //重置缓存
            $data['id'] = $rowId;
            file_put_contents(TEMP_PATH . "1.txt", json_encode($data));
            $this->_cacheReset($rowId, $data, $id);
        }
        return $rowId;
    }
}