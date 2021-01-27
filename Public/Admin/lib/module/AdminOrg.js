/**
 *	组织机构管理
 */
layui.use(['func'],function(){
	
	//【声明变量】
	var func = layui.func
		,$ = layui.$;
	
	if(A=='index') {
		
		//【TABLE列数组】
		var cols = [
				{ type:'checkbox', fixed: 'left' }
				,{ field:'id', width:80, title: 'ID', align:'center', sort: true, fixed: 'left' }
				,{ field:'logo_url', width:80, title: 'LOGO', align:'center', templet:function(d){
					var logoStr = "";
		 			if(d.logo_url) {
		 				logoStr = '<a href="'+d.logo_url+'" target="_blank"><img src="'+d.logo_url+'" height="26" /></a>';
		 			}
		 			return logoStr;
		          }}
				,{ field:'name', width:300, title: '组织机构全称', align:'center' }
				,{ field:'nickname', width:120, title: '组织机构简称', align:'center' }
				,{ field:'contact', width:100, title: '联系人', align:'center' }
				,{ field:'tel', width:150, title: '联系电话', align:'center' }
				,{ field:'address', width:350, title: '详细地址', align:'center' }
				,{ field:'format_add_user', width:100, title: '创建人', align:'center' }
				,{ field:'format_add_time', width:180, title: '添加时间', align:'center', sort: true }
				,{ field:'format_upd_time', width:180, title: '更新时间', align:'center', sort: true }
				,{ field:'sort_order', width:100, title: '排序', align:'center' }
				,{ fixed:'right', width:280, title: '功能操作区', align:'center', toolbar: '#toolBar' }
			];
		
		//【渲染TABLE】
		func.tableIns(cols,"tableList",function(layEvent,data){
			
			if(layEvent === 'auth'){
				console.log("组织权限设置");
				location.href = mUrl + "/adminAuth/index?type=3&type_id="+data.id;
			}
			
		});
		
		//【设置弹框】
		func.setWin("组织机构");
		
	}

});