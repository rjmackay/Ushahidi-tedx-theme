<?php blocks::open("reports");?>
<?php //blocks::title(Kohana::lang('ui_main.reports_listed'));?>
		<?php
		if ($total_items == 0)
		{
			?>
			<div><?php echo Kohana::lang('ui_main.no_reports'); ?></div>
			<?php
		}
		$i = 0;
		foreach ($incidents as $incident)
		{
			$incident_id = $incident->id;
			$incident_title = text::limit_chars($incident->incident_title, 40, '...', True);
			$incident_date = $incident->incident_date;
			$incident_date = date('j M Y', strtotime($incident->incident_date));
			$incident_location = $incident->location->location_name;
			$incident_category = $incident->category->current() ? $incident->category->current()->category_title : '';
			$incident_video = false;
			if (isset($incident_videos[$incident_id][0]))
			{
				$incident_video = $incident_videos[$incident_id][0]['thumb'] ? $incident_videos[$incident_id][0]['thumb'] : $video_embed->thumb($incident_videos[$incident_id][0]['link']);
			}
			if ($incident_video) {
		?>
		<div class="report <?php echo "col-".($i % 5); ?>">
			<a href="<?php echo url::site() . 'reports/view/' . $incident_id; ?>"> 
			<div class="report-image"><img src="<?php echo $incident_video ?>" width="160" /></div>
			<div  class="report-date"><?php echo $incident_date; ?></div>
			<div  class="report-location"><?php echo $incident_category ?></div>
			<div class="report-title"><?php echo $incident_title ?></div>
			</a>
		</div>
		<?php
			$i++;
			}
		}
		?>
	<a class="more" href="<?php echo url::site() . 'reports/' ?>"><?php echo Kohana::lang('ui_main.view_more'); ?></a>
<div style="clear:both;"></div>
<?php blocks::close();?>