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
 
define('EQDKP_INC', true);
$eqdkp_root_path = './../../../';
include_once ($eqdkp_root_path . 'common.php');

class charImporter extends page_generic {
	public function __construct() {
		$handler = array(
			'massupdate'		=> array('process' => 'perform_massupdate'),
			'resetcache'		=> array('process' => 'perform_resetcache'),
			'ajax_massupdate'	=> array('process' => 'ajax_massupdate'),
			'ajax_mudate'		=> array('process' => 'ajax_massupdatedate'),
		);
		parent::__construct(false, $handler, array());
		$this->user->check_auth('u_member_man');
		$this->user->check_auth('u_member_add');
		$this->game->new_object('bnet_armory', 'armory', array($this->config->get('uc_server_loc'), $this->config->get('uc_data_lang')));
		$this->process();
	}

	public function perform_resetcache(){
		// delete the cache folder
		$this->game->obj['armory']->DeleteCache();

		// Output the success message
		$hmtlout = '<div id="guildimport_dataset">
						<div id="controlbox">
							<fieldset class="settings">
								<dl>
									'.$this->game->glang('uc_importcache_cleared').'
								</dl>
							</fieldset>
						</div>
					</div>';

		$this->tpl->assign_vars(array(
			'DATA'		=> $hmtlout,
			'STEP'		=> ''
		));

		$this->core->set_vars(array(
			'page_title'		=> $this->user->lang('raidevent_raid_guests'),
			'header_format'		=> 'simple',
			'template_file'		=> 'importer.html',
			'display'			=> true
		));
	}

