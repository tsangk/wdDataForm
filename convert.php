<?php
	$templateCode = $_POST["yaml"];

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
	echo json_encode(array("textarea"=>$textarea,"status"=>$status));
?>