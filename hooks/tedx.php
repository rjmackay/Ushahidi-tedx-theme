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
		$this->ted_id = "";
		
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
				// Hook into the Report Add/Edit Form in Admin
				case 'index':
					// Hook in after the controller to modify template vars
					Event::add('ushahidi_filter.main_content', array($this, '_post_main'));
			}
		}
	}
	
	/**
	 * Modify main template vars
	 */
	public function _post_main()
	{
		Event::$data->site_tagline = Kohana::config('settings.site_tagline');
		//var_dump(Event::$data); exit();
	}
	
}

new tedx;