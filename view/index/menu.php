<style>
<!--
body{
	font-size:14px;
}
#browser {
	margin:0px;
	padding:0px;
}
-->
</style>
	<link rel="stylesheet" href="<?php echo $static;?>/jquery-treeview/jquery.treeview.css" />
	<script src="<?php echo $static;?>/js/jquery.min.js"></script>
	<script src="<?php echo $static;?>/js/jquery.cookie.js"></script>
	<script src="<?php echo $static;?>/jquery-treeview/jquery.treeview.js" type="text/javascript"></script>

	<script type="text/javascript">
	$(document).ready(function(){
		$("#browser").treeview({
			control: "#treecontrol",
			persist: "cookie",
			cookieId: "treeview-black",
			toggle: function() {
				console.log("%s was toggled.", $(this).find(">span").text());
			}
		});
	})
	</script>
	<div id="treecontrol"><a href="">≡收缩</a></div>
	<ul id="browser" class="filetree treeview-famfamfam">
		<!--<li><span class="folder">分类</span>-->
			<ul>
				<!-- <li><span class="folder">Item 1.1</span>
					<ul>
						<li><span class="file">Item 1.1.1</span></li>
					</ul>
				</li>
				<li><span class="folder">Folder 2</span>
					<ul>
						<li><span class="folder">Subfolder 2.1</span>
							<ul id="folder21">
								<li><span class="file">File 2.1.1</span></li>
								<li><span class="file">File 2.1.2</span></li>
							</ul>
						</li>
						<li><span class="folder">Subfolder 2.2</span>
							<ul>
								<li><span class="file">File 2.2.1</span></li>
								<li><span class="file">File 2.2.2</span></li>
							</ul>
						</li>
					</ul>
				</li>
				<li class="closed"><span class="folder">Folder 3 (closed at start)</span>
					<ul>
						<li><span class="file">File 3.1</span></li>
					</ul>
				</li>
				<li><span class="file">File 4</span></li> -->
				<?php echo $menuList;?>
			</ul>
		<!--</li>-->
	</ul>