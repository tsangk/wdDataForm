<html>
	<head>
		<!-- Copyright (c) Kenneth Tsang 2012 -->
		<!-- Automated access to this program is strictly forbidden -->
		<title>Wikidot Data Form (Forgiving to Strict)</title>
		<style>body{ font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; }td{ vertical-align: top; font-size: 12px; }#status{text-align: center;padding: 0 1em;margin: 0.5em 0;border: 1px solid #EAA;}</style>
	</head>
	<body>
		<h1>Wikidot Data Form Converter</h1>
		<p>This program converts existing forgiving YAML code into the current strict version.  Please note that this program is not perfect and may have bugs.  If you encounter any issues, please leave a comment <a href="http://jxeeno.tk/blog:wikidot:from-forgiving-to-strict">here</a> with the exact code that's causing the issue.</p>
		<table style="width: 100%;">
			<tbody><tr>
				<td style="width: 50%;">
					<b>Input:</b> Paste the entire _template page below (copy from <i>view source</i> or <i>Edit</i> textarea)
				</td>
				<td style="width: 50%;">
					<b>Output:</b> Your new compliant _template code:
				</td>
				</tr>
<?php
	$textarea = "";
	function checkStatus($array){
		if(isset($array["fields"])){
			if(count($array)!=1){
				return "<b>Notice! There is more than one parent-level value. Usually, it is reserved for <i>fields:</i> only.</b><br />Please check indenting and copy the <b>entire</b> _template from <i>view source</i> or the <i>Edit</i> textarea.";
			}else{
				if(count($array["fields"])<1){
					return "<b>Notice! There are no fields defined.</b><br />Please check indenting and copy the <b>entire</b> _template from <i>view source</i> or the <i>Edit</i> textarea.";
				}else{
					return "<b>Your data form has been successfully converted!</b>";
				}
			}
		}else{
			return "<b>ERROR! Invalid Data Form input!  YAML must contain <i>fields:</i></b>";
		}
	}
	if(!empty($_POST["yaml"])){
		require_once('spyc.php');
		require_once('sfYamlDumper.php');
		if(preg_match("/\[\[form\]\]([\s\S]+)\[\[\/form\]\]/",$_POST["yaml"],$matches)){
			$dumper = new sfYamlDumper();
			$dfarray = Spyc::YAMLLoad(trim($matches[1]));
			$status = checkStatus($dfarray);
			$textarea = preg_replace("/\[\[form\]\]([\s\S]+)\[\[\/form\]\]/","[[form]]\n".$dumper->dump($dfarray,10)."[[/form]]",$_POST["yaml"]);
		}else{
			/*$dumper = new sfYamlDumper();
			$dfarray = Spyc::YAMLLoad(trim($_POST["yaml"]));
			$status = checkStatus($dfarray);
			$textarea = $dumper->dump($dfarray,10);*/
			$status = "<b>ERROR! Please enter _template page with [[form]] blocks!</b>";
			$textarea = "Enter your _template page with the non-complaint data form YAML and click \"Fix\"!";
		}
	}else{
		$textarea = "Enter your _template page with the non-complaint data form YAML and click \"Fix\"!";
		$status = "<b>Converter Ready!</b>";
	}
?>
			<tr>
				<td colspan=2>
					<div id="status"><?php echo $status; ?></div>
				</td>
			</tr>
			<tr>
				<td>
					<form method="post">
						<textarea name="yaml" style="height: 300px; width: 100%;"><?php if(!empty($_POST["yaml"])){ echo htmlentities($_POST["yaml"]); } ?></textarea><br />
						<p style="text-align: right;"><input type="submit" value="fix!" /></p>
					</form>
				</td>
				
				<td>
					<textarea style="height: 300px; width: 100%;"><?php echo $textarea; ?></textarea>
				</td>
			</tr></tbody>
		</table>
		<hr />
		<center>
			<script type="text/javascript"><!--
			google_ad_client = "ca-pub-2102065243904072";
			/* Ad Bottom */
			google_ad_slot = "2376964219";
			google_ad_width = 728;
			google_ad_height = 90;
			//-->
			</script>
			<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>
		</center>
		<hr />
		&copy; Kenneth Tsang 2012.  All rights reserved.  Powered by SPYC and syYaml.
	</body>
</html>