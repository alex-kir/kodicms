<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

abstract class Behavior_Abstract {
	
	// What must be escaped in the route regex
	const REGEX_ESCAPE  = '[.\\+*?[^\\]${}=!|]';
	
	// What can be part of a <segment> value
	const REGEX_SEGMENT = '[^/.,;?\n]++';
	
	/**
	 *
	 * @var array 
	 */
	protected $_routes = array();
	
	/**
	 *
	 * @var string 
	 */
	protected $_matched_route = NULL;

	/**
	 *
	 * @var Model_Page_Front 
	 */
	protected $_page;
	
	/**
	 *
	 * @var array 
	 */
	protected $_params = array();

	/**
	 * 
	 * @param Model_Page_Front $page
	 * @param string $url
	 * @param string $uri
	 */
	public function __construct( Model_Page_Front &$page, $url, $uri)
	{
		$this->_page = &$page;
		
		$uri = substr($uri, strlen($url));

		$this->_match_route($uri);
	}
	
	/**
	 * 
	 * @param string $name
	 * @return string|NULL
	 */
	public function __get($name) 
	{
		return $this->param($name);
	}
	
	/**
	 * 
	 * @param string $name
	 * @param mixed $default
	 * @return string|NULL
	 */
	public function param($name, $default = NULL)
	{
		return isset($this->_params[$name]) 
			? $this->_params[$name] 
			: $default;
	}
	
	/**
	 * 
	 * @param string $name
	 * @return boolean
	 */
	public function __isset($name) 
	{
		return isset($this->_params[$name]);
	}
	
	/**
	 * 
	 * @param string $uri
	 */
	final protected function _match_route($uri)
	{
		foreach ($this->_routes as $_uri => $params)
		{
			if( ! isset($params['method']))
			{
				$params['method'] = 'execute';
			}

			// The URI should be considered literal except for keys and optional parts
			// Escape everything preg_quote would escape except for : ( ) < >
			$expression = preg_replace('#'.self::REGEX_ESCAPE.'#', '\\\\$0', $_uri);

			// Insert default regex for keys
			$expression = str_replace(array('<', '>'), array('(?P<', '>'.self::REGEX_SEGMENT.')'), $expression);
			
			if ( isset($params['regex']) )
			{
				$search = $replace = array();
				foreach ($params['regex'] as $key => $value)
				{
					$search[]  = "<$key>".Route::REGEX_SEGMENT;
					$replace[] = "<$key>$value";
				}

				// Replace the default regex with the user-specified regex
				$expression = str_replace($search, $replace, $expression);
			}

			if ( ! preg_match('#^'.$expression.'$#uD', $uri, $matches))
				continue;

			foreach ($matches as $key => $value)
			{
				if (is_int($key))
				{
					// Skip all unnamed keys
					continue;
				}

				// Set the value for all matched keys
				$this->_params[$key] = $value;
			}
			
			$this->_matched_route = $_uri;
			return $this->{$params['method']}();
		}
		
		$this->_params = preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY);		
		return $this->execute();
	}
	
	abstract public function execute();
}