<?php

if (!(defined('ABSPATH') || defined('MCDATAPATH'))) exit;
if (!class_exists('BVFWRuleEvaluator')) :

class BVFWRuleEvaluator {
	private $request;

	const VERSION = 0.2;

	public function __construct($request) {
		$this->request = $request;
	}

	function getErrors() {
			return $this->errors;
	}

	function resetErrors() {
		$this->errors = array();
	}

	// ================================ Functions for type checking ========================================
	function isNumeric($value) {
		return (preg_match('/^\d+$/', $value));
	}

	function isRegularWord($value) {
		return (preg_match('/^\w+$/', $value));
	}

	function isSpecialWord($value) {
		return (preg_match('/^\S+$/', $value));
	}

	function isRegularSentence($value) {
		return (preg_match('/^[\w\s]+$/', $value));
	}

	function isSpecialCharsSentence($value) {
		return (preg_match('/^[\w\W]+$/', $value));
	}

	function isLink($value) {
		return (preg_match('/^(http|ftp)s?:\/\/\S+$/i', $value));
	}

	function isFileUpload($value) {
		$file = $this->getFiles($value);
		if (is_array($file) && in_array('tmp_name', $file)) {
			return is_uploaded_file($file['tmp_name']);
		}
		return false;
	}

	function isIpv4($value) {
		return (preg_match('/^\b((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])\.){3}(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])\b$/x', $value));
	}

	function isEmbededIpv4($value) {
		return (preg_match('/\b((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])\.){3}(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])\b/x', $value));
	}

	function isIpv6($value) {
		return (preg_match('/^(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))$/x', $value));
	}

	function isEmbededIpv6($value) {
		return (preg_match('/(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))/x', $value));
	}