	public function perform_massupdate(){
		// check permission again, cause this is for admins only
		$this->user->check_auth('a_members_man');

		// quit if there is not a server….
		if($this->config->get('servername') == ''){
			return '<fieldset class="settings mediumsettings">
							<dl>
								<dt><label>'.$this->game->glang('uc_error_head').'</label></dt>
								<dd>'.$this->game->glang('uc_error_noserver').'</dd>
							</dl>
						</fieldset>';
		}

		$memberArry	= array();
		$arrMemberIDs = $this->pdh->get('member', 'id_list', array());
		$arrMemberIDs = $this->pdh->sort($arrMemberIDs, 'member', 'name', 'asc');
		foreach($arrMemberIDs as $memberID){
			$strMemberName = $this->pdh->get('member', 'name', array($memberID));
			if (strlen($strMemberName)){
				$memberArry[] = array(
						'charname'	=> $strMemberName,
						'charid'	=> $memberID,
				);
			}
		}

		$hmtlout = '<div id="guildimport_dataset">
						<div id="controlbox">
							<fieldset class="settings">
								<dl>
									'.$this->game->glang('uc_massupd_loading').'
									<div id="progressbar"></div>
								</dl>
							</fieldset>
						</div>
						<fieldset class="settings data">
						</fieldset>
					</div>';

		$this->tpl->add_js('$( "#progressbar" ).progressbar({ value: 0 }); getData();', 'docready');
		$this->tpl->add_js('
			var chardataArry = $.parseJSON(\''.json_encode($memberArry).'\');
			function getData(i){
				if (!i)
					i=0;
	
				if (chardataArry.length >= i){
					setTimeout(function(){
						$.post("charimporter.php'.$this->SID.'&ajax_massupdate=true&totalcount="+chardataArry.length+"&actcount="+i, chardataArry[i], function(data){
							chardata = $.parseJSON(data);
							if(chardata.success == "imported"){
								successdata = "<span style=\"color:green;\">'.$this->game->glang('uc_armory_updated').'</span>";
							}else{
								successdata = "<span style=\"color:red;\">'.$this->game->glang('uc_armory_updfailed').'<br/>"+
								((chardata.error) ? "'.$this->game->glang('uc_armory_impfail_reason').' "+chardata.error : "")+"</span>";
							}
							$("#guildimport_dataset fieldset.data").prepend("<dl><dt><label><img src=\""+ chardata.image +"\" alt=\"charicon\" height=\"84\" width=\"84\" /></label></dt><dd>"+ chardata.name+"<br/>"+ successdata +"</dd></dl>").children(":first").hide().fadeIn("slow");
							$("#progressbar").progressbar({ value: ((i/chardataArry.length)*100) })
							if(chardataArry.length > i+1){
								getData(i+1);
							}else{
								$.post("charimporter.php'.$this->SID.'&ajax_mudate=true");
								$("#controlbox").html("<dl><div class=\"infobox infobox-large infobox-green clearfix\"><i class=\"fa fa-check fa-4x pull-left\"></i> '.$this->game->glang('uc_cupdt_header_fnsh').'</div></dl>").fadeIn("slow");
								return;
							}
						});
					}, 80);
				}
			}');

		$this->tpl->assign_vars(array(
			'DATA'		=> $hmtlout,
			'STEP'		=> ''
		));

		$this->core->set_vars(array(
			'page_title'		=> $this->user->lang('raidevent_raid_guests'),
			'header_format'		=> 'simple',
			'template_file'		=> 'importer.html',
			'display'			=> true
		));
	}

	public function ajax_massupdatedate(){
		$this->config->set(array('uc_profileimported'=> $this->time->time));
	}

	public function ajax_massupdate(){
		// due to connected/virtual realms, check for a servername of the char
		$char_server	= $this->pdh->get('member', 'profile_field', array($this->in->get('charid', 0), 'servername'));
		$servername		= ($char_server != '') ? $char_server : $this->config->get('servername');
		$chardata		= $this->game->obj['armory']->character(unsanitize($this->in->get('charname', '')), unsanitize($servername), true);

		if($chardata && !isset($chardata['status']) && !empty($chardata['name']) && $chardata['name'] != 'none'){
			$errormsg	= '';
			$charname	= $chardata['name'];
			$charicon	= $this->game->obj['armory']->characterIcon($chardata);

			// insert into database
			$info		= $this->pdh->put('member', 'addorupdate_member', array($this->in->get('charid', 0), array(
				'name'				=> $this->in->get('charname', ''),
				'level'				=> $chardata['level'],
				'gender'			=> $this->game->obj['armory']->ConvertID($chardata['gender'], 'int', 'gender'),
				'race'				=> $this->game->obj['armory']->ConvertID($chardata['race'], 'int', 'races'),
				'class'				=> $this->game->obj['armory']->ConvertID($chardata['class'], 'int', 'classes'),
				'guild'				=> $chardata['guild']['name'],
				'last_update'		=> ($chardata['lastModified']/1000),
				'prof1_name'		=> $this->game->get_id('professions', $chardata['professions']['primary'][0]['name']),
				'prof1_value'		=> $chardata['professions']['primary'][0]['rank'],
				'prof2_name'		=> $this->game->get_id('professions', $chardata['professions']['primary'][1]['name']),
				'prof2_value'		=> $chardata['professions']['primary'][1]['rank'],
				'talent1'			=> $this->game->obj['armory']->ConvertTalent($chardata['talents'][0]['spec']['icon']),
				'talent2'			=> $this->game->obj['armory']->ConvertTalent($chardata['talents'][1]['spec']['icon']),
				'health_bar'		=> $chardata['stats']['health'],
				'second_bar'		=> $chardata['stats']['power'],
				'second_name'		=> $chardata['stats']['powerType'],
			), $this->in->get('overtakeuser')));

			$this->pdh->process_hook_queue();
			$successmsg	= ($info) ? 'imported' : 'error';
		}else{
			$successmsg	= 'error';
			$errormsg	= (isset($chardata['reason']) && !empty($chardata['reason'])) ? $chardata['reason'] : $this->game->glang('uc_error_nodata_bnet');
			$charname	= $this->in->get('charname', '');
			$charicon	= $this->server_path.'images/global/avatar-default.svg';
		}

		die(json_encode(array(
			'image'		=> $charicon,
			'name'		=> $charname,
			'success'	=> $successmsg,
			'error'		=> $errormsg
		)));
	}

	public function perform_step0(){

		$tmpmemname = '';
		if($this->in->get('member_id', 0) > 0){
			$tmpmemname = $this->pdh->get('member', 'name', array($this->in->get('member_id', 0)));
		}

		// generate output
		$hmtlout = '<fieldset class="settings mediumsettings">
			<dl>
				<dt><label>'.$this->game->glang('uc_charname').'</label></dt>
				<dd>'.new htext('charname', array('value' => (($tmpmemname) ? $tmpmemname : ''), 'size' => '25')).'</dd>
			</dl>';
		
		// Server Name
		$hmtlout .= '<dl>
				<dt><label>'.$this->game->glang('servername').'</label></dt>
				<dd>';
		$hmtlout .= new htext('servername', array('value' => (($this->config->get('servername')) ? stripslashes($this->config->get('servername')) : ''), 'size' => '25', 'autocomplete' => $this->game->get('realmlist')));
		
		$hmtlout .= '</dd>
			</dl>
			<dl>
				<dt><label>'.$this->game->glang('uc_server_loc').'</label></dt>
				<dd>';
		if($this->config->get('uc_server_loc')){
			$hmtlout .= $this->config->get('uc_server_loc');
			
			$hmtlout .= new hhidden('server_loc', array('value' => $this->config->get('uc_server_loc')));
		}else{
			$hmtlout .= new hdropdown('server_loc', array('options' => $this->game->obj['armory']->getServerLoc()));
		}
		$hmtlout .= '</dd>
			</dl>';
		
		$hmtlout .= '</fieldset>';
		$hmtlout .= '<br/><button type="submit" name="submiti"><i class="fa fa-download"></i> '.$this->game->glang('uc_import_forw').'</button>';
		return $hmtlout;
	}

	public function perform_step1(){
		$hmtlout = '';
		if($this->in->get('member_id', 0) > 0){
			// We'll update an existing one...
			$isindatabase	= $this->in->get('member_id', 0);
			$isMemberName	= $this->pdh->get('member', 'name', array($isindatabase));
			$isServerName	= $this->config->get('servername');
			$isServerLoc	= $this->config->get('uc_server_loc');
			$is_mine		= ($this->pdh->get('member', 'userid', array($isindatabase)) == $this->user->data['user_id']) ? true : false;
		}else{
			// Check for existing member name
			$isindatabase	= $this->pdh->get('member', 'id', array($this->in->get('charname'), array('servername' => $this->in->get('servername'))));
			$hasuserid		= ($isindatabase > 0) ? $this->pdh->get('member', 'userid', array($isindatabase)) : 0;
			$isMemberName	= $this->in->get('charname');
			$isServerName	= $this->in->get('servername');
			$isServerLoc	= $this->in->get('server_loc');
			if($this->user->check_auth('a_charmanager_config', false)){
				$is_mine	= true;			// We are an administrator, its always mine..
			}else{
				$is_mine	= (($hasuserid > 0) ? (($hasuserid == $this->user->data['user_id']) ? true : false) : true);	// we are a normal user
			}
		}
		
		if($is_mine){
			// Load the Armory Data
			$this->game->obj['armory']->setSettings(array('loc'=>$isServerLoc));
			$chardata	= $this->game->obj['armory']->character(unsanitize($isMemberName), unsanitize($isServerName), true);
			//new hhidden('server_loc', array('value' => $this->config->get('uc_server_loc')))
			// Basics
			$hmtlout	.= new hhidden('member_id', array('value' => $isindatabase));
			$hmtlout	.= new hhidden('member_name', array('value' => $isMemberName));
			$hmtlout	.= new hhidden('member_level', array('value' => $chardata['level']));
			$hmtlout	.= new hhidden('gender', array('value' => $this->game->obj['armory']->ConvertID($chardata['gender'], 'int', 'gender')));
			$hmtlout	.= new hhidden('member_race_id', array('value' => $this->game->obj['armory']->ConvertID($chardata['race'], 'int', 'races')));
			$hmtlout	.= new hhidden('member_class_id', array('value' => $this->game->obj['armory']->ConvertID($chardata['class'], 'int', 'classes')));
			$hmtlout	.= new hhidden('guild', array('value' => $chardata['guild']['name']));
			$hmtlout	.= new hhidden('servername', array('value' => $chardata['realm']));
			$hmtlout	.= new hhidden('last_update', array('value' => ($chardata['lastModified']/1000)));

			// primary professions
			$hmtlout	.= new hhidden('prof1_name', array('value' => $chardata['professions']['primary'][0]['name']));
			$hmtlout	.= new hhidden('prof1_value', array('value' => $chardata['professions']['primary'][0]['rank']));
			$hmtlout	.= new hhidden('prof2_name', array('value' => $chardata['professions']['primary'][1]['name']));
			$hmtlout	.= new hhidden('prof2_value', array('value' => $chardata['professions']['primary'][1]['rank']));

			// talents
			$hmtlout	.= new hhidden('talent1', array('value' => $this->game->obj['armory']->ConvertTalent($chardata['talents'][0]['spec']['icon'])));
			$hmtlout	.= new hhidden('talent2', array('value' => $this->game->obj['armory']->ConvertTalent($chardata['talents'][1]['spec']['icon'])));
			

			// health/power bar
			$hmtlout	.= new hhidden('health_bar', array('value' => $chardata['stats']['health']));
			$hmtlout	.= new hhidden('second_bar', array('value' => $chardata['stats']['power']));
			$hmtlout	.= new hhidden('second_name', array('value' => $chardata['stats']['powerType']));

			// viewable Output
			if(!isset($chardata['status'])){
				$hmtlout	.= '
				<div class="infobox infobox-large infobox-red clearfix">
					<i class="fa fa-exclamation-triangle fa-4x pull-left"></i> '.(($isindatabase) ? $this->game->glang('uc_charfound4') : $this->game->glang('uc_charfound3')).'
				</div>

				<fieldset class="settings mediumsettings">
					<dl>
						<dt><label><img src="'.$this->game->obj['armory']->characterIcon($chardata).'" name="char_icon" alt="icon" width="44px" height="44px" align="middle" /></label></dt>
						<dd>
							'.sprintf($this->game->glang('uc_charfound'), $isMemberName).'<br />
							'.sprintf($this->game->glang('uc_charfound2'), $this->time->user_date(($chardata['lastModified']/1000))).'
						</dd>
					</dl>
					<dl>';
				if(!$isindatabase){
					if($this->user->check_auth('u_member_conn', false)){
						$hmtlout	.= '<dt>'.$this->user->lang('overtake_char').'</dt><dd>'.new hradio('overtakeuser', array('value' => 1)).'</dd>';
					}else{
						$hmtlout	.= '<dt>'.$this->user->lang('overtake_char').'</dt><dd>'.new hradio('overtakeuser', array('value' => 1, 'disabled' => true)).'</dd>';
						$hmtlout	.= new hhidden('overtakeuser', array('value' => '1'));
					}
				}
				$hmtlout	.= '
					</dl>
					</fieldset>';
				$hmtlout		.= '<center>
										<button type="submit" name="submiti"><i class="fa fa-refresh"></i> '.(($isindatabase) ? $this->game->glang('uc_prof_update') : $this->game->glang('uc_prof_import')).'</button>
									</center>';
			}else{
				$hmtlout		.= '<div class="infobox infobox-large infobox-red clearfix">
										<i class="fa fa-exclamation-triangle fa-4x pull-left"></i> <b>WARNING: </b> '.$chardata['reason'].'
									</div>';
			}
		}else{
			$hmtlout	.= '<div class="infobox infobox-large infobox-red clearfix">
								<i class="fa fa-exclamation-triangle fa-4x pull-left"></i> '.$this->game->glang('uc_notyourchar').'
							</div>';
		}
		return $hmtlout;
	}

	public function perform_step2(){
		$data = array(
			'name'				=> $this->in->get('member_name'),
			'level'				=> $this->in->get('member_level', 0),
			'gender'			=> $this->in->get('gender', 'male'),
			'race'				=> $this->in->get('member_race_id', 0),
			'class'				=> $this->in->get('member_class_id', 0),
			'guild'				=> $this->in->get('guild',''),
			'last_update'		=> $this->in->get('last_update', 0),
			'prof1_name'		=> $this->game->get_id('professions', $this->in->get('prof1_name', '')),
			'prof1_value'		=> $this->in->get('prof1_value', 0),
			'prof2_name'		=> $this->game->get_id('professions', $this->in->get('prof2_name', '')),
			'prof2_value'		=> $this->in->get('prof2_value', 0),
			'talent1'			=> $this->in->get('talent1', 0),
			'talent2'			=> $this->in->get('talent2', 0),
			'health_bar'		=> $this->in->get('health_bar', 0),
			'second_bar'		=> $this->in->get('second_bar', 0),
			'second_name'		=> $this->in->get('second_name', ''),
			'servername'		=> $this->in->get('servername', ''),
		);

		$info		= $this->pdh->put('member', 'addorupdate_member', array($this->in->get('member_id', 0), $data, $this->in->get('overtakeuser', 0)));
		$this->pdh->process_hook_queue();
		if($info){
			$hmtlout	= '<div class="infobox infobox-large infobox-green clearfix">
								<i class="fa fa-check fa-4x pull-left"></i> '.$this->game->glang('uc_armory_updated').'
							</div>';
		}else{
			$hmtlout	= '<div class="infobox infobox-large infobox-red clearfix">
								<i class="fa fa-exclamation-triangle fa-4x pull-left"></i> '.$this->game->glang('uc_armory_updfailed').'
							</div>';
		}
		return $hmtlout;
	}

	public function display(){

		// quit if there is not a server….
		if($this->config->get('servername') == ''){
			$this->tpl->assign_vars(array(
				'DATA'		=> '<fieldset class="settings mediumsettings">
							<dl>
								<dt><label>'.$this->game->glang('uc_error_head').'</label></dt>
								<dd>'.$this->game->glang('uc_error_noserver').'</dd>
							</dl>
						</fieldset>'
			));
		}else{
			$stepnumber		= ($this->config->get('servername') && $this->config->get('uc_server_loc') && $this->in->get('member_id',0) > 0 && $this->in->get('step',0) == 0) ? 1 : $this->in->get('step',0);
			$urladdition	 = ($this->in->get('member_id',0)) ? '&amp;member_id='.$this->in->get('member_id',0) : '';
			$funcname		 = 'perform_step'.$stepnumber;
			$this->tpl->assign_vars(array(
				'DATA'		=> $this->$funcname(),
				'STEP'		=> ($stepnumber+1).$urladdition
			));
		}
		$this->core->set_vars(array(
			'page_title'		=> $this->user->lang('raidevent_raid_guests'),
			'header_format'		=> 'simple',
			'template_file'		=> 'importer.html',
			'display'			=> true
		));
	}
}
registry::register('charImporter');
?>