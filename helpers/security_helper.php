<?php
/**
 * PHP 5
 *
 * LucidFrame : Simple & Flexible PHP Development
 * Copyright (c), LucidFrame.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @package     LC.helpers 
 * @author		Sithu K. <cithukyaw@gmail.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Strips HTML tags in the value to prevent from XSS attack. It should be called for GET values.
 * @param  mixed $value The value which to be stripped.
 * @return mixed the cleaned value
 */	 
function _get($get){
	if(is_array($get)){
		foreach($get as $name=>$value){
			if(is_array($value)){
				$get[$name] = _get($value);
			}else{
				$value = strip_tags(trim($value));
				$value = urldecode($value);
				$get[$name] = $value;
			}
		}
		return $get;
	}else{
		$value = strip_tags(trim($get));
		return urldecode($value);
	}
}
/**
 * Strips HTML tags in the value to prevent from XSS attack. It should be called for POST values.
 * @param  mixed $value The value which to be stripped.
 * @return mixed the cleaned value
 */	
function _post($post){
	if(is_array($post)){
		foreach($post as $name=>$value){
			if(is_array($value)){
				$post[$name] = _post($value);
			}else{				
				$value = stripslashes($value);
				$value = htmlspecialchars($value, ENT_QUOTES);
				$value = strip_tags(trim($value));
				$post[$name] = $value;
			}
		}
	}else{		
		$value = stripslashes($post);
		$value = htmlspecialchars($value, ENT_QUOTES);
		$value = strip_tags(trim($post));
		return $value;		
	}
	return $post;
}
/**
 * Strips javascript tags in the value to prevent from XSS attack
 * @param 	mixed $value the value which to be stripped.
 * @return 	mixed the cleaned value
 */	
function _xss($value){
	if(is_object($value)) return $value;
	$pattern = '@<script[^>]*?>.*?</[^>]*?script[^>]*?>@si'; # strip out javacript
	if(is_array($value)){
		foreach($value as $key => $val){
			if(is_array($val)){
				$value[$key] = _xss($val);
			}else{
				$value[$key] = preg_replace( $pattern, '', trim(stripslashes($val))); 				
			}
		}
	}else{
		$value = preg_replace( $pattern, '', trim(stripslashes($value)));  
	}
	return $value; 
}	