<?php
class Session
{
	public static function load($key, $sub_key = null)
	{
		if ($sub_key) {
			if (isset($_SESSION[$sub_key][$key])) return $_SESSION[$sub_key][$key];
		} else if ($key) {
			if (isset($_SESSION[$key])) return $_SESSION[$key];
		}
	}

	public static function add($key, $values, $sub_key = null)
	{
		if ($sub_key) {
			$_SESSION[$sub_key][$key] = $values;
		} else if ($key) {
			$_SESSION[$key] = $values;
		}
	}

	public static function start()
	{
		session_start();
		session_regenerate_id(true);
	}

	public static function destroy()
	{
		session_destroy();
	}

	public static function show($key)
	{
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		}
	}
}
