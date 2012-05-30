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
        // XXX: Might need to replace magic no. 8 with a constant
		$content->total_items = ORM::factory('incident')
			->where('incident_active', '1')
			->limit('8')->count_all();
		$incidents = ORM::factory('incident')
			->where('incident_active', '1')
			->limit('10')
			->orderby('incident_date', 'desc')
			->find_all();

		$incident_video = array();
		$incident_photo = array();
		foreach($incidents as $incident) {
			$incident_video[$incident->id] = array();
			$incident_photo[$incident->id] = array();
			
			foreach($incident->media as $media)
			{
				// We only care about videos and photos
				if ($media->media_type == 2)
				{
					$incident_video[$incident->id][] = array(
						'link' => $media->media_link,
						'thumb' => $media->media_thumb
					);
				}
				elseif ($media->media_type == 1)
				{
					$incident_photo[$incident->id][] = array(
						'large' => url::convert_uploaded_to_abs($media->media_link),
						'thumb' => url::convert_uploaded_to_abs($media->media_thumb)
					);
				}
			}
		}

		// Video & photo links
		$content->incident_videos = $incident_video;
		$content->incident_photos = $incident_photo;

		$content->incidents = $incidents;

		// Create object of the video embed class
		$content->video_embed = new VideoEmbed();

		echo $content;
	}
}

new tedx_reports_block;