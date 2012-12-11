<?php defined('SYSPATH') or die('No direct script access.');
/**
 * TED Hook - Load All Events
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package	   Ushahidi - http://source.ushahididev.com
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class tedx {
	
	/**
	 * Registers the main event add method
	 */
	public function __construct()
	{
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));
	}
	
	/**
	 * Adds all the events to the main Ushahidi application
	 */
	public function add()
	{
		// Only add the events if we are on that controller
		if (Router::$controller == 'main')
		{
			switch (Router::$method)
			{
				case 'index':
					Event::add('ushahidi_action.themes_add_requirements_pre_theme', array($this, 'add_js'));
			}
		}
	}
	
	public function add_js()
	{
		$themes = Event::$data;
		
		if ($themes->map_enabled)
		{
			// Add ushahidi.js extensions
			Requirements::js('themes/tedx/js/ushahidi.tedx.js');
		}
	}
	
}

new tedx;