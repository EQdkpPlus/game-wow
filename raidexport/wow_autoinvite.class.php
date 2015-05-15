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

$rpexport_plugin['wow_autoinvite.class.php'] = array(
	'name'			=> 'WoW Auto Invite',
	'function'		=> 'wowautoinviteexport',
	'contact'		=> 'webmaster@wallenium.de',
	'version'		=> '2.0.0');

if(!function_exists('wowautoinviteexport')){
	function autoinvite_eclass($id){
		switch($id){
			case '1': $eclass = 'DEATHKNIGHT';break;
			case '2': $eclass = 'DRUID';break;
			case '3': $eclass = 'HUNTER';break;
			case '4': $eclass = 'MAGE';break;
			case '5': $eclass = 'PALADIN';break;
			case '6': $eclass = 'PRIEST';break;
			case '7': $eclass = 'ROGUE';break;
			case '8': $eclass = 'SHAMAN';break;
			case '9': $eclass = 'WARLOCK';break;
			case '10': $eclass = 'WARRIOR';break;
			case '11': $eclass = 'MONK';break;
			default: $eclass = '0';
		}
		return $eclass;
	}
	
	function wowautoinviteexport($raid_id, $raid_groups=0){
		$attendees	= registry::register('plus_datahandler')->get('calendar_raids_attendees', 'attendees', array($raid_id));
		$guests		= registry::register('plus_datahandler')->get('calendar_raids_guests', 'members', array($raid_id));

		$a_json	= array();
		foreach($attendees as $id_attendees=>$d_attendees){
			$a_json[]	= array(
				'name'		=> unsanitize(registry::register('plus_datahandler')->get('member', 'name', array($id_attendees))),
				'status'	=> $d_attendees['signup_status'],
				'class'		=> autoinvite_eclass(registry::register('plus_datahandler')->get('member', 'classid', array($id_attendees))),
				'note'		=> unsanitize($d_attendees['note']),
				'level'		=> registry::register('plus_datahandler')->get('member', 'level', array($id_attendees)),
				'guest'		=> false,
				'group'		=> $d_attendees['raidgroup']
			);
		}
		foreach($guests as $guestsdata){
			$a_json[]	= array(
				'name'		=> unsanitize($guestsdata['name']),
				'status'	=> false,
				'class'		=> autoinvite_eclass($guestsdata['class']),
				'note'		=> unsanitize($guestsdata['note']),
				'level'		=> 0,
				'guest'		=> true,
				'group'		=> $guestsdata['raidgroup']
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

		#http://www.curse.com/addons/wow/auto-invite#t1:description
		#name:eClass:level:inGroup:group:comment
		#name: name of the player
		#eClass: must bes PRIEST, HUNTER, WARRIOR, MAGE, PALADIN, SHAMAN, WARLOCK, ROGUE or DRUID
		#level: can be 0, the mod will read the level then automatically
		#inGroup=1 player is in the current group setup and will be invited by the mod, inGroup=0 player is not in the current group setup (only in the complete list)
		#group='-' no group defined group=1 or group=2 to group=8 number of the group
		#comment=Comment for the player. Use <br> to split it in several lines

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
						output += item.name + ":" + item.class + ":" + item.level + ":1:-:" +item.note + "\n";
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