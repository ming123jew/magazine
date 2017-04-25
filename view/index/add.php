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
		<div class="cf well form-search" style="margin-top:10px; height:35px;line-height:4px;">
			<strong style="font-weight: bolder;">添加期刊</strong>
		 </div>
		 <div class="row" style="margin-right:0px;">
		 <form class="form-horizontal" action="" method="post">
		  <input type="hidden" name="__token__" id="__token__" value="{$token}" />
		  <input type="hidden" name="info[category_id]" value=" {$array_category.id}" />
			<div class="form-group ">
			    <label class="col-lg-2 control-label" for="signupform-username">分类：</label>
			    <div class="col-lg-3">
				     {$array_category.name}
			    </div>
			</div>
			<div class="form-group ">
			    <label class="col-lg-2 control-label" for="signupform-username">目录：</label>
			    <div class="col-lg-3">
			    <?php foreach ($array_default_catalogue as $key=>$value){?>
			     <input type="checkbox" name="info[catalogue][]" value="<?php echo $value['cid'].'|'.$value['name'];?>" checked> <?php echo $value['name'];?>
			      &nbsp;&nbsp;&nbsp;&nbsp;
			    <?php }?>
			    &nbsp;&nbsp;&nbsp;&nbsp;
			    <a style="display:none;">+新建目录</a>
			    </div>
			</div>
			<div class="form-group ">
			    <label class="col-lg-2 control-label" for="signupform-username">标题：</label>
			    <div class="col-lg-3">
				     <input class="form-control text" type="text" name="info[title]" value="" placeholder="标题"> 
			    </div>
			</div>
			<div class="form-group ">
			    <label class="col-lg-2 control-label" for="signupform-username">关键词：</label>
			    <div class="col-lg-3">
				     <input class="form-control text" type="text" name="info[tags]" value="" placeholder="关键词，注意用空格隔开."> 
			    </div>
			</div>
			<div class="form-group ">
			    <label class="col-lg-2 control-label" for="signupform-username">摘要：</label>
			    <div class="col-lg-3">
			   		 <textarea placeholder="摘要，请少于120字." class="form-control text" rows="" cols="" name="info[desc]"></textarea>
			    </div>
			</div>
			<div class="form-group ">
			    <label class="col-lg-2 control-label" for="signupform-username">缩略图：(请上传320*514)</label>
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
				
				            <div class="img-preview"></div>
				        </div>
				
				
				    </div>

				     <input class="form-control text" type="hidden" id="thumb" name="info[thumb]" value="" placeholder="">
			    </div>
			</div>
			<div class="form-group ">
			    <label class="col-lg-2 control-label" for="signupform-username">跳转链接：</label>
			    <div class="col-lg-3">
			    	<input name="info[islink]" type="checkbox" id="islink" onclick="ruselinkurl();">
			    	 <input class="form-control text" type="text" name="info[gourl]" id="gourl" value="" disabled="disabled" placeholder="跳转链接">  
			    </div>
			</div>
			
			<div class="form-group" style="display:none;">
			    <label class="col-lg-2 control-label" for="signupform-username">内容：</label>
			    <div class="col-lg-3">
			    	<script id="editor" type="text/plain" style="width:1024px;height:500px;"></script>
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
var url_server = "<?php echo url('content/uploadthumb',['token'=>$token])?>";
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
</script>

<script type="text/javascript" src="<?php echo $static;?>/webuploader-0.1.5/webuploader.js"></script>
<script type="text/javascript" src="<?php echo $static;?>/webuploader-0.1.5/cropper/cropper.js"></script>
<script type="text/javascript" src="<?php echo $static;?>/webuploader-0.1.5/cropper/uploader.js"></script>
<?php require $pach . 'public/foot.php';?>