<?php
	$t_item = $this->getVar("item");
	$va_comments = $this->getVar("comments");
 	$va_object_browse_info = caGetInfoForBrowseType("objects");
 	$va_views = $va_object_browse_info["views"];
 	$o_config = caGetBrowseConfig();
 	$va_view_icons = $o_config->getAssoc("views")
?>
<div class="row">
	<div class='col-xs-12 navTop'><!--- only shown at small screen size -->
		{{{previousLink}}}{{{resultsLink}}}{{{nextLink}}}
	</div><!-- end detailTop -->
	<div class='navLeftRight col-xs-1 col-sm-1 col-md-1 col-lg-1'>
		<div class="detailNavBgLeft">
			{{{previousLink}}}{{{resultsLink}}}
		</div><!-- end detailNavBgLeft -->
	</div><!-- end col -->
	<div class='col-xs-12 col-sm-10 col-md-10 col-lg-10'>
		<div class="container">
			<div class="row">
				<div class='col-md-12 col-lg-12'>
					<H4>{{{^ca_collections.preferred_labels.displayname}}}</H4>
					<H6>{{{^ca_collections.type_id}}}{{{<ifdef code="ca_collections.idno">, ^ca_collections.idno</ifdef>}}}</H6>
				</div><!-- end col -->
			</div><!-- end row --> 
			<div class="row">			
				<div class='col-md-12 col-lg-12'>
<?php	
	if ($va_abstract = $t_item->get('ca_collections.abstract')) {	
		print "<h6>Abstract</h6>";
		print"<p>".$va_abstract."</p>";
	}
	print "<div style='width:100%;clear:both;height:1px;'></div>";

	if ($t_item->get('ca_collections.extent.extent_amount') != "") {	
		if ($va_extent = $t_item->get('ca_collections.extent', array('template' => '^extent_amount'))) {	
			print "<h6>Extent</h6>";
			print "<p>".$va_extent."</p>";
		}
	}
	print "<div style='width:100%;clear:both;height:1px;'></div>";
	
		if ($va_content = $t_item->get('ca_collections.hierarchy.preferred_labels', array('returnAsArray' => true, 'removeFirstItems' => 0))) {	
		$content_id = $t_item->get('ca_collections.hierarchy.collection_id', array('returnAsArray' => true, 'removeFirstItems' => 0));
		$va_content_array = array_combine($content_id, $va_content);
		print "<h6>Hierarchy</h6><ul>";
		foreach ($va_content_array as $id => $content) {
			print "<li>".caNavLink($this->request, $content, '', '', 'Detail', 'collections/'.$id)."</li>";
		}
	print "</ul><div style='width:100%;clear:both;height:1px;'></div>";
	}

	if (is_array($va_collection_children_ids = $t_item->get('ca_collections.children.collection_id', array('returnAsArray' => true))) && sizeof($va_collection_children_ids)) {
		print "<h6>Content</h6>";
		$qr_children = caMakeSearchResult('ca_collections', $va_collection_children_ids);
		while($qr_children->nextHit()) {
			$vn_collection_id = $qr_children->get('collection_id');
			print "<div>".caNavLink($this->request, $qr_children->get('ca_collections.preferred_labels'), '', '', 'Detail', 'collections/'.$vn_collection_id)."</div>";
			if (in_array($t_item->get('ca_collections.type_id'), caMakeTypeIDList('ca_collections', array('series, subseries', 'collection')))) { 
				if ($va_object_levels = $qr_children->get('ca_objects.object_id', array('returnAsArray' => true))) {
					$qr_objects = caMakeSearchResult('ca_objects', $va_object_levels);
					while($qr_objects->nextHit()) {
						$vn_object_id = $qr_objects->get('ca_objects.object_id');
						//$t_object = new ca_objects($va_object_level);
						print "<div style='padding-left:20px;'>Box: ".$qr_objects->get('ca_objects.location.box')." Folder: ".$qr_objects->get('ca_objects.location.folder')." ".caNavLink($this->request, $qr_objects->get('ca_objects.preferred_labels.name'), '', '', 'Detail', 'objects/'.$vn_object_id)."</div>";
					}
				}
			}
			// if ($va_second_children = $t_collection->get('ca_collections.children.collection_id', array('returnAsArray' => true))) {
// 				foreach ($va_second_children as $vn_second_content => $va_second_child) {
// 					$t_second_collection = new ca_collections($va_second_child);
// 					print "<div style='padding-left:20px;'>".caNavLink($this->request, $t_second_collection->get('ca_collections.preferred_labels'), '', '', 'Detail', 'collections/'.$va_second_child)."</div>";
// 					if ($va_third_children = $t_second_collection->get('ca_collections.children.collection_id', array('returnAsArray' => true))) {
// 						foreach ($va_third_children as $vn_third_content => $va_third_child) {
// 							$t_third_collection = new ca_collections($va_third_child);
// 							print "<div style='padding-left:40px;'>".caNavLink($this->request, $t_third_collection->get('ca_collections.preferred_labels'), '', '', 'Detail', 'collections/'.$va_third_child)."</div>";
// 						}
// 					}
// 				}
// 			}
		}
	}
	
