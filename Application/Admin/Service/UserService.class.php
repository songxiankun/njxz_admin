<?php


namespace Admin\Service;


use Admin\Model\UserModel;

class UserService extends \Admin\Model\ServiceModel
{
    public function __construct()
    {
        parent::__construct();
        $this->mod = new UserModel();
    }

    public function getList($map = array(), $sort = "id desc")
    {
        // 获取查询条件
        $params = I("post.keys", 0);
        if ($params) {
            $map['mobile'] = array('like', "%$params%");
        }

        // 标识位
        $map['mark'] = 1;

        //获取数据  所有 id
        $result = $this->mod
            ->where($map)->order($sort)
            ->page(PAGE, PERPAGE)->getField("id", true);

        $list = [];
        if (is_array($result)) {
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
        foreach ($map as $key => $val) {
            $Page->parameter[$key] = urlencode($val);
        }
        $show = $Page->show();
        //设置返回值
        $message = array(
            "msg" => '操作成功',
            "code" => 0,
            "data" => $list,
            "count" => $count,
        );
        return $message;
    }

    /**
     * @param array $data
     * @param string $error
     * @param false $is_sql
     * @return array
     */
    public function edit($data, &$error = '', $is_sql = false)
    {
        $data = I("post.", 0);
        $avatar = trim($data['avatar']);
        $password = trim($data['password']);
        $email = trim($data['email']);
        $mobile = trim($data['mobile']);
        //字段验证
        if (!$data['id'] && !$avatar) {
            return message('请上传头像', false);
        }

        if (!\Zeus::isValidEmail($email))
            return message('邮箱输入错误', false);

        if (!\Zeus::isValidMobile($mobile))
            return message('请输入正确手机号', false);

        //数据处理
        if (strpos($avatar, "temp")) {
            $data['avatar'] = \Zeus::saveImage($avatar, 'user');
        }
        //密码加密处理
        if ($password) {
            $password = \Zeus::getPassWord($data['password']);
            $data['password'] = $password;
        } else {
            unset($data['password']);
        }
        // 生成唯一token
        $data['token'] = \Zeus::getToken();
        $data['status'] = (isset($data['status']) && $data['status'] == "on") ? 1 : 2;

        return parent::edit($data);
    }

    /**
     * Notes: 数据处理
     * User: songxk
     * DateTime: 2020/8/26 6:51 上午
     * @param $info
     */
    public function operate($info)
    {
        if (isset($info['source']) && $info['source'])
        {
            $info['source'] = $info['source'] == 1 ? "后台添加" : "微信小程序注册";
        }

        if (isset($info['status'])&& $info['status'])
        {
            $info['status'] = $info['status'] == 1 ? "正常" : "禁用";
        }

        if (isset($info['last_login_time']) && $info['last_login_time'])
        {
            $info['last_login_time'] = date("Y-m-d H:i:s", $info['last_login_time']);
        }
        return $info;
    }
}