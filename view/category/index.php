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
         <?php foreach($list as $v){ ?>
        <tr>
        	<td>{:array_key_exists('_id', $v)?$v._id:$v.id}</td>
        	<td>{$v.name}</td>
        	<td>{$v.count}</td>
        	<td>{$v.status}</td>
        	<td><?php if($isRole){?><a href="<?php echo Url('category/role',array('id'=>$v['id']))?>">权限设置</a>&nbsp;&nbsp;|&nbsp;&nbsp;<?php } ?><a href="<?php echo Url('category/edit',array('id'=>$v['id']))?>">修改</a>&nbsp;&nbsp;|&nbsp;&nbsp;删除</td>
        </tr>
        <?php } ?>
        </tbody>
     </table>
     <div style="text-align: center;">
     	{$page}
     </div>
     
</div>
<?php require $pach . 'public/foot.php';?>