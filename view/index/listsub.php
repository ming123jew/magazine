<?php require $pach . 'public/top.php';?>
<style>
<!--
.table-bordered{font-size:12px;}
-->
</style>
<div class="wrap js-check-wrap">
		<div class="cf well form-search" style="margin-top:10px;">
		    <div class="fl ">
		    	<div class="btn-group">
		            <a style="background-color:blue;" href="javascript:history.back(-1)" class="btn btn-success">※ 返回</a>
		        </div>
		        <div class="btn-group">
		            <a href="<?php echo url('listsub',['magazine_id'=>$magazine_id]);?>" class="btn btn-success">全部</a>
		        </div>
		    	<?php foreach ($array_default_catalogue as $key=>$value){?>
		        <div class="btn-group">
		            <a href="<?php echo url('listsub',['magazine_id'=>$magazine_id,'category_id'=>$category_id,'catalogue_id'=>$value['cid']]);?>" class="btn btn-success">{$value.name}</a>
		        </div>
		        <?php }?>
		         <div class="btn-group">
		            <a style="background-color:blue;" onclick="alert('建设中');return false;" dhref="<?php echo url('content/RecycleSub',['magazine_id'=>$magazine_id,'category_id'=>$category_id])?>" class="btn btn-success">※ 内容回收站</a>
		        </div>
		        <div class="btn-group">
		            <a style="background-color:blue;" href="<?php echo url('content/AddSub',['magazine_id'=>$magazine_id,'category_id'=>$category_id])?>" class="btn btn-success">※ 添加内容</a>
		        </div>
		    </div>
		</div>
		
    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th width="30">ID</th>
            <th align="center">标题</th>
             <th align="center">目录</th>
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
            <th align="left">{$value.catalogue_id|getCatalogueName}</th>
            <th align="left">{$value.magazine_article_stats.click_num}</th>
            <th align="left">{:$value.status > 0 ? "<em style='color:green;'>已审核</em>" : "<em style='color:red;'>未审核</em>"}</th>
            <th align="left">{$value.username}</th>
            <th align="left">

            	<?php if($value['status']){?>
            	<a  href="#" post-url="<?php echo url('content/CheckSub',['id'=>$value['id'],'magazine_id'=>$value['magazine_id'],'category_id'=>$value['category_id'],'status'=>1]);?>" class="a-post">取消审核</a>
            	<?php }else{?>
            	<a  href="#" post-url="<?php echo url('content/CheckSub',['id'=>$value['id'],'magazine_id'=>$value['magazine_id'],'category_id'=>$value['category_id']]);?>" class="a-post">审核通过</a>
            	<?php }?>
            	&nbsp;&nbsp;|&nbsp;&nbsp;
            	<a href="<?php echo url('content/EditSub',['id'=>$value['id'],'catalogue_id'=>$catalogue_id])?>">修改</a>
            	&nbsp;&nbsp;|&nbsp;&nbsp;
            	<a href="#"  post-msg="你确定要删除吗" post-url="<?php echo url('content/DeleteSub',['id'=>$value['id'],'magazine_id'=>$value['magazine_id'],'category_id'=>$value['category_id'],'status'=>'D']);?>" class="a-post">删除</a>
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