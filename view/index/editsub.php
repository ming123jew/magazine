<?php require $pach . 'public/top.php';?>
<link rel="stylesheet" href="<?php echo $static;?>/webuploader-0.1.5/webuploader.css" />
<link rel="stylesheet" href="<?php echo $static;?>/webuploader-0.1.5/cropper/cropper.css" />
<link rel="stylesheet" href="<?php echo $static;?>/webuploader-0.1.5/cropper/style.css" />
<script type="text/javascript">
<!--
var ueditor_server_url = '<?php echo url("ueditor/index",['token'=>$token]);?>';
//-->
</script>
<script type="text/javascript" charset="utf-8" src="<?php echo $static;?>ueditor1_4_3_3/utf8/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $static;?>ueditor1_4_3_3/utf8/ueditor.all.min.js"> </script>
<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
<script type="text/javascript" charset="utf-8" src="<?php echo $static;?>ueditor1_4_3_3/utf8/lang/zh-cn/zh-cn.js"></script>

<style>
<!--
.text{
	width:520px;
}
.form-group{
	border-bottom: 1px dotted #ccc;
}
-->
</style>
<div class="wrap js-check-wrap">

		 <div class="cf well form-search" style="margin-top:10px;">
			<div class="btn-group">
		    	<a style="background-color:blue;" href="javascript:history.back(-1)" class="btn btn-success">返回</a>
			</div>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<strong style="font-weight: bolder;">期刊添加内容</strong>
		 </div>
		 <div class="row" style="margin-right:0px;">
		 <form class="form-horizontal" action="" method="post">
		  <input type="hidden" name="__token__" id="__token__" value="{$token}" />
		  <input type="hidden" name="info[category_id]" value="{$array_category.id}" />
		  <input type="hidden" name="info[magazine_id]" value="{$array_magazine_article.magazine_id}" />
		  <input type="hidden" name="info[id]" value="{$array_magazine_article.id}" />
		  <input class="form-control text" type="hidden" id="thumb" name="info[thumb]" value="{$array_magazine_article.thumb}" placeholder="">
			<div class="form-group ">
			    <label class="col-lg-2 control-label" for="signupform-username">分类：</label>
			    <div class="col-lg-3">
				     {$array_category.name}
			    </div>
			</div>
			<div class="form-group ">
			    <label class="col-lg-2 control-label" for="signupform-username">目录：</label>
			    <div class="col-lg-3">
			    	<select class="form-control text" name="info[catalogue_id]">
			    	<option value="0">--选择--</option>
			    	{$string_options_catalogue}
			    	</select>
			    </div>
			</div>
			<div class="form-group ">
			    <label class="col-lg-2 control-label" for="signupform-username">标题：</label>
			    <div class="col-lg-3">
				     <input class="form-control text" type="text" name="info[title]" value="{$array_magazine_article.title}" placeholder="标题"> 
			    </div>
			</div>
			<div class="form-group ">
			    <label class="col-lg-2 control-label" for="signupform-username">关键词：</label>
			    <div class="col-lg-3">
				     <input class="form-control text" type="text" name="info[tags]" value="{$array_magazine_article.tags}" placeholder="关键词，注意用空格隔开."> 
			    </div>
			</div>
			<div class="form-group ">
			    <label class="col-lg-2 control-label" for="signupform-username">摘要：</label>
			    <div class="col-lg-3">
			   		 <textarea placeholder="摘要，请少于120字." class="form-control text" rows="" cols="" name="info[desc]">{$array_magazine_article.desc}</textarea>
			    </div>
			</div>
			<div class="form-group ">
			    <label class="col-lg-2 control-label" for="signupform-username">日期：（格式: 03/12）</label>
			    <div class="col-lg-3">
			   		<input class="form-control text" type="text" name="info[date]" value="{$array_magazine_article.date}" placeholder=""> 
			    </div>
			</div>
			<div class="form-group ">
			    <label class="col-lg-2 control-label" for="signupform-username">缩略图：(请上传1:1比例的图片)</label>
			    <div class="col-lg-3">
			    
					<div id="wrapper">
				        <div class="uploader-container">
				            <div id="filePicker">选择文件</div>
				        </div>
				
				        <!-- Croper container -->
				        <div class="cropper-wraper webuploader-element-invisible">
				            <div class="img-container">
				                <img src="" alt="" />
				            </div>
				
				            <div class="upload-btn">上传所选区域</div>
				
				            <div class="img-preview" style="overflow: hidden;"><img src="{$array_magazine_article.thumb}" style="max-height: 200px;"/></div>
				        </div>
				    </div>

			    </div>
			</div>
			<div class="form-group ">
			    <label class="col-lg-2 control-label" for="signupform-username">跳转链接：</label>
			    <div class="col-lg-3">
			    	<input name="info[islink]" type="checkbox" id="islink"  <?php if($array_magazine_article['gourl']){?>checked<?php }?> onclick="ruselinkurl();">
			    	 <input class="form-control text" type="text" name="info[gourl]" id="gourl" value="{$array_magazine_article.gourl}" <?php if(!$array_magazine_article['gourl']){?>disabled="disabled"<?php }?> placeholder="跳转链接">  
			    </div>
			</div>
			
			<div class="form-group">
			    <label class="col-lg-2 control-label" for="signupform-username">内容：</label>
			    <div class="col-lg-3">
			    	<script id="editor" type="text/plain" style="width:1024px;height:500px;">{$array_magazine_article.body|htmlspecialchars_decode}</script>
			    	<button type="button" onclick="getLocalData()" >获取草稿箱内容</button>
        			<button type="button" onclick="clearLocalData()" >清空草稿箱</button>
			    </div>
			</div>
			<div class="form-actions">
			    <button type="button" class="btn btn-primary ajax-post " autocomplete="off">
			        保存
			    </button>
			    <a class="btn btn-default active" onclick="history.go(-1)">返回</a>
			</div>    
		</form>
		</div>
</div>
<script type="text/javascript">
var url_server = "<?php echo url('content/uploadthumb',['token'=>$token,'oldfile'=>urlencode($array_magazine_article['thumb']),'magazine_id'=>$array_magazine_article['magazine_id']])?>";
//判断跳转
function ruselinkurl() {
	var test = document.getElementById("islink").checked; 
	
    if(test) {
    	
            $('#gourl').attr('disabled',false); 
            //var oEditor = CKEDITOR.instances.content;
           // oEditor.insertHtml('　');
            //return false;
    } else {
            $('#gourl').attr('disabled','true');
    }
}
//实例化编辑器
//建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
var ue = UE.getEditor('editor');
function getLocalData () {
    //alert(UE.getEditor('editor').execCommand( "getlocaldata" ));
	 UE.getEditor('editor').setContent(UE.getEditor('editor').execCommand( "getlocaldata" ));
}

function clearLocalData () {
    UE.getEditor('editor').execCommand( "clearlocaldata" );
    alert("已清空草稿箱")
}

function catchRemoteImage(){
	UE.fireEvent("catchRemoteImage"); //editor换成你自己在实例化编辑器时候定义的名字
	
}
$('.cropper-container').hide();
$('.upload-btn').hide();
$('#img-container').hide();
$('.cropper-wraper').removeClass('webuploader-element-invisible');

</script>

<script type="text/javascript" src="<?php echo $static;?>/webuploader-0.1.5/webuploader.js"></script>
<script type="text/javascript" src="<?php echo $static;?>/webuploader-0.1.5/cropper/cropper.js"></script>
<script type="text/javascript" src="<?php echo $static;?>/webuploader-0.1.5/cropper/uploader.js"></script>
<?php require $pach . 'public/foot.php';?>