/**
 * 常用方法封装【方法调用类】
 */
layui.extend({
	common: 'module/common',
	treeGird : '../layui/lay/modules/treeGird',
}).define(['form','layer','table','common','treeGird'],function(exports){
    "use strict";
    var form = layui.form,
		table = layui.table,
		layer = layui.layer,
		common = layui.common,
		$ = layui.$;
    
    /**
     * 声明全局变量
     */
    var _tableIns,_tableName,_callback,_title,_tW=0,_tH=0,_isDbclick;
    
    /**
     * 自定义模块接口对象
     */
	var func = {
		/**
		 * TABLE列表函数
		 */
		tableIns: function(cols,tableName,callback,url,tableSort=false,result,act='index',
						   excleName = "table") {
			_tableName = tableName;
			_callback = callback;
			
			//网络请求地址初始化
			if(!url || url=='') url = cUrl+"/"+act;
			
			//网络请求地址参数
			var param = $("#param").val();
			if(param) {
				param = JSON.parse(param);console.log(param);
				//if ($.isArray(param)) {console.log(param);
				    for (var i in param) {
				    	console.log("索引："+i+",参数："+param[i]);
				    	if(url.indexOf("?") >= 0 ) { 
						    //包含
				    		url += "&"+param[i];
						}else{
							//不包含
							url += "?"+i+"="+param[i];
						}
		            }
				//}
				console.log("网络请求地址："+url);
			}
			
			//TABLE组件初始化
			_tableIns = table.render({
				elem : "#"+_tableName
				,url : url
				,toolbar: true
//				,toolbar: '#toolbarDemo'
               ,title: excleName
//                ,totalRow: true
				,method: 'post'
				,cellMinWidth : 150
				,page : true
				,page:  {
					 // 限定条数   总数、计数  上一页     页     下一页    到第几页、跳
			         layout: ['refresh','prev', 'page', 'next', 'skip','count','limit'] //自定义分页布局 
		             ,curr: 1 
		             ,groups: 10 //显示 连续页码
		             ,first: '首页'
		             ,last: '尾页' 
		         }
//			  	//初始排序
//			    ,initSort: {
//			        field: 'id', //排序字段，对应 cols 设定的各字段名
//			        type: 'desc' //排序方式  asc: 升序、desc: 降序、null: 默认排序
//			    }
				,height : "full-160"
				,limit : 20
				,limits : [20,30,40,50,60,70,80,90,100,150,200,1000,10000]
				,even: true //开启隔行背景
				,cols : [cols]
				,loading: true
				,done: function(res, curr, count) {
					//console.log("TABLE加载成功");
					
					//【TABLE行双击事件】
					if(_isDbclick) {
						var tbody = $('.layui-table-body').find("table" ).find("tbody");
						var tr = tbody.children("tr");
						tr.on('dblclick',function(){
							var index = tbody.find(".layui-table-hover").data('index');
							var obj = res.data[index];
							console.log("TABLE行对象ID："+obj.id);
							common.edit(_title,obj.id,_tW,_tH);
						});
					}
					
					if(result) {
						result(res,curr,count);
					}
					
			    }
			}); 
			
			//监听行工具事件
			table.on("tool("+_tableName+")", function(obj){
				var data = obj.data
				,layEvent = obj.event;

				if(layEvent === 'del'){
					console.log("删除操作");
					common.drop(data.id,function(data,res){
						if(res) {
							console.log("删除成功");
							obj.del();
						}else{
							console.log("删除失败");
						}
					});
				}else if(layEvent === 'edit'){
					console.log("编辑操作");
					common.edit(_title,data.id,_tW,_tH);
				}else if(layEvent === 'detail') {
					console.log("详情操作");
					common.detail(_title,data.id,_tW,_tH);
				}
				
				//执行回调
				if(_callback) {
					_callback(layEvent,data);
				}
				
			});
			
			//监听头工具栏事件
			table.on("toolbar("+_tableName+")", function(obj){
				var checkStatus = table.checkStatus(obj.config.id);
				if(obj.event=="add"){   //添加
					layer.msg("您点击了添加按钮");
				}
			});

			//监听复选框
			table.on("checkbox("+_tableName+")", function(obj){
				  console.log(obj.checked); //当前是否选中状态
				  console.log(obj.data); //选中行的相关数据
				  console.log(obj.type); //如果触发的是全选，则为：all，如果触发的是单选，则为：one
			});
			
			//监听行单击事件
			table.on("row("+_tableName+")", function(obj){
				//标注选中样式
				obj.tr.addClass('layui-table-click').siblings().removeClass('layui-table-click');
				var data = obj.data;
				console.log("单击对象ID："+data.id);
			});
			
			//监听排序事件
			if(tableSort) {
				
				//注:_tableName是table原始容器的属性 lay-filter="对应的值"
				table.on("sort("+_tableName+")", function(obj){ 
					// console.log(obj.field); //当前排序的字段名
					// console.log(obj.type); //当前排序类型：desc(降序)、asc(升序)、null(空对象，默认排序)
					// console.log(this); //当前排序的 th对象
					
					//请求服务端进行动态排序
					table.reload(_tableName, {
					    initSort: obj
						,where: {
							field: obj.field //排序字段
							,order: obj.type //排序方式
					    }
					});
				});
			}
			return this;
			
		},
		/**
		 * 设置行双击事件
		 */
		setDbclick:function(param=true){
			_isDbclick = param;
			return this;
		},
		/**
		 * TreeGrid列表函数
		 */
		treeIns:function(layout,treeName,isExpand=false,callback,url){
			
			//网络请求地址初始化
			if(!url || url=='') url = cUrl+"/index";
			
			//异步获取数据源
			var getDataList = function(){
				
				var result;
				// 设置同步
			    $.ajaxSetup({
			        async : false
			    });

		    	$.post(url, {}, function(data){
					if (data.success) {
						result = data.data;
					}else{
						//TODO...
					}
					
				}, 'json');
		    	
		    	// 恢复异步
		        $.ajaxSetup({
		            async : true
		        });
		        
		        return result;
		    }
			
			//TreeGrid组件初始化
		    var treeIns = layui.treeGird({
		        elem: '#'+treeName, //传入元素选择器
		        spreadable: isExpand, //设置是否全展开，默认不展开
		        checkbox : true,
		        nodes: getDataList(),
		        layout: layout
		    });
		    form.render();
		    
		    //全部收缩
		    $('#collapse').on('click', function() {
		        layui.collapse(treeIns);
		        return false;
		    });

		    //全部展开
		    $('#expand').on('click', function() {
		    	layui.expand(treeIns);
		    	return false;
		    });

		    //行选中事件
		    form.on('checkbox(*)', function(data){
		        // console.log(data.elem); //得到checkbox原始DOM对象
		        // console.log(data.elem.checked); //是否被选中，true或者false
		        // console.log(data.value); //复选框value值，也可以通过data.elem.value得到
		        // console.log(data.othis); //得到美化后的DOM对象

		        var arr = layui.getSelected(treeIns);
		        // console.log(arr.length)
		    });
		    
		    //添加数据
			$(".btnAdd2").click(function(){
				var pid = $(this).attr("data-id");
				if(callback) {
					callback('btnAdd2',0,pid);
				}else{
					common.edit(_title,0,_tW,_tH,['pid='+pid]);
				}
			});
			
			//编辑数据
			$(".btnEdit").click(function(){
				var id = $(this).attr("data-id");
				if(callback) {
					callback('btnEdit',id,0);
				}else{
					common.edit(_title,id,_tW,_tH);
				}
			});
			
			//删除数据
			$(".btnDel").click(function(){
				var id = $(this).attr("data-id");
				if(callback) {
					callback('btnDel',id,0);
				}else{
					common.drop(id,function(data,isSuc){
						history.go(0);
						console.log("树节点已删除");
					});
				}
			});
			
			//设置权限
			$(".btnSetAuth").click(function(){
				var id = $(this).attr("data-id");
				if(callback) {
					callback('btnSetAuth',id,0);
				}
			});
		},
		/**
		 * 设置弹窗函数
		 */
		setWin: function(title,tW,tH) {
			_title = title;
			_tW = tW;
			_tH = tH;
			return this;
		},
		/**
		 * 模糊搜索函数
		 */
		searchForm: function(searchForm,tableList) {
			
			//搜索功能
		    form.on("submit("+searchForm+")", function (data) {
		        common.searchForm(table,data,tableList);
		        return false;
		    });
			
		},
		/**
		 * TABLE复选框选中函数
		 */
		getCheckData: function(tableName=''){
			
			//设置请求URL
			if(!tableName || tableName=='') tableName = _tableName;
			var checkStatus = table.checkStatus(tableName)
		      ,data = checkStatus.data;
		      return data;
		      
		},
		/**
		 * 初始化日期组件(支持多组件初始化)
		 */
		initDate:function(item,callback){
			common.initDate(item,function(value, date){
				if(callback) {
					callback(value, date);
				}
			});
		},
		/**
		 * 打开窗体函数
		 */
		showWin:function(title,url,tW=0,tH=0,param,type,btn,callback){
			common.showWin(title,url,tW,tH,param,type,btn,function(layero, index){
				if(callback) {
					callback(layero, index);
				}
			});
		},
		/**
		 * 网络POST请求
		 */
		ajaxPost:function(url,data,callback,msg){
			common.ajaxPost(url,data,callback,msg);
		}
	}
	
	/**
	 * 批量删除
	 */
    $(".btnBatchDrop").click(function(){
        var checkStatus = table.checkStatus(_tableName),
            data = checkStatus.data;
        
        common.batchDrop(data,"submitForm",function(){
        	//console.log("批量删除成功");
        	_tableIns.reload();
        });
        
    });

	// 导入数据  excel数据导入
	$(".btnImport").click(function (){
		func.showWin("上传数据", "/Devices/import",);
	});

	/**
	 * 表单验证函数
	 */
	common.verify();
	
    /**
     * 搜索功能(统一方法)
     */
    form.on("submit(searchForm)", function (data) {
        common.searchForm(table,data);
        return false;
    });
	
	/**
	 * 添加数据
	 */
	$(".btnAdd").click(function(){
		//自定义参数
		var param = $(this).attr("data-param");
		if(param) {
			param = JSON.parse(param);
		}
		// console.log("自定义参数："+param);
		common.edit(_title,0,_tW,_tH,param);
    });
	
	/**
	 * 提交表单(统一方法)
	 */
	form.on('submit(submitForm)', function(data){
		common.submitForm(data);
		return false;
	});
    
	/**
	 * 输入自定义模块(此模块接口是对象)
	 */
    exports('func',func);
});
