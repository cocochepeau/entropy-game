<?php
class Cookie
{
	const Session = 0;
	const OneHour = 3600;
	const OneDay = 86400;
	const SevenDays = 604800;
	const ThirtyDays = 18144000;
	const SixMonths = 108864000;

	/**
	* Returns true if there is a cookie with this name.
	*
	* @param string $name
	* @return bool
	*/
	public static function exists($name)
	{
		return isset($_COOKIE[$name]);
	}

	/**
	* Returns true if there no cookie with this name or it's empty, or 0,
	* or a few other things. Check http://php.net/empty for a full list.
	*
	* @param string $name
	* @return bool
	*/
	public static function isEmpty($name)
	{
		return empty($_COOKIE[$name]);
	}

	/**
	* Set a cookie. Silently does nothing if headers have already been sent.
	*
	* @param string $name
	* @param string $value
	* @param mixed $expiry
	* @param string $path
	* @param string $domain
	* @return bool
	*/
	public static function set($name, $value = '', $expiry = self::Session, $path = '/', $domain = '', $secure = false, $httpOnly = false)
	{
		if(!headers_sent())
		{
			if(is_numeric($expiry) && $expiry > 0) $expiry = time() + $expiry;

			if(@setcookie($name, $value, $expiry, $path, $domain, $secure, $httpOnly))
			{
				$_COOKIE[$name] = $value;
				return true;
			}
		}
		return false;
	}

	/**
	* Get the value of the given cookie.
	*
	* @param string $name
	* @return mixed
	*/
	public static function get($name)
	{
		if(isset($_COOKIE[$name])) return $_COOKIE[$name];
		return false;
	}

	/**
	* Delete a cookie.
	*
	* @param string $name
	* @param string $path
	* @param string $domain
	* @param bool $removeFromGlobal set to true to remove this cookie from $_REQUEST.
	* @return bool
	*/
	public static function delete($name, $path = '/', $domain = '', $removeFromGlobal = true)
	{
		if(!headers_sent())
		{
			$retVal =@ setcookie($name, '', time() - 3600, $path, $domain);

			if($removeFromGlobal) unset($_COOKIE[$name]);

			if($retVal) return true;
		}
		return false;
	}
}
