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

if (!defined('EQDKP_INC')){
	die('Do not access this file directly.');
}

/*+----------------------------------------------------------------------------
  | pdh_r_wow
  +--------------------------------------------------------------------------*/
if (!class_exists('pdh_r_wow')) {
	class pdh_r_wow extends pdh_r_generic {

		/**
		* Data array loaded by initialize
		*/
		private $data;
		private $guilddata;

		/**
		* Hook array
		*/
		public $hooks = array(
			'member_update',
		);

		/**
		* Presets array
		*/
		public $presets = array(
			'wow_charicon'			=> array('charicon', array('%member_id%'),			array()),
			'wow_achievementpoints'	=> array('achievementpoints',array('%member_id%'),	array()),
			'wow_gearlevel'			=> array('averageItemLevelEquipped',array('%member_id%'),	array()),
			'wow_profiler'			=> array('profilers', array('%member_id%'),			array()),
		);

		/**
		* Constructor
		*/
		public function __construct(){
		}
	
		public function reset(){
		}

		/**
		* init
		*
		* @returns boolean
		*/
		public function init(){
			$this->data = array();
			$this->game->new_object('bnet_armory', 'armory', array(unsanitize($this->config->get('uc_server_loc')), $this->config->get('uc_data_lang')));
			$this->guilddata = $this->game->obj['armory']->guild(unsanitize($this->config->get('guildtag')), unsanitize($this->config->get('servername')));
			$guildMembers = array();

			if (is_array($this->guilddata['members'])){
				foreach($this->guilddata['members'] as $member){
					 $this->data[sanitize($member['character']['name'])] = $member;
				}
			}
			return true;
		}

		public function get_achievementpoints($member_id){
			$membername = $this->pdh->get('member', 'name', array($member_id));
			if (isset($this->data[$membername])){
				return $this->data[$membername]['character']['achievementPoints'];
			}
			
			$char_server	= $this->pdh->get('member', 'profile_field', array($member_id, 'servername'));
			$servername		= ($char_server != '') ? $char_server : $this->config->get('servername');
			
			$charinfo = $this->game->obj['armory']->character(unsanitize($membername), unsanitize($servername));
			if (isset($charinfo['achievementPoints'])){
				return $charinfo['achievementPoints'];
			}
			
			return 0;
		}

		public function get_html_achievementpoints($member_id){
			return '<i class="adminicon"></i>&nbsp;'.$this->get_achievementpoints($member_id);
		}

		public function get_charicon($member_id){
			$membername = $this->pdh->get('member', 'name', array($member_id));
			if (isset($this->data[$membername])){
				return $this->game->obj['armory']->characterIcon($this->data[$membername]['character']);
			}
			$char_server	= $this->pdh->get('member', 'profile_field', array($member_id, 'servername'));
			$servername		= ($char_server != '') ? $char_server : $this->config->get('servername');
			
			$charinfo = $this->game->obj['armory']->character(unsanitize($membername), unsanitize($servername));
			if (isset($charinfo['thumbnail'])){
				return $this->game->obj['armory']->characterIcon($charinfo);
			}
			return '';
		}

		public function get_html_charicon($member_id){
			$charicon = $this->get_charicon($member_id);
			if ($charicon == '') {
				$charicon = $this->server_path.'images/global/avatar-default.svg';
			}
			return '<img src="'.$charicon.'" alt="Char-Icon" height="48" class="gameicon"/>';
		}

		public function get_averageItemLevelEquipped($member_id){
			$membername = $this->pdh->get('member', 'name', array($member_id));
			$char_server	= $this->pdh->get('member', 'profile_field', array($member_id, 'servername'));
			$servername		= ($char_server != '') ? $char_server : $this->config->get('servername');
			
			$charinfo = $this->game->obj['armory']->character(unsanitize($membername), unsanitize($servername));
			if (isset($charinfo['items']['averageItemLevelEquipped'])){
				return $charinfo['items']['averageItemLevelEquipped'];
			}
			
			return '';
		}

		public function get_profilers($member_id){
			$membername		= $this->pdh->get('member', 'name', array($member_id));
			$char_server	= $this->pdh->get('member', 'profile_field', array($member_id, 'servername'));
			$servername		= ($char_server != '') ? $char_server : $this->config->get('servername');
			
			$output			= '';
			$a_profilers	= array(
				1	=> array(
					'icon'	=> $this->server_path.'games/wow/profiles/profilers/askmrrobot.png',
					'name'	=> 'AskMrRobot.com',
					'url'	=> $this->game->obj['armory']->bnlink(unsanitize($membername), unsanitize($servername), 'askmrrobot')
				)
			);
			
			
			if(is_array($a_profilers)){
				foreach($a_profilers as $v_profiler){
					$output	.= '<a href="'.$v_profiler['url'].'"><img src="'.$v_profiler['icon'].'" alt="'.$v_profiler['name'].'" width="20" class="gameicon"/></a> '; 
				}
			}
			return $output;
			
		}

	} //end class
} //end if class not exists
?>