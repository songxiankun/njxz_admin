/**
 * 常用方法封装【基础类】
 */
layui.define(['form','layer','larry','laydate'],function(exports){
	"use strict";
	var form = layui.form,
		layer = layer = parent.layer === undefined ? layui.layer : top.layer,
		larry = layui.larry,
		laydate = layui.laydate,
		$ = layui.$;
	
	/**
	 * 自定义模块接口对象
	 */
	var active = {
		/**
		 * 添加、编辑公共函数
		 */
		edit: function(title,id,tW=0,tH=0,param){
			var titleStr = '';
	        if(id>0){
	        	titleStr = '修改';
	        }else{
	        	titleStr = '新增';
	        }
	        if(title ==='undefined' || title ==='') {
	        	titleStr += "信息";
	        }else{
	        	titleStr += title;
	        }

	        //URL逻辑处理
	        var url = cUrl+"/edit?id="+id;
	        if (Array.isArray(param)) {
			    for (var i in param) {
			    	console.log("索引："+i+",数据源："+param[i]);
			    	url += "&"+param[i];
	            }
			}
	        // console.log("URL请求地址："+url);
	        //
	        //调用内部方法
	        active.showWin(titleStr,url,tW,tH);
		},
		/**
		 * 数据详情函数
		 */
		detail: function(title,id,tW=0,tH=0){
			//调用内部方法
	        var url = cUrl+"/detail?id="+id;
	        active.showWin(title+"详情",url,tW,tH);
		},
		/**
		 * 删除单条数据函数
		 */
		drop: function(id,callback) {
			layer.confirm('您确定要删除吗？删除后将无法恢复！', {
                icon: 3,
                skin: 'layer-ext-moon',
                btn: ['确认', '取消'] //按钮
            },function(index){
            	
            	//调用内部方法
            	var url = cUrl+"/drop";
            	active.ajaxPost(url,{"id":id},function(data,flag){
            		if(callback) {
            			console.log("删除成功ID:"+data.id);
            			callback(data,flag);
                	}
            	},'正在删除。。。');
            	
			});
			
		},
		/**
		 * 批量删除函数
		 */
		batchDrop: function(data,form,callback) {
			
			var ids = [];
			if(data.length > 0) {
	            for (var i in data) {
	                ids.push(data[i].id);
	            }
	            var idsStr = ids.join(",");
	            layer.confirm('确定删除选中的数据吗？', { icon: 3, title: '提示信息' }, function (index) {
	            	
	            	$.ajax({
	                    url:cUrl + "/batchDrop",
	                    dataType:"json",
	                    type:"POST",
	                    data:{"id":idsStr,"changeAct":0},
	                    beforeSend:function () {
	                        layer.msg('正在提交。。。', {
	                            icon: 16
	                            ,shade: 0.01
	                            ,time: 0
	                        });
	                        $('#'+form).attr('disabled',"true");
	                    },
	                    success:function(res){
	                        $('#'+form).removeAttr("disabled");
	                        layer.closeAll();
	                        if(res.success){
	                        	//2秒后关闭
	                            layer.msg(res.msg,{ icon: 1,time: 1000}, function () {
	                            	if(callback) {
	                            		callback(true);
	                            	}
	                            });
	                        }else{
	                            layer.msg(res.msg,{ icon: 5 });
	                        }
	                    },
	                    error:function() {
	                    	layer.msg("AJAX请求异常");
	                    	if(callback) {
	                    		callback(false);
	                    	}
	                    }
	                });
	            	
	            })
	        }else{
	            layer.msg("请选择需要删除的数据");
	        }
			
		},
		/**
		 * 表单验证函数
		 */
		verify: function(){
			form.verify({
				//value：表单的值、item：表单的DOM对象
		        required: function(value, item) { 
					var title = $(item).data('title');
					if(!title) {
						//自动获取
						title = $(item).parents('.layui-inline').find('.layui-form-label').text();
						if(title.indexOf("：") >= 0 ) { 
							title = title.substring(0,title.Length-1);
						} 
					}
					//值为空时提示
					if(!value) {
						return $(item).attr('placeholder');
					}
				}
				,number: [/^[0-9]*$/, '请输入数字']
				,username: function(value, item){
					//特殊字符验证
					if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
						return title + '不能含有特殊字符';
				    }
					//下划线验证
				    if(/(^\_)|(\__)|(\_+$)/.test(value)){
				    	return title + '首尾不能出现下划线\'_\'';
				    }
				    //数字验证
				    if(/^\d+\d+\d$/.test(value)){
				    	return title + '不能全为数字';
				    }
				}
				//数组的两个值分别代表：[正则匹配、匹配不符时的提示文字]
				,pass: [/^[\S]{6,12}$/,'密码必须6到12位，且不能出现空格'] 
			})
		},
		/**
		 * 表单提交函数
		 */
		submitForm: function(data) {
			var index = layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.2});
			$.post(aUrl, data.field, function(data){
				if (data.success) {
					layer.close(index);
					layer.msg(data.msg,{ icon: 6 });
					layer.closeAll("iframe");
					
					//刷新父页面
		            parent.location.reload();
					
					return false ;
				}else{
					layer.close(index);
					layer.msg(data.msg);
				}
			}, 'json');
			
		},
		/**
		 * 搜索函数
		 */
		searchForm: function(table,data,tableList='') {
			
			if(tableList=='') {
				tableList = "tableList";
			}
			
			//执行重载
	        table.reload(tableList, {
	            page: {
	                curr: 1
	            },
	            where: data.field
	        });
	        
		},
		/**
		 * 初始化日期组件函数
		 */
		initDate:function(item,callback){
			
			if (Array.isArray(item)) {
			    for (var i in item) {
			    	console.log("索引："+i);
			    	console.log("组件元素："+item[i]);
			    	var subItem = item[i].split('|');
			    	console.log("组件ID："+subItem[0]);
			    	if(subItem[2]) {
			    		console.log("日期参数："+subItem[2]);
			    		var param = subItem[2].split(',');
			    	}
			    	
			    	//日期组件数据重组
			    	var options = {};
			    	options.elem = "#"+subItem[0];
			    	options.type = subItem[1];
			    	options.theme = 'molv';//主题颜色[molv,#393D49,grid]
			    	options.range = subItem[3]==="true" ? true : subItem[3];//开启左右面板
			    	options.calendar = true;//是否显示公历节日
			    	options.show = false;//默认显示
			    	options.position = 'absolute';//[fixed,absolute,static]
			    	options.trigger = 'click';//定义鼠标悬停时弹出控件[click,mouseover]
			    	options.btns = ['clear','now','confirm'];//工具按钮 默认值['clear', 'now', 'confirm']
			    	options.mark = {'0-06-25':"生日",'0-12-31':"跨年"};//自定义标注重要日子
			    	//控件在打开时触发，回调返回一个参数
			    	options.ready = function(date){
			    		console.log("组件面板打开："+date);
			    	}
			    	//日期时间被切换后的回调
			    	options.change = function(value, date, endDate){
			    		console.log(value); //得到日期生成的值，如：2017-08-18
			    	    console.log(date); //得到日期时间对象：{year: 2017, month: 8, date: 18, hours: 0, minutes: 0, seconds: 0}
			    	    console.log(endDate); //得结束的日期时间对象，开启范围选择（range: true）才会返回。对象成员同上。
			    	}
			    	//控件选择完毕后的回调
			    	options.done = function(value, date,endDate){
						if(callback) {
							callback(value, date);
						}
					}
			    	if(param) {
			    		//最小值
			    		var minV = param[0];
			    		if(minV) {
			    			var isNum = !isNaN(minV);
				    		if(isNum) {
				    			//数字
				    			options.min = parseInt(minV);
				    		}else{
				    			//非数字
				    			options.min = minV;
				    		}
			    		}
			    		//最大值
			    		var maxV = param[1];
			    		if(maxV) {
			    			var isNum2 = !isNaN(maxV);
				    		if(isNum2) {
				    			//数字
				    			options.max = parseInt(maxV);
				    		}else{
				    			//非数字
				    			options.max = maxV;
				    		}
			    		}
			    	}
			    	//日期选择组件
			    	laydate.render(options);
	            }
			}
		},
		/**
		 * 弹出窗体函数
		 */
		showWin:function(title,url,tW=0,tH=0,param,type=2,btn,callback){
	        var index = layui.layer.open({
	            title : title,
	            type : type,
	            area : [tW+"px",tH+"px"],
	            content : url,
//	            closeBtn: false,
	            shadeClose: true,//点击遮罩关闭  
	            shade: 0.4,
	            maxmin: true, //开启最大化最小化按钮
//	            skin: 'layui-layer-rim', //加上边框
//	            skin: 'layui-layer-molv', //加上边框
	            btn: btn,
		        btnAlign: 'c',
	            success : function(layero, index){
	            	
	            	//窗体传值【支持多值传递】
	            	if (Array.isArray(param)) {
	            		for (var i in param) {
	            			var item = param[i].split('=');
	            			console.log("传值："+item[0]+","+item[1]);
	            			var body = layui.layer.getChildFrame('body', index);
	                        body.find("#"+item[0]).val(item[1]);
	            		}
	            	}
	            	
	            	//回调函数
	                if(callback) {
	                	callback(layero,index);
	                }
	            	
	                //延迟5秒
	                setTimeout(function(){
	                    layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
	                        tips: 3
	                    });
	                },500);
	                
	            },
	            end: function () {
	                
	            }
	        });
	        
	        if(tW==0) {
	        	//全屏设置
	        	layui.layer.full(index);
	            $(window).on("resize",function(){
	            	layui.layer.full(index);
	            });
	        }

		},
		/**
		 * 网络请求函数(POST)
		 */
		ajaxPost:function(url,data,callback,msg='处理中,请稍后。。。'){
			var index = '';
			$.ajax({
                url:url,
                dataType:"json",
                type:"POST",
                data:data,
                beforeSend:function () {
                	index = layer.msg(msg, {
                        icon: 16
                        ,shade: 0.01
                        ,time: 0
                    });
                },
                success:function(res){
                    if(res.success){
                    	//2秒后关闭
                        layer.msg(res.msg,{ icon: 1,time: 500}, function () {
            				layer.close(index);
            				if(callback) {
                        		callback(data,true);
                        	}
                        });
                    }else{
                        layer.msg(res.msg,{ icon: 5 });
                        return false;
                    }
                },
                error:function() {
                	layer.msg("AJAX请求异常");
                	if(callback) {
                		callback(data,false);
                	}
                }
            });
		}
	}
	
	/**
	 * 输入自定义模块(此模块接口是对象)
	 */
    exports('common', active); 
});