<?php if (!defined('THINK_PATH')) exit();?><select name="<?php echo ($idStr); ?>" id="<?php echo ($idStr); ?>" lay-search="">
	<option value="">【请选择部门】</option>
	<?php if($adminDepList != null): if(is_array($adminDepList)): $i = 0; $__LIST__ = $adminDepList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if($val["id"] == $selectId): ?>selected<?php endif; ?>><?php echo ($val["name"]); ?></option>
<!--            <optgroup label="<?php echo ($val["name"]); ?>">-->
<!--                <?php if($val['children'] != null): ?>-->
<!--                <?php if(is_array($val['children'])): $i = 0; $__LIST__ = $val['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>-->
<!--                -->
<!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
<!--<?php endif; ?>-->
<!--            </optgroup>--><?php endforeach; endif; else: echo "" ;endif; endif; ?>
</select>