/**
 * Copyright (c) 2008-2012 by Ushahidi Dev Team
 * Published under the LGPL license. See License.txt for the
 * full text of the license
 *
 * @requires media/js/ushahidi.js
 */
(function(){
	/**
	 * APIMethod: onFeatureSelect
	 *
	 * Callback that is executed when a feature that is on the map is
	 * selected. When executed, it displays a popup with the content/information
	 * about the selected feature
	 */
	Ushahidi.Map.prototype.onFeatureSelect = function(event) {
		this._selectedFeature = event.feature;

		zoom_point = event.feature.geometry.getBounds().getCenterLonLat();
		lon = zoom_point.lon;
		lat = zoom_point.lat;

		content = this.buildPopupContent(event.feature);
		
		// Destroy existing popups before opening a new one
		if (event.feature.popup != null) {
			map.removePopup(event.feature.popup);
		}

		// Create the popup
		var anchor = {'size': new OpenLayers.Size(0,0), 'offset': new OpenLayers.Pixel(0, 0)};
		var popup = new OpenLayers.Popup.Anchored("chicken", 
			event.feature.geometry.getBounds().getCenterLonLat(),
			new OpenLayers.Size(320,320),
			content,
			anchor, false, this.onPopupClose);
		popup.panMapIfOutOfView = true;
		popup.autoSize = true;
		event.feature.popup = popup;
		this._olMap.addPopup(popup);
		popup.show();

		// Register zoom in/out events
		$("#zoomIn", popup.contentDiv).click(
			{context: this, latitude: lat, longitude: lon, zoomFactor: 1}, 
			this.zoomToSelectedFeature);

		$("#zoomOut", popup.contentDiv).click(
			{context: this, latitude: lat, longitude: lon, zoomFactor: -1}, 
			this.zoomToSelectedFeature);
		
		// Setup slideshow
		context = this;
		if (event.feature.attributes.incident_ids) 
		{
			event.feature.current_id = 0;
			
			// Update popup content with new incident
			var updateContent = function (increment) {
				// Increment id
				context._selectedFeature.current_id = context._selectedFeature.current_id + increment;
				
				// Handle boundaries
				if (context._selectedFeature.current_id < 0)
				{
					context._selectedFeature.current_id = context._selectedFeature.attributes.incident_ids.length -1;
				} else if (context._selectedFeature.current_id >= context._selectedFeature.attributes.incident_ids.length -1) {
					context._selectedFeature.current_id = 0;
				}
				// Grab new incident data
				$.getJSON(Ushahidi.baseURL+'json/single/'+context._selectedFeature.attributes.incident_ids[context._selectedFeature.current_id],
					function(data)
					{
						// Replacing popup content
						var feature = data.features[data.features.length -1];
						feature.attributes = feature.properties;
						feature.attributes.incident_ids = context._selectedFeature.attributes.incident_ids;
						feature.current_id = context._selectedFeature.current_id;
						
						popup.setContentHTML(context.buildPopupContent(feature));
						
						$('.infowindow .prev').click(function (event) {
							updateContent(-1);
							return false;
						});
						$('.infowindow .next').click(function (event) {
							updateContent(+1);
							return false;
						});
					}
				);
			}
			
			updateContent(0);
		}
	}
	
	/**
	 * APIMethod: buildPopupContent
	 *
	 * Build html content for popups when features selected. 
	 * Called by onFeatureSelect
	 *
	 * Parameters:
	 * feature   - {Object} GeoJSON feature object
	 */
	Ushahidi.Map.prototype.buildPopupContent = function(feature)
	{
		// Image to display within the popup
		var image = "";
		/*if (feature.attributes.thumb !== undefined && feature.attributes.thumb != '') {
			image = "<div class=\"infowindow_image\"><a href='"+feature.attributes.link+"'>";
			image += "<img src=\""+feature.attributes.thumb+"\" height=\"59\" width=\"89\" /></a></div>";
		} else */if (feature.attributes.image !== undefined && feature.attributes.image != '') {
			image = "<div class=\"infowindow_image\">";
			image += "<a href=\""+feature.attributes.link+"\" title=\""+feature.attributes.name+"\">";
			image += "<img src=\""+feature.attributes.image+"\" />";
			image += "</a>";
			image += "</div>";
		}

		var content = "<div class=\"infowindow\">" + image +
		    "<div class=\"infowindow_content\">"+
		    "<div class=\"infowindow_list\">"+feature.attributes.name+"</div>\n" +
		    "<div class=\"infowindow_meta\">";

		/*if (typeof(feature.attributes.link) != 'undefined' &&
		    feature.attributes.link != '') {

		    content += "<a href='"+feature.attributes.link+"'>" +
			    "More Information</a><br/>";
		}*/

		if (feature.attributes.incident_ids) 
		{
			content += "<a href='#' class='next'>N &gt;</a><a href='#' class='prev'>&lt; P</a>";
		}

		/*content += "<a id=\"zoomIn\">";
		content += "Zoom In</a>";
		content += "&nbsp;&nbsp;|&nbsp;&nbsp;";
		content += "<a id=\"zoomOut\">";
		content += "Zoom Out</a></div>";*/
		content += "</div><div style=\"clear:both;\"></div></div>";		

		if (content.search("<script") != -1) {
			content = "Content contained Javascript! Escaped content " +
			    "below.<br />" + content.replace(/</g, "&lt;");
		}
		
		return content;
	}
})();