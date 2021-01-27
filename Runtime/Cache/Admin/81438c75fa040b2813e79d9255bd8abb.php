<?php if (!defined('THINK_PATH')) exit();?><style>
html {
    background-color: #F1F2F7;
}
</style>

<!-- 面包屑 -->
<!-- 面包屑 -->
<div class="layui-body-header">
    <span class="layui-body-header-title"></span>
    <span class="layui-breadcrumb pull-left" style="visibility: visible;">
      <a>首页</a><span lay-separator="">/</span>
      <?php if(is_array($crumb)): $i = 0; $__LIST__ = $crumb;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a><?php echo ($vo); ?></a><span lay-separator="">/</span><?php endforeach; endif; else: echo "" ;endif; ?>
      <a>查看</a>
    </span>
</div>

<!-- 主体部分开始 -->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
        
             <!-- 内容区 -->
			 

<form class="layui-form" action="">
	<input name="type" id="type" type="hidden" value="<?php echo ($type); ?>">
	<input name="type_id" id="type_id" type="hidden" value="<?php echo ($type_id); ?>">

	<!-- 功能操作区一 -->
	<div class="layui-form toolbar">
       <div class="layui-form-item">
            <div class="layui-inline">
                <div class="layui-input-inline" style="width: auto;">
					<button class="layui-btn layui-btn-normal" id="expand"><i class="larry-icon">&#xe8c0;</i> 全部展开</button>
			    	<button class="layui-btn layui-btn-warm" id="collapse"><i class="larry-icon">&#xe8c1;</i> 全部收起</button>
			    	<a href="javascript:history.back();" class="layui-btn layui-btn-small">
						<i class="layui-icon">&#xe65c;</i> 返回
					</a>
					<button class="layui-btn" lay-submit="" lay-filter="submitForm2" id="submitForm2"><i class="layui-icon">&#xe631;</i> 保存设置</button>
               </div>
           </div>
       </div>
	</div>

	<!-- 树形结构一 -->
   	<div id="treeList" lay-filter="treeList" ></div>
</form>


			
        </div>
    </div>
</div>
<!-- 主体部分结束 -->