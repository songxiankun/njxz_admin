<?php if (!defined('THINK_PATH')) exit();?><form class="layui-form info-form" action="" style="width:80%;">
	<input name="id" id="id" type="hidden" value="<?php echo ($info["id"]); ?>">
	<div class="layui-form-item">
		<label class="layui-form-label">头像：</label>
		<?php echo W('common/uploadImg',array(avatar,$info['avatar_url'],'90x90',头像,'','450x450','1/1'));?>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">真实姓名：</label>
			<div class="layui-input-inline">
				<input name="realname" id="realname" value="<?php echo ($info["realname"]); ?>" lay-verify="required" autocomplete="off" placeholder="请输入真实姓名" class="layui-input" type="text">
			</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label">员工工号：</label>
			<div class="layui-input-inline">
				<input name="num" id="num" value="<?php echo ($info["num"]); ?>" lay-verify="" placeholder="请输入员工工号" autocomplete="off" class="layui-input" type="text">
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">手机号码：</label>
			<div class="layui-input-inline">
				<input name="mobile" id="mobile" value="<?php echo ($info["mobile"]); ?>" lay-verify="required|phone" placeholder="请输入手机号码" autocomplete="off" class="layui-input" type="tel">
			</div>
		</div>
		
		<div class="layui-inline">
			<label class="layui-form-label">电子邮箱：</label>
			<div class="layui-input-inline">
				<input name="email" id="email" value="<?php echo ($info["email"]); ?>" lay-verify="required|email" placeholder="请输入邮箱" autocomplete="off" class="layui-input" type="text">
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">身份证号：</label>
			<div class="layui-input-inline">
				<input name="identity" id="identity" value="<?php echo ($info["identity"]); ?>" lay-verify="identity" placeholder="请输入身份证号" autocomplete="off" class="layui-input" type="text">
			</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label">性别：</label>
			<div class="layui-input-block">
				<?php echo W('common/singleSelect',array('gender|1|性别|name|id',C('GENDER_ARR'),$info['gender'] ? $info['gender'] : 1));?>
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">职位：</label>
			<div class="layui-input-inline">
				<?php echo W('position/select',array('position_id|1|职位|name|id',$info['position_id']));?>
			</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label">入职时间：</label>
			<div class="layui-input-inline">
				<input name="entry_date" id="entry_date" value="<?php echo ($info["format_entry_date"]); ?>" lay-verify="datetime" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input date-icon" type="text">
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">用户名：</label>
			<div class="layui-input-inline">
				<input name="username" id="username" value="<?php echo ($info["username"]); ?>" lay-verify="required" placeholder="请输入用户名" autocomplete="off" class="layui-input" type="text">
			</div>
		</div>
		
		<div class="layui-inline">
			<label class="layui-form-label">密码：</label>
			<div class="layui-input-inline">
				<input name="password" placeholder="请输入密码" autocomplete="off" class="layui-input" type="password">
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">所属组织：</label>
			<div class="layui-input-inline">
				<?php echo W('adminOrg/select',array('organization_id|1|组织机构|name|id',$info['organization_id']));?>
			</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label">所属部门：</label>
			<div class="layui-input-inline">
				<?php echo W('adminDep/select',array(dept_id,$info['dept_id']));?>
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">管理员：</label>
			<div class="layui-input-inline">
				<?php echo W('common/switchCheck',array(is_admin,'是|否',$info['is_admin']));?>
			</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label">状态：</label>
			<div class="layui-input-inline">
				<?php echo W('common/switchCheck',array(status,'在用|禁用',$info['status'] ? $info['status'] : 1));?>
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">所属城市：</label>
		<?php echo W('city/select',array($info['district_id'],3));?>
	</div>
	<div class="layui-form-item layui-form-text">
		<label class="layui-form-label">备注：</label>
		<div class="layui-input-block">
			<textarea name="note" id="note" placeholder="请输入备注" class="layui-textarea"><?php echo ($info["note"]); ?></textarea>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-input-block">
			<button class="layui-btn" lay-submit="" lay-filter="submitForm">立即提交</button>
			<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		</div>
	</div>
</form>