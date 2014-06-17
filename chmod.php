<?php
	function chmod_R($path, $filemode, $dirmode) {
		if (is_dir($path) ) {
			if (!@chmod($path, $dirmode)) {
				$dirmode_str=decoct($dirmode);
				//print "<p class='faildir'>Failed applying filemode '$dirmode_str' on directory '$path'</p>";
			}
			$dh = opendir($path);
			while (($file = readdir($dh)) !== false) {
				if($file != '.' && $file != '..') {
					$fullpath = $path.'/'.$file;
					chmod_R($fullpath, $filemode,$dirmode);
				}
			}
			closedir($dh);
			//print "<p class='success'> Applied new filemode '".decoct($dirmode)."' on dir '$path'</p>";
		} else {
			if (is_link($path)) {
				print "<p class='lihkskip'>link '$path' is skipped</p>";
				return;
			}
			if (!@chmod($path, $filemode)) {
				$filemode_str=decoct($filemode);
				print "<p class='failfile'>Failed applying filemode '$filemode_str' on file '$path'</p>";
				return;
			}
			//print "<p class='success'> Applied new filemode '".decoct($filemode)."' on file '$path'</p>";
		}
		
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Change mode utility output</title>
		<style type="text/css">
			body{
				background: #ccc;
				font: normal 16px sans-serif;
				padding: 0;
				margin: 0;
				border: none;
			}
			
			h1{
				background: #eee;
				color: #666;
				margin: 0;
				padding: 32px 0 0 24px;
				height: 64px;
			}
			
			form{
				background: #aaa;
			}
			
			input{
				padding: 4px;
				margin-left: 16px;
			}
			
			label{
				margin-left: 16px;
			}
			
			p{
				margin: 0;
				padding: 8px 8px 8px 16px;
			}
			
			.main{
				width: 960px;
				margin:0 auto;
			}
			
			.inputtext{
				width:48px;
			}
			
			.success{
				color: #090;
				background: #cfc;
				border: #090 solid 1px;
			}
			
			.faildir{
				color: #a00;
				background: #c99;
				border: #a33 solid 1px;
			}
			
			.failfile{
				color: #ee0;
				background: #aa9;
				border: #cc0 solid 1px;
			}
			
			.linkskip{
				color: #33f;
				background: #aaf;
				border: #66f solid 1px;
			}
		</style>
	</head>
	<body>
		<div class="main">
			<h1>Change mode utility</h1>
			<form action="<?php $SCRIPT_FILENAME ?>" method="POST">
				<label>directories:</label>
				<input class="inputtext" type="text" name="p_dirmode" maxlength="3" value="755"/>
				<label>files:</label>
				<input class="inputtext" type="text" name="p_filemode" maxlength="3" value="644"/>
				<input type="submit" value="change mode"/>
				<label><?php echo $SCRIPT_NAME ?></label>
			</form>
			<?php
				if(isset($_POST["p_dirmode"])){
					$dir_mode = octdec($_POST["p_dirmode"]);
					$file_mode = isset($_POST["p_filemode"])&&$_POST["p_filemode"]!=''? octdec($_POST["p_filemode"]):$dir_mode;
					//echo decoct($dir_mode)." | ".decoct($file_mode);
					foreach(scandir('.') as $dir){
						if(is_dir($dir)&&$dir!='.'&&$dir!='..'){
							chmod_R($dir,$file_mode,$dir_mode);
						}
					}
				}
			?>
		</div>
	</body>
</html>