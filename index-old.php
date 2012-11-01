<?php
	$templateCode = html_entity_decode($_POST["yaml"]);

	$textarea = "";
	function checkStatus($array){
		$acceptedValues = array("label","type","values","default","category","join","before","after","width","height","match","match-error","hint","value","default-schema","required");
		if(isset($array["fields"])){
			if(count($array)!=1){
				return "<b><span class=\"er-red\">Notice! There is more than one parent-level value. Usually, it is reserved for <i>fields:</i> only.</span></b><br />Please check indenting and copy the <b>entire</b> _template from the page editor.";
			}else{
				if(!is_array($array['fields'])){
					return "<b><span class=\"er-red\">Notice! There are no fields defined.</span></b><br />Please check indenting and copy the <b>entire</b> _template from the page editor.";
				}else{
					$isokay = true;
					$noticeString = "";
					foreach($array["fields"] as $key=>$value){
						foreach($value as $k => $v){
							if(!in_array($k,$acceptedValues)){
								$isokay = false;
								$noticeString .= "<br />Please check property <i>$k</i> under field <i>$key</i>";
							}
						}
					}
					if(!$isokay){
						return "<b><span class=\"er-red\">Notice! There are invalid data form properties defined.</span></b>$noticeString";
					}else{
						return "<b>Your data form has been successfully converted!</b>";
					}
				}
			}
		}else{
			return "<b><span class=\"er-red\">ERROR! Invalid Data Form input!  YAML must contain <i>fields:</i></span></b><br />Please check if you have spelt <i>fields:</i> correctly!";
		}
	}
	if(!empty($templateCode)){
		require_once('spyc.php');
		require_once('sfYamlDumper.php');
		if(preg_match("/\[\[form\]\]([\s\S]+)\[\[\/form\]\]/",$templateCode,$matches)){
			$dumper = new sfYamlDumper();

			// Bug fixes (reported by Ed Johnson)
			// Colon in line
			$matches[1] = preg_replace("/([\n\r] +)([^:]+):( ?)([^\"'\n\r][^\n\r]+:[^\n\r]+[^\"'\n\r])($|\n|\r)/","$1$2:$3'$4'$5",$matches[1]);

			// Bracket at end of line
			$matches[1] = preg_replace("/([\n\r] +)([^:]+):( ?)(\[+)($|\n|\r)/","$1$2:$3'$4'$5",$matches[1]);
			$dfarray = Spyc::YAMLLoad(trim($matches[1]));
			$status = checkStatus($dfarray);
			$textarea = preg_replace("/\[\[form\]\]([\s\S]+)\[\[\/form\]\]/","�DFREPLACE�",$templateCode);
			$textarea = str_replace("�DFREPLACE�","[[form]]\n".$dumper->dump($dfarray,999)."[[/form]]",$textarea);
		}else{
			$status = "<b><span class=\"er-red\">ERROR! Please enter _template page with [[form]] blocks!</span></b>";
			$textarea = "Enter your _template page with the non-complaint data form YAML and click the convert button!";
		}
	}else{
		$textarea = "Enter your _template page with the non-complaint data form YAML and click the convert button!";
		$status = "<b>Converter Ready!</b>";
	}
?>
<html>
	<head>
		<!-- Copyright (c) Kenneth Tsang 2012 -->
		<!-- Automated access to this program is strictly forbidden -->
		<title>Wikidot Data Form (Forgiving to Strict)</title>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<style>
			html{
				font-family: 'Open Sans', sans-serif;
				font-size: 14px;
			}
			td{ vertical-align: top; font-size: 12px; }
			#status{text-align: center;padding: 0 1em;margin: 0.5em 0;border: 1px solid #EAA;background: white;}
			#header{
				background: #3b3b3b url(img/texture.png) repeat;
				height: 30px;
				width: 100%;
				position: fixed;
				top: 0px;
				left: 0px;
				color: white;
				vertical-align: middle;
				line-height: 32px;
				padding-left: 10px;
				padding-top: 3px;
				padding-bottom: 3px;
				white-space: nowrap;
				z-index: 9;
				border-bottom: 1px solid #3b3b3b;
				-webkit-box-shadow: 0 0 2px rgba(0, 0, 0, .52);
				text-align: center;
			}
			#header #logo{
				font-weight: bold;
				color: white;
				text-decoration: none;
				font-size: 120%;
			}
			#content{
				margin-top: 40px;
			}

			/* Wrapping */
			.grayshade{
				background-color: #DDD;
				border-radius: 10px;
			}
			.er-red{ color: red;}
		</style>
		<script type="text/javascript">
			function selectAll(el){el.focus();el.select();}
		</script>
	</head>
	<body>
		<div id="header"><span id="logo">Wikidot Data Form Converter</span></div>
		<div id="content">
			<center>
				<script type="text/javascript"><!--
				google_ad_client = "ca-pub-2102065243904072";
				/* wd-dataform */
				google_ad_slot = "9181870869";
				google_ad_width = 728;
				google_ad_height = 15;
				//-->
				</script>
				<script type="text/javascript"
				src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
				</script>
			</center>
			<p><b>How to use:</b> This program converts existing forgiving YAML code into the current strict version.
				<ol>
					<li>Go to your _template page with an non-compliant YAML Data Form</li>
					<li>Edit the _template page (Ctrl + E), select the entire page's content and copy.</li>
					<li>Paste entire page source to the left textarea and click the &quot;&gt;&gt;&quot; button to convert</li>
					<li>Check if there are any errors in the status box and copy the converted code and replace the existing content</li>
				</ol>
			</p>
			<form method="post" class="grayshade">
				<table style="width: 100%; padding: 10px;">
					<tbody>
					<tr>
						<td colspan=3>
							<div id="status"><?php echo $status; ?></div>
						</td>
					</tr>
					<tr>
						<td style="width: 45%;">
							<b>Input:</b> Paste the entire page source from the _template page below (copy from the page editor)
						</td>
						<td style="width: 10%;"></td>
						<td style="width: 45%;">
							<b>Output:</b> Your new compliant _template code:
						</td>
					</tr>
					<tr>
						<td>
								<textarea name="yaml" style="height: 300px; width: 100%;"><?php if(!empty($templateCode)){ echo htmlentities($templateCode,ENT_COMPAT | ENT_HTML401,"UTF-8"); } ?></textarea><br />
						</td>
						<td style="vertical-align:middle;text-align:center;"><input type="submit" value=">>" /></td>
						<td>
							<textarea id="outputTA" onclick="selectAll(this);" style="height: 300px; width: 100%;"><?php echo htmlentities($textarea,ENT_COMPAT | ENT_HTML401,"UTF-8"); ?></textarea>
						</td>
					</tr>
					<tr>
						<td colspan=3>
							<span>&copy; Kenneth Tsang 2012.  All rights reserved.  Powered by SPYC and syYaml.</span><br />
							<span><b>Disclaimer:</b> This tool does not guarantee accurate output. Use at your own risk. If you encounter any issues, please leave a comment <a href="http://jxeeno.tk/blog:wikidot:from-forgiving-to-strict">here</a> with the exact code that's causing the issue.</span>
						</td>
				</tbody></table>
			</form>
			<center>
				<script type="text/javascript"><!--
				google_ad_client = "ca-pub-2102065243904072";
				/* wd-dataform */
				google_ad_slot = "9181870869";
				google_ad_width = 728;
				google_ad_height = 15;
				//-->
				</script>
				<script type="text/javascript"
				src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
				</script>
			</center>
		</div>
	</body>
</html>