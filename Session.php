<?php
class Session
{

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
