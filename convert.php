<?php
/**
 * wdConverter - Wikidot Data Form Converter
 * Copyright (c) Kenneth Tsang 2012
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * @copyright Copyright (c) Kenneth Tsang 2012
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
	$templateCode = $_POST["yaml"];

	$textarea = "";
	$status = "";
	function checkStatus($array){
		$acceptedValues = array("label","type","values","default","category","join","before","after","width","height","match","match-error","hint","value","default-schema","required");
		if(isset($array["fields"])){
			if(count($array)!=1){
				return "<b><span class=\"er-red\">Notice! There is more than one parent-level value. Usually, it is reserved for <i>fields:</i> only.</span></b><br />(Try autocorrect to fix problems) Please check indenting and copy the <b>entire</b> _template from the page editor.";
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
								if(isset($value["type"]) && $value["type"]=="select"){
									$noticeString .= "<br />Indentation for <i>$k</i> appears to be wrong (under <i>$key</i>)";
								}else{
									$noticeString .= "<br /><i>$k</i> is not a recognised property name (under <i>$key</i>)";
								}
							}
						}
					}
					if(!$isokay){
						return "<b><span class=\"er-red\">Notice! There are invalid data form properties defined. (Try autocorrect to fix problems)</span></b>$noticeString";
					}else{
						return "<b>Your data form has been successfully converted!</b>";
					}
				}
			}
		}else{
			return "<b><span class=\"er-red\">ERROR! Invalid Data Form input!  YAML must contain <i>fields:</i></span></b><br />Please check if you have spelt <i>fields:</i> correctly!";
		}
	}
	function autoCorrect($array){
		global $status;
		$status .= "<br />[AUTOCORRECT MESSAGES]";
		$acceptedValues = array("label","type","values","default","category","join","before","after","width","height","match","match-error","hint","value","default-schema","required");
		if(isset($array["fields"])){
			if(!is_array($array['fields'])){
				return $array;
			}
			if(count($array)!=1){
				$_tmpArr = array("fields"=>$array["fields"]);
				foreach($array as $key=>$value){
					if($key!="fields"){
						$_tmpArr["fields"][$key] = $value;
						$status .= "<br />[AC]: Fixed indenting of <i>$key</i>";
					}
				}
				$array = $_tmpArr;
			}
			
			$_tmpArr = array("fields"=>array());
			foreach($array["fields"] as $key=>$value){
				$_tmpInnerArr = array();
				$_tmpValues = array();
				foreach($value as $k => $v){
					if(!in_array($k,$acceptedValues)){
						if(isset($value["type"]) && $value["type"]=="select"){
							if(!isset($_tmpInnerArr["values"])){ $_tmpInnerArr["values"]=array(); }
							$_tmpInnerArr["values"][$k] = $v;
							$status .= "<br />[AC]: Fixed indenting of value <i>$k</i> in <i>$key</i>";
						}else{$_tmpInnerArr[$k]=$v;}
					}else{$_tmpInnerArr[$k]=$v;}
				}
				$_tmpArr["fields"][$key] = $_tmpInnerArr;
			}
			$array = $_tmpArr;
			return $array;
		}else{ return $array; }
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
			if(isset($_POST["autocorrect"])){
				$dfarray = autoCorrect($dfarray);
			}
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