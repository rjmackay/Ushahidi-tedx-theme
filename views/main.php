
</div>
<!-- / wrapper -->

<script type="text/javascript">
$(function(){
	
	// show/hide report filters and layers boxes on home page map
	$("a.toggle").toggle(
	function() { 
		$($(this).attr("href")).show();
		$(this).addClass("active-toggle");
	},
	function() { 
		$($(this).attr("href")).hide();
		$(this).removeClass("active-toggle");
	}
	);
	
});

</script>
<!-- main -->
<div id="main" class="clearingfix">
	<?php include('div_main.php'); ?>
</div>
<!-- / main -->

<!-- wrapper -->
<div class="rapidxwpr floatholder">

	<!-- middle -->
	<div id="middle">

		<?php
		echo $div_timeline;
		?>
		
		<h2 id="tagline">Community powered map of TEDx Talks</h2>
		
		
		<div class="background layoutleft">
		
			<!-- mainmenu -->
			<div id="mainmenu" class="clearingfix">
				<ul>
					<?php nav::main_tabs('home'); ?>
				</ul>
			</div>
			<!-- / mainmenu -->
		
			<!-- content -->
			<div class="content-container">

				<!-- content blocks -->
				<div class="content-blocks clearingfix">
					<ul class="content-column">
						<?php blocks::render(); ?>
					</ul>
				</div>
				<!-- /content blocks -->

			</div>
			<!-- content -->
		</div>
	</div>
	<!-- / middle -->

</div>
<!-- / wrapper -->
