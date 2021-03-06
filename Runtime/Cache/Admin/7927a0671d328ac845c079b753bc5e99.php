<?php if (!defined('THINK_PATH')) exit();?><form class="layui-form info-form" action="" style="width:80%;">
    <input name="id" id="id" type="hidden" value="<?php echo ($info["id"]); ?>">

    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">楼区名称：</label>
            <div class="layui-input-inline">
                <input name="name" id="name" value="<?php echo ($info["name"]); ?>" lay-verify="required"
                       autocomplete="off" placeholder="请输入楼区名称" class="layui-input" type="text">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">楼区层数：</label>
            <div class="layui-input-inline">
                <input name="floors" id="floors" value="<?php echo ($info["floors"]); ?>" lay-verify="" placeholder="请输入楼区层数"
                       autocomplete="off"
                       class="layui-input" type="text">
            </div>
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-inline">
            <label for="note" class="layui-form-label">楼区备注：</label>
            <div class="layui-input-block">
                <textarea style="text-align: left" name="note" id="note" cols="75" rows="10"><?php echo ($info["note"]); ?></textarea>
            </div>
        </div>
    </div>


    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="submitForm">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>