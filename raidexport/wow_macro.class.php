<?php
/*	Project:	EQdkp-Plus
 *	Package:	World of Warcraft game package
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2015 EQdkp-Plus Developer Team
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Affero General Public License as published
 *	by the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Affero General Public License for more details.
 *
 *	You should have received a copy of the GNU Affero General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if ( !defined('EQDKP_INC') ){
	header('HTTP/1.0 404 Not Found');exit;
}

$rpexport_plugin['wow_macro.class.php'] = array(
	'name'			=> 'WoW Macro',
	'function'		=> 'WoWMacroexport',
	'contact'		=> 'webmaster@wallenium.de',
	'version'		=> '2.0.0');

if(!function_exists('WoWMacroexport')){
	function WoWMacroexport($raid_id, $raid_groups=0){
		$attendees	= registry::register('plus_datahandler')->get('calendar_raids_attendees', 'attendees', array($raid_id));
		$guests		= registry::register('plus_datahandler')->get('calendar_raids_guests', 'members', array($raid_id));

		$a_json	= array();
		foreach($attendees as $id_attendees=>$d_attendees){
			$char_server	= registry::register('plus_datahandler')->get('member', 'profile_field', array($id_attendees, 'servername'));
			$servername		= ($char_server != registry::register('config')->get('servername')) ? $char_server : false;
			$a_json[]	= array(
				'name'		=> unsanitize(registry::register('plus_datahandler')->get('member', 'name', array($id_attendees))),
				'status'	=> $d_attendees['signup_status'],
				'guest'		=> false,
				'group'		=> $d_attendees['raidgroup'],
				'realm'		=> $servername
			);
		}
		foreach($guests as $guestsdata){
			$a_json[]	= array(
				'name'		=> unsanitize($guestsdata['name']),
				'status'	=> false,
				'guest'		=> true,
				'group'		=> $guestsdata['raidgroup'],
				'realm'		=> false
			);
		}
		$json = json_encode($a_json);
		unset($a_json);

		registry::register('template')->add_js('
			genOutput()
			$("input[type=\'checkbox\'], #raidgroup").change(function (){
				genOutput()
			});
		', "docready");

		registry::register('template')->add_js('
		function genOutput(){
			var attendee_data = '.$json.';
			output = "";

			cb_guests		= ($("#cb_guests").attr("checked")) ? true : false;
			cb_confirmed	= ($("#cb_confirmed").attr("checked")) ? true : false;
			cb_signedin		= ($("#cb_signedin").attr("checked")) ? true : false;
			cb_backup		= ($("#cb_backup").attr("checked")) ? true : false;

			$.each(attendee_data, function(i, item) {
				if((cb_guests && item.guest == true) || (cb_confirmed && !item.guest && item.status == 0) || (cb_signedin && item.status == 1) || (cb_backup && item.status == 3)){
					if($("#raidgroup").length == 0 || $("#raidgroup").val() == "0" || (item.group > 0 && item.group == $("#raidgroup").val())){
						realmdata	 = (item.realm) ? "-" + item.realm : "";
						output		+= "/inv " + item.name + realmdata + "\n";
					}
				}
			});
			$("#attendeeout").html(output);
		}
			');
			
			if(is_array($raid_groups)){
				$text  = "<dt><label>".registry::fetch('user')->lang('raidevent_export_raidgroup')."</label></dt>
							<dd>
								".new hdropdown('raidgroup', array('options' => $raid_groups, 'value' => 0, 'id' => 'raidgroup'))."
							</dd>
						</dl><dl>";
			}

		$text .= "<input type='checkbox' checked='checked' name='confirmed' id='cb_confirmed' value='true'> ".registry::fetch('user')->lang(array('raidevent_raid_status', 0));
		$text .= "<input type='checkbox' checked='checked' name='guests' id='cb_guests' value='true'> ".registry::fetch('user')->lang('raidevent_raid_guests');
		$text .= "<input type='checkbox' checked='checked' name='signedin' id='cb_signedin' value='true'> ".registry::fetch('user')->lang(array('raidevent_raid_status', 1));
		$text .= "<input type='checkbox' name='backup' id='cb_backup' value='true'> ".registry::fetch('user')->lang(array('raidevent_raid_status', 3));
		$text .= "<br/>";
		$text .= "<textarea name='group".rand()."' id='attendeeout' cols='60' rows='10' onfocus='this.select()' readonly='readonly'>";
		$text .= "</textarea>";

		$text .= '<br/>'.registry::fetch('user')->lang('rp_copypaste_ig')."</b>";
		return $text;
	}
}
?>