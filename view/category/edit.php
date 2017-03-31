<?php require $pach . 'public/top.php';?>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li><a href="<?php echo Url('category/index')?>">分类列表</a></li>
        <li   class="active"><a href="<?php echo Url('category/add')?>">分类添加</a></li>
    </ul>

    
    <div class="row">
    
	<form class="form-horizontal" action="<?php echo Url('category/edit')?>" method="post">
	            
	
	    <div class="form-group ">
		    <label class="col-lg-2 control-label" for="signupform-username">上级分类：</label>
		    <div class="col-lg-3">
			    <select name="pid" calss="form-control">
			    	<option value="0">顶级分类</option>
			    	<option>2</option>
			    </select>
		    </div>
		</div>
	    <div class="form-group ">
		    <label class="col-lg-2 control-label" for="signupform-username">分类名称：</label>
		    <div class="col-lg-3">
			     <input name="name" class="form-control" value="{$list.name}"/>
		    </div>
		</div>
		
		<div class="form-group">
		    <label class="col-lg-2 control-label">状态 </label>
		    <div class="col-lg-3">
		                <label class="radio-inline">
		            <input type="radio" checked="" name="status" value="1"> 开启
		        </label>
		        <label class="radio-inline">
		            <input type="radio" name="status" value="0"> 禁用
		        </label>
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
<?php require $pach . 'public/foot.php';?>