	function isEmail($value) {
		return (preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/', $value));
	}

	function isEmbededEmail($value) {
		return (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}/', $value));
	}

	function isEmbededLink($value) {
		return (preg_match('/(http|ftp)s?:\/\/\S+$/i', $value));
	}

	function isEmbededHtml($value) {
		return (preg_match('/<(html|head|title|base|link|meta|style|picture|source|img|iframe|embed|object|param|video|audio|track|map|area|form|label|input|button|select|datalist|optgroup|option|textarea|output|progress|meter|fieldset|legend|script|noscript|template|slot|canvas)/ix', $value));
	}

	function isFile($value) {
		return (preg_match('/\.(jpg|jpeg|png|gif|ico|pdf|doc|docx|ppt|pptx|pps|ppsx|odt|xls|zip|gzip|xlsx|psd|mp3|m4a|ogg|wav|mp4|m4v|mov|wmv|avi|mpg|ogv|3gp|3g2|php|html|phtml|js|css)/ix', $value));
	}

	function isPathTraversal($value) {
		return (preg_match('/(?:\.{2}[\/]+)/', $value));
	}

	function isPhpEval($value) {
		return (preg_match('/\\b(?i:eval)\\s*\\(\\s*(?i:base64_decode|exec|file_get_contents|gzinflate|passthru|shell_exec|stripslashes|system)\\s*\\(/', $value));
	}

	// ================================ Functions to perform operations ========================================
	function contains($val, $subject) {
		if (is_array($val)) {
			return in_array($val, $subject);
		}
		return strpos((string) $subject, (string) $val) !== false;
	}

	function notContains($val, $subject) {
		return !$this->contains($val, $subject);
	}

	function match($pattern, $subject) {
		if (is_array($subject)) {
			foreach ($subject as $k => $v) {
				if ($this->match($pattern, $v)) {
					return true;
				}
			}
			return false;
		}
		$resp = preg_match((string) $pattern, (string) $subject);
		if ($resp === false) {
			array_push($this->errors, array("preg_match", $subject));
		} else if ($resp > 0) {
			return true;
		}
		return false;
	}

	function notMatch($pattern, $subject) {
		return !$this->match($pattern, $subject);
	}

	function matchCount($pattern, $subject) {
		$count = 0;
		if (is_array($subject)) {
			foreach ($subject as $val) {
				$count += $this->matchCount($pattern, $val);
			}
			return $count;
		}
		$count = preg_match_all((string) $pattern, (string) $subject, $matches);
		if ($count === false) {
			array_push($this->errors, array("preg_match_all", $subject));
		}
		return $count;
	}

	function maxMatchCount($pattern, $subject) {
		$count = 0;
		if (is_array($subject)) {
			foreach ($subject as $val) {
				$count = max($count, $this->matchCount($pattern, $val));
			}
			return $count;
		}
		$count = preg_match_all((string) $pattern, (string) $subject, $matches);
		if ($count === false) {
			array_push($this->errors, array("preg_match_all", $subject));
		}
		return $count;
	}

	function equals($val, $subject) {
		return ($val == $subject);
	}

	function notEquals($val, $subject) {
		return !$this->equals($val, $subject);
	}

	function isIdentical($val, $subject) {
		return ($val === $subject);
	}

	function notIdentical($val, $subject) {
		return !$this->isIdentical($val, $subject);
	}

	function greaterThan($val, $subject) {
		return ($subject > $val);
	}

	function greaterThanEqualTo($val, $subject) {
		return ($subject >= $val);
	}

	function lessThan($val, $subject) {
		return ($subject < $val);
	}

	function lessThanEqualTo($val, $subject) {
		return ($subject <= $val);
	}

	function lengthGreaterThan($val, $subject) {
		return (strlen((string) $subject) > $val);
	}

	function lengthLessThan($val, $subject) {
		return (strlen((string) $subject) < $val);
	}

	function md5Equals($val, $subject) {
		return (md5((string) $subject) === $val);
	}

	function compareMultipleSubjects($func, $args, $subjects) {
		// TODO
	}

	// ================================ Functions to get request data ========================================
	function getReqInfo($key) {
		return $this->request->getReqInfo($key);
	}

	function getPath() {
		return $this->request->getPath();
	}

	function getServerValue($key) {
		return $this->request->getServerValue($key);
	}

	function getHeader($key) {
		return $this->request->getHeader($key);
	}

	function getHeaders() {
		return $this->request->getHeaders();
	}

	function getPostParams() {
		if (func_num_args() > 0) {
			$args = func_get_args();
			return $this->request->getPostParams($args);
		}
		return $this->request->getPostParams();
	}

	function getReqMethod() {
		return $this->request->getMethod();
	}

	function getGetParams() {
		if (func_num_args() > 0) {
			$args = func_get_args();
			return $this->request->getGetParams($args);
		}
		return $this->request->getGetParams();
	}

	function getCookies() {
		if (func_num_args() > 0) {
			$args = func_get_args();
			return $this->request->getCookies($args);
		}
		return $this->request->getCookies();
	}

	function getFiles() {
		if (func_num_args() > 0) {
			$args = func_get_args();
			return $this->request->getFiles($args);
		}
		return $this->request->getFiles();
	}

	function getFileNames() {
		if (func_num_args() > 0) {
			$args = func_get_args();
			return $this->request->getFileNames($args);
		}
		return $this->request->getFileNames();
	}

	function getHost() {
		return $this->host;
	}

	function getURI() {
		return $this->request->getURI();
	}

	function getIP() {
		return $this->request->getIP();
	}

	function getTimestamp() {
		return $this->request->getTimeStamp();
	}

	function getUserRoleLevel() {
		return $this->request->getUserRoleLevel();
	}

	function isUserRoleLevel($level) {
		return $this->request->isUserRoleLevel($level);
	}

	function getAllParams() {
		return $this->request->getAllParams();
	}

	// ================================ Functions to evaluate rule logic ========================================
	function evaluateRule($ruleLogic) {
		return $this->evaluateExpression($ruleLogic);
	}
	
	function evaluateExpression($expr) {
		switch ($expr["type"]) {
		case "AND" :
			$loperand = $this->getValue($expr["left_operand"]);
			$roperand = $this->getValue($expr["right_operand"]);
			return ($loperand && $roperand);
		case "OR" :
			$loperand = $this->getValue($expr["left_operand"]);
			$roperand = $this->getValue($expr["right_operand"]);
			return ($loperand || $roperand);
		case "NOT" :
			return !$this->getValue($expr["value"]);
		case "FUNCTION" :
			return $this->executeFunctionCall($expr);
		default :
			break;
		}
	}

	function fetchConstantValue($name) {
		$value = constant($name);
		if ($value) {
			return $value;
		}
		array_push($this->errors, array("fetch_constant_value", $name));
		return false;
	}

	function getArgs($args) {
		$_args = array();
		foreach ($args as $arg) {
			array_push($_args, $this->getValue($arg));
		}
		return $_args;
	}

	function executeFunctionCall($func) {
		$name = $func["name"];
		$handler = array($this, $name);
		if (!is_callable($handler)) {
			array_push($this->errors, array("execute_function_call", "function_not_allowed", $name));
			return false;
		}
		return call_user_func_array($handler, $this->getArgs($func["args"]));
	}

	function getValue($expr) {
		switch ($expr["type"]) {
		case "NUMBER" :
			return $expr["value"];
		case "STRING" :
			return $expr["value"];
		case "STRING_WITH_QUOTES" :
			$expr["value"] = preg_replace("/^('|\")/", "", $expr["value"]);
			$expr["value"] = preg_replace("/('|\")$/", "", $expr["value"]);
			return $expr["value"];
		case "CONST" :
			return $this->fetchConstantValue($expr["value"]);
		case "FUNCTION" :
			return $this->executeFunctionCall($expr);
		default :
			return $this->evaluateExpression($expr);
		}
	}
}
endif;