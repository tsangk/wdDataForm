<html>
	<head>
		<!-- Copyright (c) Kenneth Tsang 2012 -->
		<!-- Automated access to this program is strictly forbidden -->
		<title>Wikidot Data Form (Forgiving to Strict)</title>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
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
			#spinner-wrap{ display:none; }
		</style>
		<script type="text/javascript">
			function selectAll(el){el.focus();el.select();}
			function convertYAML(){
				$("#spinner-wrap").fadeIn();
				$("#button-wrap").hide();
				$.post("convert.php",$("#yamlForm").serialize(),function(data){
					$("#outputTA").val(data.textarea);
					$("#status").html(data.status);
					$("#spinner-wrap").fadeOut(function(){$("#button-wrap").fadeIn();});
				},"json");
			}
		</script>
		<?php if(isset($_GET["iframe"])){ ?>
		<script type="text/javascript" src="http://snippets.wdfiles.com/local--code/code:custom-html/1"></script>
		<style>
			#header{ display: none; }
			#content{
				margin-top: 5px;
			}
		</style>
		<?php } ?>
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
			<form method="post" action="javascript:;" class="grayshade" onsubmit="convertYAML();" id="yamlForm">
				<table style="width: 100%; padding: 10px;">
					<tbody>
					<tr>
						<td colspan=3>
							<div id="status"><b>Converter Ready</b></div>
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
								<textarea id="inputTA" name="yaml" style="height: 300px; width: 100%;"></textarea><br />
						</td>
						<td style="vertical-align:middle;text-align:center;"><div id="button-wrap"><input type="submit" value=">>" /><br /><br />Autocorrect?<br /><span style="font-size:80%">(BETA)</span><br /><input type="checkbox" name="autocorrect" /></div><div id="spinner-wrap"><img src="img/spin.gif" /></div></td>
						<td>
							<textarea id="outputTA" onclick="selectAll(this);" style="height: 300px; width: 100%;"></textarea>
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