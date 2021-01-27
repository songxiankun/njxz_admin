<?php if (!defined('THINK_PATH')) exit();?>
<form class="layui-form info-form" action="">
	<input name="id" id="id" type="hidden" value="<?php echo ($info["id"]); ?>">
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">菜单名称：</label>
			<div class="layui-input-inline">
				<input name="name" id="name" value="<?php echo ($info["name"]); ?>" lay-verify="required" autocomplete="off" placeholder="请输入菜单名称" class="layui-input" type="text">
			</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label">菜单图标：</label>
			<div class="layui-input-inline">
				<input name="icon" id="icon" value="<?php if($info["icon"] != null): echo ($info["icon"]); else: ?>larry-xitong<?php endif; ?>" lay-verify="required" autocomplete="off" placeholder="请输入菜单图标" class="layui-input" type="text" disabled="">
			</div>
			<a href="javascript:" class="layui-btn layui-btn-small btnIcon" id="btnIcon">
				<i class="iconFont larry-icon <?php if($info["icon"] != null): echo ($info["icon"]); else: ?>larry-xitong<?php endif; ?>"></i> 图标
			</a>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">菜单类型：</label>
			<div class="layui-input-inline">
				<?php echo W('common/singleSelect',array('type|1|菜单类型|name|id',C('MENU_TYPE'),$info['type']));?>
			</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label">上级菜单：</label>
			<div class="layui-input-inline">
				<?php echo W('common/singleSelect',array('parent_id|0|上级菜单|name|id',$menuList,$info['parent_id']));?>
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">URL地址：</label>
			<div class="layui-input-inline">
				<input name="url" id="url" value="<?php echo ($info["url"]); ?>" autocomplete="off" placeholder="请输入URL地址" class="layui-input" type="text">
			</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label">参数：</label>
			<div class="layui-input-inline">
				<input name="param" id="param" value="<?php echo ($info["param"]); ?>" autocomplete="off" placeholder="请输入参数" class="layui-input" type="text">
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">权限标识：</label>
			<div class="layui-input-inline">
				<input name="auth" id="auth" value="<?php echo ($info["auth"]); ?>" autocomplete="off" placeholder="请输入权限标识" class="layui-input" type="text">
			</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label">序号：</label>
			<div class="layui-input-inline">
				<input name="sort_order" id="sort_order" value="<?php echo ($info["sort_order"]); ?>" lay-verify="required|number" autocomplete="off" placeholder="请输入序号" class="layui-input" type="text">
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">是否显示：</label>
			<div class="layui-input-inline">
				<?php echo W('common/switchCheck',array(is_show,'是|否',$info['is_show']));?>
			</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label">公共菜单：</label>
			<div class="layui-input-inline">
				<?php echo W('common/switchCheck',array(is_public,'是|否',$info['is_public']));?>
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-input-block">
			<button class="layui-btn" lay-submit="" lay-filter="submitForm" id="submitForm">立即提交</button>
			<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		</div>
	</div>
</form>