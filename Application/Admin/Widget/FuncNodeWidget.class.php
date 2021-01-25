<?php

/**
 * 功能节点-挂件
 */
namespace Admin\Widget;
use Think\Controller;
class FuncNodeWidget extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 添加节点【全局导航】
     * @param $funcName
     * @param array $param
     */
    function btnAdd($funcName, $param = [])
    {
        $this->assign('param', json_encode($param));
        $this->btnFunc("add", '&#xe654;', $funcName);
    }


    /**
     * 批量删除节点【全局导航】
     * @param $funcName
     */
    function btnDAll($funcName)
    {
        $this->btnFunc("batchDrop", '&#xe640;', $funcName, "layui-btn-danger");
    }

    /**
     * 常用按钮【全局导航】
     * @param $funcAct
     * @param $funcIcon
     * @param $funcName
     * @param string $funcColor
     * @param int $funcType
     * @param array $param
     */
    function btnFunc($funcAct, $funcIcon, $funcName, $funcColor = '', $funcType = 1, $param = [])
    {
        $this->assign('funcAct', $funcAct);
        $this->assign('funcIcon', $funcIcon);
        $this->assign('funcName', $funcName);
        $this->assign('funcColor', $funcColor);
        $this->assign('funcType', $funcType);
        if ($param) {
            $this->assign('param', json_encode($param));
        }
        $this->display("Widget:FuncNode:funcNode.func");
    }

    /**
     * 添加节点【行数据】
     */
    function btnAdd2()
    {
        $this->display("Widget:FuncNode:funcNode.add");
    }

    /**
     * 编辑节点【行数据】
     */
    function btnEdit($funcName)
    {
        $this->assign('funcName', $funcName);
        $this->display("Widget:FuncNode:funcNode.edit");
    }

    /**
     * 删除节点【行数据】
     */
    function btnDel($funcName)
    {
        $this->assign('funcName', $funcName);
        $this->display("Widget:FuncNode:funcNode.drop");
    }


    /**
     * 查看详情【行数据】
     */
    function btnDetail($funcName)
    {
        $this->assign('funcName', $funcName);
        $this->display("Widget:FuncNode:funcNode.detail");
    }

    /**
     * 设置权限
     */
    function btnSetAuth($funcName)
    {
        $this->assign('funcName', $funcName);
        $this->display("Widget:FuncNode:funcNode.auth");
    }


    /**
     * 添加节点【全局导航】
     * @param $funcName
     * @param array $param
     */
    function btnImport($funcName, $param = [])
    {
        $this->assign('param', json_encode($param));
        $this->btnFunc("import", '&#xe681;', $funcName);
    }
}