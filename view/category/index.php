<?php require $pach . 'public/top.php';?>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li  class="active"><a href="<?php echo Url('category/index')?>">分类列表</a></li>
        <li><a href="<?php echo Url('category/add')?>">分类添加</a></li>
    </ul>

    
    <div class="text-center">
	    <div class="cf well form-search" style="height: 68px;">
		    <div class="fl ">
		        总行数：
		    </div>
		</div>
    </div>
    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th width="30">ID</th>
            <th align="left">分类名称</th>
            <th align="left">数据</th>
            <th width="50" align="left">状态</th>
            <th width="160">操作</th>
        </tr>
        </thead>
        <tbody>
         {$info}
        </tbody>
     </table>
     <div style="text-align: center;">
     	
     </div>
     
</div>
<?php require $pach . 'public/foot.php';?>