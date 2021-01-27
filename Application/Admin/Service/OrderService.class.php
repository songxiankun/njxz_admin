<?php


namespace Admin\Service;


use Admin\Model\AdminModel;
use Admin\Model\OrderModel;
use Admin\Model\ServiceModel;
use Admin\Model\UserModel;

class OrderService extends ServiceModel
{
    public function __construct()
    {
        parent::__construct();
        $this->mod = new OrderModel();
    }

    /**
     * Notes: 获取维修账单列表
     * User: songxk
     * DateTime: 2020/8/26 7:33 上午
     * @param array $map
     * @param string $sort
     * @return array
     */
    public function getList($map = array(), $sort = "id desc")
    {
        // 查询条件
        $param = I("post.username");
        if ($param) {
            $userMod = new AdminModel();
            $where['realname'] = array('like', "%$param%");
            $map['admin_id'] = $userMod->field('id')->where($where)->find()['id'];
        }

        $map['mark'] = 1;

        //获取数据
        $result = $this->mod
            ->where($map)->order($sort)
            ->page(PAGE, PERPAGE)->getField("id", true);

        $list = [];
        if (is_array($result)) {
            foreach ($result as $val) {
                $info = $this->mod->getInfo($val);
                $info = $this->operator($info);
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
     * Notes: 详情
     * User: songxk
     * DateTime: 2020/9/8 6:17 下午
     * @param array $info
     * @return mixed
     */
    public function detail($info = array())
    {
        return  $this->operator($info);
    }

    /**
     * Notes: 数据处理
     * User: songxk
     * DateTime: 2020/8/26 7:26 上午
     * @param $data
     * @return
     */
    public function operator($data)
    {
        if ($data['user_id'] && isset($data['user_id'])) {
            $userMod = new UserModel();
            $data['user_name'] = $userMod->field('realname')
                ->where(['id' => $data['user_id']])->find()['realname'];
        }
        else
        {
            $data['user_name']  = "暂无";
        }

        if (isset($data['image']) && $data['image']) {
            $info['avatar_url'] = IMG_URL . $data['image'];
        }

        // TODO 换成video 的 URL
        if (isset($data['video']) && $data['video']) {
            $info['video_url'] = IMG_URL . $data['video'];
        }

        if (isset($data['admin_id']) && $data['admin_id'])  // 'admin_id' => string '1' (length=1)
        {
            $adminMod = new AdminModel();
            $data['admin_name'] = $adminMod->field('realname')->where(['id' => $data['admin_id']])->find()['realname'];
        }

        if (isset($data['sign_id']) && $data['sign_id'])  // 'admin_id' => string '1' (length=1)
        {
            $adminMod = new AdminModel();
            $data['sign_name'] = $adminMod->field('realname')->where(['id' => $data['sign_id']])->find()['realname'];
        }

        // 维修状态
        if (isset($data['status']) && $data['status'])
        {
            if ($data['status'] == 1)
            {
                $data['format_status'] = "待维修";
            }
            else if ($data['status'] == 2)
            {
                $data['format_status'] = "维修中";
            }
            else if ($data['status'] == 3)
            {
                $data['format_status'] = "维修结束";
            }
            else
            {
                $data['format_status'] = "维修状态错误";
            }
        }

        //  维修签字图片
        if (isset($data['sign']) && $data['sign'])
        {
            $data['format_sign'] = IMG_URL . $data['sign'];
        }

        return $data;
    }
}