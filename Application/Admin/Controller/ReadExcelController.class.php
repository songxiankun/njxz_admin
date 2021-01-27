<?php


namespace Admin\Controller;


use Admin\Model\AdminModel;
use Admin\Model\DevicesModel;
use Think\Controller;

/**
 * 读取Excel信息
 * Class ReadExcelController
 * @package Admin\Controller
 */
class ReadExcelController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @brief  从excel读取数据并存入到数据库
     * @param $filePath
     * @param int $defaultSheet
     * @param int $readLine
     * @return array
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function readExcel($filePath, $defaultSheet = 0, $readLine = 1000)
    {
        // 导入PHPExcel目录
        import("Org.Util.PHPExcel");
        // 读取excel包
        import("Org.Util.PHPExcel.Reader.Excel5");
        import("Org.Util.PHPExcel.Reader.Excel2007");

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
            $currentSheet = $PHPExcel->getSheet($defaultSheet);
            // 获得最大的列号
            $allColum = $currentSheet->getHighestColumn();
            // 获取总行数
            $allRow = $currentSheet->getHighestRow();
            // 存数据到数组
            $data = array();
            // 存储表头
            $title = array();

            // 创建对象
            $deviceModel = new DevicesModel();

            $count = 0;
            $flag = false;
            for ($rowIndex = 1; $rowIndex <= $allRow; ++$rowIndex)
            {
                // 循环读取每个单元格的内容。注意行从1开始，列从A开始
                for ($colIndex = 'A'; $colIndex <= $allColum; ++$colIndex)
                {
                    $addr = $colIndex . $rowIndex;
                    $cell = $currentSheet->getCell($addr)->getValue();

                    // 筛选出需要的列数据
                    if (1 == $rowIndex) {
                        $title[$colIndex] = $cell;
                        // 判断当前值是否在数组中
                        if (!in_array(trim($title[$colIndex]), C('DEVICES_COLUMNS')))
                            unset($title[$colIndex]);
                        continue;
                    }
                    // 数据重组
                    // 键值对反转 'A' => aa =====> aa => 'A'
                    $titleFlip = array_flip($title);
                    //  设备表配置数组反转
                    $map = array_flip(C('DEVICES_COLUMNS'));
                    // 保留需要的数据
                    if (in_array($colIndex, $titleFlip))
                    {
                        // 数据处理  achieve_time
                        if (trim($title[$colIndex]) == "取得日期")
                            $data[$map[$title[$colIndex]]] = strtotime($cell);
                        // 价格
                        else if (trim($title[$colIndex]) == "原值")
                            $data[$map[$title[$colIndex]]] = \Zeus::formatToCent($cell);
                        else
                            $data[$map[$title[$colIndex]]] = $cell;
                    }
                }

                if ($rowIndex != 1) {
                    $flag = true;
                }

                if ($flag) {
                    $data['add_time'] = time();
                    $data['add_user'] = session('adminId');
                    $res = $deviceModel->add($data);
                    if ($res) {
                        $count++;
                        unset($data);
                    }
                }
            }
            return message("导入入成功", true, [
                'all' => $allRow - 1,
                'import' => $count
            ]);
        } catch (\PHPExcel_Reader_Exception $e) {
            // TODO 错误信息返回
            return $e->getMessage();
        }
    }
}