<?php if (!defined('THINK_PATH')) exit();?><form class="layui-form info-form" action="">
	<input name="menu_id" id="menu_id" type="hidden" value="<?php echo ($menu_id); ?>">
	<div class="layui-form-item">
		<label class="layui-form-label">菜单名称：</label>
		<div class="layui-input-block">
			<input name="name" id="name" value="<?php echo ($info["name"]); ?>" lay-verify="required" autocomplete="off" placeholder="请输入菜单名称" class="layui-input" type="text">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">节点名称：</label>
		<div class="layui-input-block">
			<input name="func[index|查看]" lay-skin="primary" title="查看" checked="" type="checkbox">
			<input name="func[add|新增]" lay-skin="primary" title="新增" checked="" type="checkbox">
			<input name="func[edit|修改]" lay-skin="primary" title="修改" checked="" type="checkbox">
			<input name="func[detail|详情]" lay-skin="primary" title="详情" type="checkbox">
			<input name="func[drop|删除]" lay-skin="primary" title="删除" checked="" type="checkbox">
			<input name="func[batchDrop|批量删除]" lay-skin="primary" title="批量删除" checked="" type="checkbox">
			<input name="func[confirm|确认]" lay-skin="primary" title="确认" type="checkbox">
		</div>
	</div>
	<div class="layui-form-item" style="margin-top:60px;">
		<div class="layui-input-block">
			<button class="layui-btn" lay-submit="" lay-filter="submitForm" id="submitForm">立即提交</button>
			<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		</div>
	</div>
</form>