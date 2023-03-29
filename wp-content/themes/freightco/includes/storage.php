<?php
/**
 * Theme storage manipulations
 *
 * @package WordPress
 * @subpackage FREIGHTCO
 * @since FREIGHTCO 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('freightco_storage_get')) {
	function freightco_storage_get($var_name, $default='') {
		global $FREIGHTCO_STORAGE;
		return isset($FREIGHTCO_STORAGE[$var_name]) ? $FREIGHTCO_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('freightco_storage_set')) {
	function freightco_storage_set($var_name, $value) {
		global $FREIGHTCO_STORAGE;
		$FREIGHTCO_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('freightco_storage_empty')) {
	function freightco_storage_empty($var_name, $key='', $key2='') {
		global $FREIGHTCO_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($FREIGHTCO_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($FREIGHTCO_STORAGE[$var_name][$key]);
		else
			return empty($FREIGHTCO_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('freightco_storage_isset')) {
	function freightco_storage_isset($var_name, $key='', $key2='') {
		global $FREIGHTCO_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($FREIGHTCO_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($FREIGHTCO_STORAGE[$var_name][$key]);
		else
			return isset($FREIGHTCO_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('freightco_storage_inc')) {
	function freightco_storage_inc($var_name, $value=1) {
		global $FREIGHTCO_STORAGE;
		if (empty($FREIGHTCO_STORAGE[$var_name])) $FREIGHTCO_STORAGE[$var_name] = 0;
		$FREIGHTCO_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('freightco_storage_concat')) {
	function freightco_storage_concat($var_name, $value) {
		global $FREIGHTCO_STORAGE;
		if (empty($FREIGHTCO_STORAGE[$var_name])) $FREIGHTCO_STORAGE[$var_name] = '';
		$FREIGHTCO_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('freightco_storage_get_array')) {
	function freightco_storage_get_array($var_name, $key, $key2='', $default='') {
		global $FREIGHTCO_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($FREIGHTCO_STORAGE[$var_name][$key]) ? $FREIGHTCO_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($FREIGHTCO_STORAGE[$var_name][$key][$key2]) ? $FREIGHTCO_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('freightco_storage_set_array')) {
	function freightco_storage_set_array($var_name, $key, $value) {
		global $FREIGHTCO_STORAGE;
		if (!isset($FREIGHTCO_STORAGE[$var_name])) $FREIGHTCO_STORAGE[$var_name] = array();
		if ($key==='')
			$FREIGHTCO_STORAGE[$var_name][] = $value;
		else
			$FREIGHTCO_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('freightco_storage_set_array2')) {
	function freightco_storage_set_array2($var_name, $key, $key2, $value) {
		global $FREIGHTCO_STORAGE;
		if (!isset($FREIGHTCO_STORAGE[$var_name])) $FREIGHTCO_STORAGE[$var_name] = array();
		if (!isset($FREIGHTCO_STORAGE[$var_name][$key])) $FREIGHTCO_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$FREIGHTCO_STORAGE[$var_name][$key][] = $value;
		else
			$FREIGHTCO_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Merge array elements
if (!function_exists('freightco_storage_merge_array')) {
	function freightco_storage_merge_array($var_name, $key, $value) {
		global $FREIGHTCO_STORAGE;
		if (!isset($FREIGHTCO_STORAGE[$var_name])) $FREIGHTCO_STORAGE[$var_name] = array();
		if ($key==='')
			$FREIGHTCO_STORAGE[$var_name] = array_merge($FREIGHTCO_STORAGE[$var_name], $value);
		else
			$FREIGHTCO_STORAGE[$var_name][$key] = array_merge($FREIGHTCO_STORAGE[$var_name][$key], $value);
	}
}

// Add array element after the key
if (!function_exists('freightco_storage_set_array_after')) {
	function freightco_storage_set_array_after($var_name, $after, $key, $value='') {
		global $FREIGHTCO_STORAGE;
		if (!isset($FREIGHTCO_STORAGE[$var_name])) $FREIGHTCO_STORAGE[$var_name] = array();
		if (is_array($key))
			freightco_array_insert_after($FREIGHTCO_STORAGE[$var_name], $after, $key);
		else
			freightco_array_insert_after($FREIGHTCO_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('freightco_storage_set_array_before')) {
	function freightco_storage_set_array_before($var_name, $before, $key, $value='') {
		global $FREIGHTCO_STORAGE;
		if (!isset($FREIGHTCO_STORAGE[$var_name])) $FREIGHTCO_STORAGE[$var_name] = array();
		if (is_array($key))
			freightco_array_insert_before($FREIGHTCO_STORAGE[$var_name], $before, $key);
		else
			freightco_array_insert_before($FREIGHTCO_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('freightco_storage_push_array')) {
	function freightco_storage_push_array($var_name, $key, $value) {
		global $FREIGHTCO_STORAGE;
		if (!isset($FREIGHTCO_STORAGE[$var_name])) $FREIGHTCO_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($FREIGHTCO_STORAGE[$var_name], $value);
		else {
			if (!isset($FREIGHTCO_STORAGE[$var_name][$key])) $FREIGHTCO_STORAGE[$var_name][$key] = array();
			array_push($FREIGHTCO_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('freightco_storage_pop_array')) {
	function freightco_storage_pop_array($var_name, $key='', $defa='') {
		global $FREIGHTCO_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($FREIGHTCO_STORAGE[$var_name]) && is_array($FREIGHTCO_STORAGE[$var_name]) && count($FREIGHTCO_STORAGE[$var_name]) > 0) 
				$rez = array_pop($FREIGHTCO_STORAGE[$var_name]);
		} else {
			if (isset($FREIGHTCO_STORAGE[$var_name][$key]) && is_array($FREIGHTCO_STORAGE[$var_name][$key]) && count($FREIGHTCO_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($FREIGHTCO_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('freightco_storage_inc_array')) {
	function freightco_storage_inc_array($var_name, $key, $value=1) {
		global $FREIGHTCO_STORAGE;
		if (!isset($FREIGHTCO_STORAGE[$var_name])) $FREIGHTCO_STORAGE[$var_name] = array();
		if (empty($FREIGHTCO_STORAGE[$var_name][$key])) $FREIGHTCO_STORAGE[$var_name][$key] = 0;
		$FREIGHTCO_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('freightco_storage_concat_array')) {
	function freightco_storage_concat_array($var_name, $key, $value) {
		global $FREIGHTCO_STORAGE;
		if (!isset($FREIGHTCO_STORAGE[$var_name])) $FREIGHTCO_STORAGE[$var_name] = array();
		if (empty($FREIGHTCO_STORAGE[$var_name][$key])) $FREIGHTCO_STORAGE[$var_name][$key] = '';
		$FREIGHTCO_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('freightco_storage_call_obj_method')) {
	function freightco_storage_call_obj_method($var_name, $method, $param=null) {
		global $FREIGHTCO_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($FREIGHTCO_STORAGE[$var_name]) ? $FREIGHTCO_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($FREIGHTCO_STORAGE[$var_name]) ? $FREIGHTCO_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('freightco_storage_get_obj_property')) {
	function freightco_storage_get_obj_property($var_name, $prop, $default='') {
		global $FREIGHTCO_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($FREIGHTCO_STORAGE[$var_name]->$prop) ? $FREIGHTCO_STORAGE[$var_name]->$prop : $default;
	}
}
?>