#	if ($va_content = $t_item->get('ca_collections.children.preferred_labels', array('returnAsArray' => true))) {	
#		$content_id = $t_item->get('ca_collections.children.collection_id', array('returnAsArray' => true));
#		$va_content_array = array_combine($content_id, $va_content);
#		natsort($va_content_array);
#		print "<h6>Content</h6><ul>";
#		foreach ($va_content_array as $id => $content) {
#			print "<li>".caNavLink($this->request, $content, '', '', 'Detail', 'collections/'.$id)."</li>";
#		}
#	print "</ul><div style='width:100%;clear:both;height:1px;'></div>";
#	}
	

	
	# --- entities
	$va_entities = $t_item->get("ca_entities", array("returnAsArray" => 1, 'checkAccess' => $va_access_values));
	$va_occurrences = $t_item->get("ca_occurrences", array("returnAsArray" => 1, 'checkAccess' => $va_access_values));
	$va_collections = $t_item->get("ca_collections", array("returnAsArray" => 1, 'checkAccess' => $va_access_values, 'sort' => 'ca_collections.idno'));

	if ((sizeof($va_entities) > 0) | (sizeof($va_occurrences) > 0) ) {
?>
		<h6><?php print _t("Related"); ?></h6>
		<ul>
<?php
		if (sizeof($va_entities)) {
			foreach($va_entities as $va_entity) {
				print "<li>".caNavLink($this->request, $va_entity["label"], '', '', 'Detail', 'entities/'.$va_entity["entity_id"])."</li>\n";
			}
		}
		if (sizeof($va_occurrences)) {
			foreach($va_occurrences as $va_occurrence) {
				print "<li>".caNavLink($this->request, $va_occurrence["label"], '', '', 'Detail', 'occurrences/'.$va_occurrence["occurrence_id"])."</li>\n";
			}
		}
?>
		</ul>
<?php				
	}
?>									
				</div><!-- end col -->
			</div><!-- end row -->
		<?php
		$t_item->dump();
		print_R(sizeof($t_item->getRelatedItems('ca_objects')));
		?>
{{{<ifcount code="ca_objects" min="2">
			<div class="row">
				<div id="bViewButtons">
<?php
					if(is_array($va_views) && (sizeof($va_views) > 1)){
						foreach($va_views as $vs_view => $va_view_info) {
?>
							<a href="#" onClick="loadResults('<?php print $vs_view; ?>'); return false;"><span class="glyphicon <?php print $va_view_icons[$vs_view]['icon']; ?>"></span></a>
<?php
						}
					}
?>
				</div>
				<div id="browseResultsContainer">
					<?php print caBusyIndicatorIcon($this->request).' '.addslashes(_t('Loading...')); ?>
				</div><!-- end browseResultsContainer -->
			</div><!-- end row -->
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery("#browseResultsContainer").load("<?php print caNavUrl($this->request, '', 'Search', 'objects', array('search' => 'collection_id:^ca_collections.collection_id'), array('dontURLEncodeParameters' => true)); ?>", function() {
						jQuery('#browseResultsContainer').jscroll({
							autoTrigger: true,
							loadingHtml: '<?php print caBusyIndicatorIcon($this->request).' '.addslashes(_t('Loading...')); ?>',
							padding: 20,
							nextSelector: 'a.jscroll-next'
						});
					});
					
					
				});
			</script>
</ifcount>}}}		

</div><!-- end container -->

<script type="text/javascript"> 
		
		function loadResults(view) {
			jQuery("#browseResultsContainer").data('jscroll', null);
			jQuery("#browseResultsContainer").load("<?php print caNavUrl($this->request, '', 'Search', 'objects', array('search' => 'collection_id:{{{^ca_collections.collection_id}}}'), array('dontURLEncodeParameters' => true)); ?>/view/" + view, function() {
				jQuery("#browseResultsContainer").jscroll({ 
					autoTrigger: true,
					loadingHtml: "<?php print caBusyIndicatorIcon($this->request).' '.addslashes(_t('Loading...')); ?>",
					padding: 20,
					nextSelector: "a.jscroll-next"
				});
			});
		}
</script>