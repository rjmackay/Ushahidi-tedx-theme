<?php defined('SYSPATH') or die('No direct script access.');

class tedx_reports_block {
	
	public function __construct()
	{
		$block = array(
			"classname" => "tedx_reports_block",
			"name" => "TEDx Reports",
			"description" => "List the 10 latest reports in the system"
		);
		
		blocks::register($block);
	}
	
	public function block()
	{
		$content = new View('blocks/tedx_recent_reports');
		
		// Get Reports
		$incidents = ORM::factory('incident')
			->select('DISTINCT incident.id', 'incident.*','media.*')
			->where('incident_active', '1')
			->join('media', 'incident.id', 'incident_id')
			->in('media_type', array(1,2))
			->where('media_thumb IS NOT NULL')
			->where('media_thumb != ""')
			->limit('10')
			->orderby('incident_date', 'desc')
			->find_all();

		$content->incidents = $incidents;
		$content->total_items = count($incidents);

		// Create object of the video embed class
		$content->video_embed = new VideoEmbed();

		echo $content;
	}
}

new tedx_reports_block;