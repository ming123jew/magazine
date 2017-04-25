<?php require $pach . 'public/top.php';?>
<style>
<!--
body{
	background-color:#f2f2f2;
}
.cat-menu {
    width: 190px;
    margin: 10px 8px 0 0;
	background-color: #fff;
	float:left;
}
.content{
	float:left;
	border: 1px solid #c2d1d8;
    zoom: 1;

}
.content-right{	margin-top:10px;}
-->
*{margin:0;padding:0;}
</style>

<div class="wrap js-check-wrap">
    
    <div class="text-center">
	
		
		<div class="col-1 lf cat-menu" id="display_center_id" height="100%">
			<div class="content">
	        	<iframe name="center_frame" id="center_frame" src="<?php echo url('content/menu')?>" frameborder="false" scrolling="auto" style="border: none; " width="100%" height="auto" allowtransparency="true"></iframe>
	        </div>
	    </div>
	    <div class="content content-right" style=" overflow:hidden">
             <iframe name="rightMain" id="rightMain" src="<?php echo url('content/lists')?>" frameborder="false" scrolling="auto" style="border: none;" width="100%" height="auto" allowtransparency="true"></iframe>

        </div>

	
    </div>

     
</div>
<script>

var getWindowSize = function(){
return ["Height","Width"].map(function(name){
  return window["inner"+name] ||
	document.compatMode === "CSS1Compat" && document.documentElement[ "client" + name ] || document.body[ "client" + name ]
});
}
window.onload = function (){
	if(!+"\v1" && !document.querySelector) { // for IE6 IE7
	  document.body.onresize = resize;
	} else { 
	  window.onresize = resize;
	}
	function resize() {
		wSize();
		return false;
	}
}
function wSize(){
	//这是一字符串
	var str=getWindowSize();
	var strs= new Array(); //定义一数组
	strs=str.toString().split(","); //字符分割
	var heights = strs[0]-150,Body = $('body');//$('#rightMain').height(heights);   
	//iframe.height = strs[0]-46;
	if(strs[1]<980){
		//$('.header').css('width',980+'px');
		//$('#content').css('width',980+'px');
		Body.attr('scroll','');
		Body.removeClass('objbody');
	}else{
		//$('.header').css('width','auto');
		//$('#content').css('width','auto');
		Body.attr('scroll','no');
		Body.addClass('objbody');
	}
	
	var openClose = $(window.parent.document).find("#content").height()-30;
	$('#center_frame').height(openClose+9);
	$('#rightMain').height(openClose+9);
	var width = $(window.parent.document).find("#content").width();
	$('#rightMain').width(width-250);
	//$("#openClose").height(openClose+30);	
	//$("#Scroll").height(openClose-20);
	windowW();
}
wSize();
function windowW(){
	if($('#Scroll').height()<$("#leftMain").height()){
		$(".scroll").show();
	}else{
		$(".scroll").hide();
	}
}


</script>
<?php require $pach . 'public/foot.php';?>