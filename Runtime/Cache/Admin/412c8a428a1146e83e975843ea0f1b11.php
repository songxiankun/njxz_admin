<?php if (!defined('THINK_PATH')) exit();?><form class="layui-form" action="">
	<input name="admin_id" id="admin_id" type="hidden" value="<?php echo ($admin_id); ?>">
	<div class="layui-form-item">
		<label class="layui-form-label">角色名称：</label>
		<div class="layui-input-block">
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><input name="role[<?php echo ($val["id"]); ?>]" lay-skin="primary" title="<?php echo ($val["name"]); ?>" <?php if($val["selected"] == 1): ?>checked=""<?php endif; ?> type="checkbox"><?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
	</div>
	<div class="layui-form-item" style="margin-top:100px;">
		<div class="layui-input-block">
			<button class="layui-btn" lay-submit="" lay-filter="submitForm" id="submitForm">立即提交</button>
			<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		</div>
	</div>
</form>