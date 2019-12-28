<?php
/** ---------------------------------------------------------------------
 * themes/newvamuse/Transcribe/collection_html.php :
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2019 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * @package CollectiveAccess
 * @subpackage theme/default
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License version 3
 *
 * ----------------------------------------------------------------------
 */
 	$t_set = $this->getVar('set');
 	$items = $this->getVar('items');	
 	$set_id = $t_set->getPrimaryKey();
 	
	$previous_id = $this->getVar('previousID');
	$next_id = $this->getVar('nextID');
?>
<div class="container textContent">
	<div class="row">
		<div class="col-sm-1">
			<div class="setsBack">
				<?php print $previous_id ? caNavLink($this->request, '<i class="fa fa-angle-left" aria-label="back"></i><div class="small">Previous</div>', '', '*', 'Transcribe', 'Collection/'.$previous_id) : ''; ?>
				<?php print caNavLink($this->request, '<i class="fa fa-angle-double-left" aria-label="back"></i><div class="small">Back</div>', '', '*', 'Transcribe', 'Collections'); ?>
			</div>
		</div>
		<div class="col-sm-10">
			
			<h1><a href="/Transcribe/Index">Transcribe</a> &gt; <?php print $t_set->get('ca_sets.preferred_labels.name'); ?></H1>
			<p style='padding-bottom:15px;'>
				<?php print $t_set->get('ca_sets.set_description'); ?>
			</p>
			<div style="clear:both; margin-top:10px;">

			</div>
			<div class="row">
<?php
	$transcription_status = ca_objects::getTranscriptionStatusForIDs(array_map(function($v) { return $v['row_id']; }, $items));
	foreach($items as $item) {
		print "<div class='col-xs-6 col-sm-4 col-md-3 col-lg-3 lbItem{$item['object_id']}'><div class='lbItemContainer'>";
		print caNavLink($this->request, $item['representation_tag_small']."<br/>".$item['name'], '', '*', 'Transcribe', 'Item', ['id' => $item['object_id']]);
		if (isset($transcription_status['items'][$item['object_id']]['status'])) {
			switch($transcription_status['items'][$item['object_id']]['status']) {
				case __CA_TRANSCRIPTION_STATUS_NOT_STARTED__:
				default:
					print "Not started";
					break;
				case __CA_TRANSCRIPTION_STATUS_IN_PROGRESS__:
					print "In progress";
					break;
				case __CA_TRANSCRIPTION_STATUS_COMPLETED__:
					print "Completed";
					break;
			}
		}
		print "</div></div>\n";
	}
?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="setsBack">
				<?php print $next_id ? caNavLink($this->request, '<i class="fa fa-angle-right" aria-label="back"></i><div class="small">Next</div>', '', '*', 'Transcribe', 'Collection/'.$next_id) : ''; ?>
			</div>
		</div>	
	</div>
</div>