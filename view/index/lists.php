<?php require $pach . 'public/top.php';?>
<style>
<!--
.table-bordered{font-size:12px;}
-->
</style>
<div class="wrap js-check-wrap">
	<?php if($category_id>0){ ?>
		<div class="cf well form-search" style="margin-top:10px;">
		    <div class="fl ">
		        <div class="btn-group">
		            <a href="<?php echo url('content/add',['category_id'=>$category_id])?>" class="btn btn-success">※ 添加期刊</a>
		        </div>
		        &nbsp;&nbsp;
		        <div class="btn-group">
		            <a href="<?php echo url('content/recycle',['category_id'=>$category_id])?>" class="btn btn-success">※ 期刊回收站</a>
		        </div>
		    </div>
		</div>
	<?php }else{?>
	<div class="cf well form-search" style="margin-top:10px;height: 8px;line-height: 1px;">
		<div class="fl ">
			<strong style='color:#45a1de;'>╰☆╮ 温馨说明：添加期刊，请点击左边栏目菜单。^_^</strong>
		</div>
	</div>
	<?php }?>
    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th width="30">ID</th>
            <th align="center">期刊标题</th>
            <th align="center">点击量</th>
            <th align="center">状态</th>
            <th align="center">发布人</th>
            <th align="center">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $key=>$value){ ?>
        <tr>
            <th align="left">{$value.id}</th>
            <th align="left">{$value.title}</th>
            <th align="left">{$value.magazine_stats.click_num}</th>
            <th align="left">{:$value.status > 0 ? "<em style='color:green;'>已审核</em>" : "<em style='color:red;'>未审核</em>"}</th>
            <th align="left">{$value.username}</th>
            <th align="left">
            <a href="<?php echo url('content/ListSub',['magazine_id'=>$value['id'],'category_id'=>$value['category_id']])?>">期刊内容</a>
            	&nbsp;&nbsp;|&nbsp;&nbsp;
            	<a href="<?php echo url('content/AddSub',['magazine_id'=>$value['id'],'category_id'=>$value['category_id']])?>">添加内容</a>
            	&nbsp;&nbsp;|&nbsp;&nbsp;
            	<?php if($value['status']){?>
            	<a  href="#" post-url="<?php echo url('content/check',['id'=>$value['id'],'category_id'=>$value['category_id'],'status'=>1]);?>" class="a-post">取消审核</a>
            	<?php }else{?>
            	<a  href="#" post-url="<?php echo url('content/check',['id'=>$value['id'],'category_id'=>$value['category_id']]);?>" class="a-post">审核通过</a>
            	<?php }?>
            	&nbsp;&nbsp;|&nbsp;&nbsp;
            	<a href="<?php echo url('content/Edit',['id'=>$value['id'],'category_id'=>$value['category_id']])?>">修改</a>
            	&nbsp;&nbsp;|&nbsp;&nbsp;
            	<a href="#"  post-msg="你确定要删除吗" post-url="<?php echo url('content/delete',['id'=>$value['id'],'category_id'=>$value['category_id'],'status'=>'D']);?>" class="a-post">删除</a>
            </th>
        </tr>
        <?php } ?>
        </tbody>
     </table>
     
     <div style="text-align: center;">
     	{$page}
     </div>
     
</div>
<?php require $pach . 'public/foot.php';?>