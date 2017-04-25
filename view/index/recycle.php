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
		</div>
	</div>
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
            	<a href="<?php echo url('content/Edit',['id'=>$value['id'],'category_id'=>$value['category_id']])?>">修改</a>
            	&nbsp;&nbsp;|&nbsp;&nbsp;
            	<a href="#" post-url="<?php echo url('content/Recovery',['id'=>$value['id'],'category_id'=>$value['category_id'],'status'=>'R']);?>" class="a-post">恢复</a>
            </th>
        </tr>
        <?php } ?>
        </tbody>
     </table>
     
</div>
<?php require $pach . 'public/foot.php';?>