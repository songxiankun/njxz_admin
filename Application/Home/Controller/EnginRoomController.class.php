<?php


namespace Home\Controller;

use Home\Model\AdminModel;
use Home\Model\EnginRoomModel;

/**
 * test模式倒入数据
 * Class EnginRoomController
 * @package Home\Controller
 */
class EnginRoomController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 批量倒入数据到数据库
     * @return array|string|void
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function import()
    {
        // 导入PHPExcel目录
        import("Org.Util.PHPExcel");
        // 读取excel包
        import("Org.Util.PHPExcel.Reader.Excel5");
        import("Org.Util.PHPExcel.Reader.Excel2007");

        $filePath = FILE_PATH . '/enginroom/data.xlsx';
        // 判断当前的filePath是否有效，是否存在文件
        if (empty($filePath) or !file_exists($filePath))
            die('file not exists');

        // 建立excelReader对象
        $obgReader = new \PHPExcel_Reader_Excel2007();
        // 判断当前对象是否可以打开
        if (!$obgReader->canRead($filePath)) {
            // 使用新的对象去打开文件
            $obgReader = new \PHPExcel_Reader_Excel5();
            if (!$obgReader->canRead($filePath)) {
                // 文件不存在
                echo 'no excel';
                return;
            }
        }

        try {
            // 加载excel文件
            $PHPExcel = $obgReader->load($filePath);
            // 获取指定的工作表
            $currentSheet = $PHPExcel->getSheet(0);
            // 获得最大的列号
            $allColum = $currentSheet->getHighestColumn();
            // 获取总行数
            $allRow = $currentSheet->getHighestRow();
            // 存数据到数组
            $data = array();
            // 存储表头
            $title = array();

            // 创建对象
            $enginRoomMod = new EnginRoomModel();
            // 获取其他表中数据
            $adminMod = new AdminModel();

            $count = 0;
            /* 公共数据集 */
            /**
             * `building_id` int DEFAULT NULL COMMENT '楼id',
             * `floor` int DEFAULT NULL COMMENT '楼层数'
             * `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '机房名称',
             * `num` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '机房编号',
             * `admin_id` int DEFAULT NULL COMMENT '负责人员',
             * `note` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '备注',
             */
            $data['add_time'] = time();
            $data['add_user'] = 1;

            /* 存储上一个用户信息 */
            $lastUserName = '';
            $lastAdminID = '';

            for ($rowIndex = 2; $rowIndex <= $allRow; ++$rowIndex) {
                /* A: 负责人  B：具体存放消息 */
                $user = $currentSheet->getCell('A' . $rowIndex)->getValue(); /* 绍玲燕 */
                $detail = $currentSheet->getCell('B' . $rowIndex)->getValue(); /* 方山校区 工科楼南403 微机接口技术与单片机原理实验室 */

                $adminMap = [
                    'mark' => 1,   // 没被删除
                    'realname' => array('like', '%' . $user . '%'),    // 模糊查询
                    'status' => 1    // 状态不是被禁用
                ];

                /* 减少数据库操作 */
                if ($lastUserName == $user) {
                    $data['admin_id'] = $lastAdminID;
                } else {
                    // 查询当前用户id
                    $admin_id = $adminMod->field('id')->where($adminMap)->find();
                    if (isset($admin_id) && $admin_id) {
                        $data['admin_id'] = $admin_id['id'];
                        $lastUserName = $user;
                        $lastAdminID = $admin_id['id'];
                    } else {
                        continue;
                    }
                }

                /**
                 * 0 => string '方山校区' (length=12)
                 * 1 => string '工科楼南403' (length=15)
                 * 工科楼南411-2
                 * 2 => string '微机接口技术与单片机原理实验室'
                 *
                 * 1 工科楼
                 * 2 鹤琴
                 */
                $deArray = explode(" ", $detail);

                // 工科 or 鹤琴
                $data['building_id'] = sizeof($deArray) == 3 ? 1 : 2;
                if (sizeof($deArray) < 3) {
                    $data['floor'] = 4;
                    $data['name'] = sizeof($deArray) == 2 ? $deArray[1] : $deArray[0];
                    $data['num'] = '默认';
                } else {
                    // 处理工科信息
                    $data['name'] = $deArray[2];
                    // `floor` int DEFAULT NULL COMMENT '楼层数'
                    if (preg_match('/[0-9]/', $deArray[1], $matches, PREG_OFFSET_CAPTURE)) {
                        if (sizeof($matches[0]) == 2) {
                            $data['floor'] = $matches[0][0];
                            $data['num'] = strstr($deArray[1], $matches[0][0]);
                        }
                    }
                }
                $data['note'] = $detail;
                // 写入数据库
                $res = $enginRoomMod->add($data);
                if ($res) {
                    $count++;
                }
            }
            $this->ajaxReturn(message("导入入成功", true, [
                'all' => $allRow - 1,
                'import' => $count
            ]));
        } catch (\PHPExcel_Reader_Exception $e) {
            // TODO 错误信息返回
            return $e->getMessage();
        }
    }
}