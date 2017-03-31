<?php require $pach . 'public/top.php';?>
<style type="text/css">
<style>
	.table_full{
		
	}
    .checkmod{
        margin-bottom:20px;
        border: 1px solid #ebebeb;padding-bottom: 5px;
    }
    .checkmod dt{
        padding-left:10px;
        height:30px;
        line-height:30px;
        font-weight:bold;
        border-bottom: 1px solid #ebebeb;
        background-color:#ECECEC;
    }
    .checkmod dt{
        margin-bottom: 5px;
        border-bottom-color:#ebebeb;
        background-color:#ECECEC;
    }
    .checkbox , .radio{
        display:inline-block;
        height:20px;
        line-height:20px;
    }
    .checkmod dd{
        padding-left:10px;
        line-height:30px;
    }
    .checkmod dd .checkbox{
        margin:0 10px 0 0;
    }
    .checkmod dd .divsion{
        margin-right:20px;
    }
    .checkmod dt{
        line-height:30px;
        font-weight:bold;
    }

    .rule_check{border: 1px solid #ebebeb;margin: auto;padding: 5px 10px;}
    .menu_parent{margin-bottom: 5px;}
</style>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li><a href="<?php echo Url('category/index')?>">分类列表</a></li>
        <li><a href="<?php echo Url('category/add')?>">分类添加</a></li>
        <li   class="active"><a href="#">权限设置</a></li>
    </ul>

    
    <div class="row">
    
	<form class="form-horizontal" action="<?php echo Url('category/role')?>" method="post">
	            
	

	    <div class="form-group ">
		    <label class="col-lg-2 control-label" for="signupform-username">分类名称：</label>
		    <div class="col-lg-3">
			     <input name="name" readonly class="form-control" value="{$list.name}"/>
		    </div>
		     <div style="clear:both;"></div>
		    <label class="col-lg-2 control-label" >权限设置 ：</label>
		</div>
		
		<div class="form-group" style="width: 78%;margin: 0px auto;">
		    
		   
		    <div class="table_full">
		    	<?php foreach ($roleList as $key=>$value){?>
				<dl class="checkmod">
                    <dt class="hd">
                        <label class="checkbox" data-original-title="" data-toggle="tooltip">
                        <input {:array_key_exists($value[$role_id],$value)?$value[$value[$role_id]]:''}   name="roleid[{$value[$role_id]}][]" value="{$value[$role_id]}" level="1" onclick="javascript:checknode(this);" type="checkbox">
                        {$value[$role_name]}
                        </label>
                    </dt>
                    <dd class="bd">
                       <div class="rule_check" style="width: 99%;">
	                       <label class="checkbox" data-original-title="" data-toggle="">
	                        <input {:array_key_exists('add',$value)?$value['add']:''}  name="roleid[{$value[$role_id]}][]" value="add" level="2" onclick="javascript:checknode(this);" type="checkbox">
	                                  上传
		                   </label>
		                   <label class="checkbox" data-original-title="" data-toggle="">
		                        <input {:array_key_exists('edit',$value)?$value['edit']:''} name="roleid[{$value[$role_id]}][]" value="edit" level="2" onclick="javascript:checknode(this);" type="checkbox">
		                        修改
		                   </label>
		                   <label class="checkbox" data-original-title="" data-toggle="">
		                        <input  {:array_key_exists('detele',$value)?$value['detele']:''} name="roleid[{$value[$role_id]}][]" value="detele" level="2" onclick="javascript:checknode(this);" type="checkbox">
		                        删除
		                   </label>
		                   <label class="checkbox" data-original-title="" data-toggle="">
		                        <input {:array_key_exists('read',$value)?$value['read']:''} name="roleid[{$value[$role_id]}][]" value="read" level="2" onclick="javascript:checknode(this);" type="checkbox">
		                        阅读
		                   </label>
                   		</div>
                        <span class="child_row"></span>
                    </dd>
            	</dl>
		         <?php } ?>
		         
		    </div>
		</div>
		
		<div class="form-actions">
			<input name="id" value="{$list.id}" type="hidden"/>
		    <button type="button" class="btn btn-primary ajax-post " autocomplete="off">
		        保存
		    </button>
		    <a class="btn btn-default active" onclick="history.go(-1)">返回</a>
		</div>        
	
	</form>
    

		    
		   
    </div>

     
</div>

<script type="text/javascript">
<!--
function checknode(obj) {

    var chk = $("input[type='checkbox']");
    var count = chk.length;
    var num = chk.index(obj);
    var level_top = level_bottom = chk.eq(num).attr('level');

    for (var i = num; i >= 0; i--) {
        var le = chk.eq(i).attr('level');
        if (eval(le) < eval(level_top)) {
            chk.eq(i).prop("checked",true);
            var level_top = level_top - 1;
        }
    }

    for (var j = num + 1; j < count; j++) {
        var le = chk.eq(j).attr('level');
        if (chk.eq(num).prop("checked")) {
            if (eval(le) > eval(level_bottom)){

                chk.eq(j).prop("checked",true);
            }
            else if (eval(le) == eval(level_bottom)){
                break;
            }
        } else {
            if (eval(le) > eval(level_bottom)){
                chk.eq(j).prop("checked",false);
            }else if(eval(le) == eval(level_bottom)){
                break;
            }
        }
    }
}

//-->
</script>

<?php require $pach . 'public/foot.php';?>