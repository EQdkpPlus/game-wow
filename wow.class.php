<?php
/*	Project:	EQdkp-Plus
 *	Package:	World of Warcraft game package
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2016 EQdkp-Plus Developer Team
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

if(!class_exists('wow')) {
	class wow extends game_generic {

		protected static $apiLevel	= 20;
		public $version				= '1.0.1';
		protected $this_game		= 'wow';
		protected $types			= array('factions', 'races', 'classes', 'talents', 'filters', 'roles', 'classrole', 'professions', 'chartooltip');	// which information are stored?
		protected $classes			= array();
		protected $roles			= array();
		protected $races			= array();															// for each type there must be the according var
		protected $factions			= array();															// and the according function: load_$type
		protected $filters			= array();
		protected $professions		= array();
		public $langs				= array('english', 'german');										// in which languages do we have information?
		public $importers 			= array();

		public $character_unique_ids = array('servername');

		public $game_settings		= array(
			'calendar_hide_emptyroles'	=> false,
		);

		protected $class_dependencies = array(
			array(
				'name'		=> 'faction',
				'type'		=> 'factions',
				'admin' 	=> true,
				'decorate'	=> false,
				'parent'	=> false,
			),
			array(
				'name'		=> 'race',
				'type'		=> 'races',
				'admin'		=> false,
				'decorate'	=> true,
				'parent'	=> array(
					'faction' => array(
						'alliance'	=> array(0,1,2,3,4),
						'horde'		=> array(0,5,6,7,8),
					),
				),
			),
			array(
				'name'		=> 'class',
				'type'		=> 'classes',
				'admin'		=> false,
				'decorate'	=> true,
				'primary'	=> true,
				'colorize'	=> true,
				'roster'	=> true,
				'recruitment' => true,
				'parent'	=> array(
					'race' => array(
						0 	=> 'all',							// Unknown
						1 	=> array(1,3,4,6,7,9,10,11),		// Gnome
						2 	=> array(1,3,4,5,6,7,9,10,11),		// Human
						3 	=> array(1,3,4,5,6,7,8,9,10,11),	// Dwarf
						4 	=> array(1,2,3,4,6,7,10,11,12),		// Night Elf
						5 	=> array(1,2,3,4,6,7,8,9,10,11),	// Troll
						6 	=> array(1,3,4,6,7,9,10,11),		// Undead
						7 	=> array(1,3,4,7,8,9,10,11),		// Orc
						8 	=> array(1,2,3,5,6,8,10,11),		// Tauren
					),
				),
			),
			array(
				'name'		=> 'talent1',
				'type'		=> 'talents',
				'admin'		=> false,
				'decorate'	=> false,
				'recruitment' => true,
				'parent'	=> array(
					'class' => array(
						0 	=> 'all',			// Unknown
						2 	=> array(3,4,5,6),	// Druid
						3 	=> array(7,8,9),	// Hunter
						4 	=> array(10,11,12),	// Mage
						5 	=> array(13,14,15),	// Paladin
						6 	=> array(16,17,18),	// Priest
						7 	=> array(19,20,21),	// Rogue
						8 	=> array(22,23,24),	// Shaman
						9 	=> array(25,26,27),	// Warlock
						10 	=> array(28,29,30),	// Warrior
					),
				),
			),
			array(
				'name'		=> 'talent2',
				'type'		=> 'talents',
				'admin'		=> false,
				'decorate'	=> false,
				'parent'	=> array(
					'class' => array(
						0 	=> 'all',			// Unknown
						2 	=> array(3,4,5,6),	// Druid
						3 	=> array(7,8,9),	// Hunter
						4 	=> array(10,11,12),	// Mage
						5 	=> array(13,14,15),	// Paladin
						6 	=> array(16,17,18),	// Priest
						7 	=> array(19,20,21),	// Rogue
						8 	=> array(22,23,24),	// Shaman
						9 	=> array(25,26,27),	// Warlock
						10 	=> array(28,29,30),	// Warrior
					),
				),
			),
		);

		public $default_roles = array(
			1	=> array(2, 5, 6, 8),			// healer
			2	=> array(2, 5, 10),				// tank
			3	=> array(2, 3, 4, 6, 8, 9),	// dd distance
			4	=> array(2, 3, 5, 7, 8, 10)	// dd near
		);

		public $default_classrole = array(
			2	=> 4,	// Druid
			3	=> 3,	// Hunter
			4	=> 3,	// Mage
			5	=> 4,	// Paladin
			6	=> 1,	// Priest
			7	=> 4,	// Rogue
			8	=> 4,	// Shaman
			9	=> 3,	// Warlock
			10	=> 1,	// Warrior
		);

		// source http://wow.gamepedia.com/Class_colors
		protected $class_colors = array(
			2	=> '#FF7D0A',	// Druid
			3	=> '#ABD473',	// Hunter
			4	=> '#69CCF0',	// Mage
			5	=> '#F58CBA',	// Paladin
			6	=> '#FFFFFF',	// Priest
			7	=> '#FFF569',	// Rogue
			8	=> '#0070DE',	// Shaman
			9	=> '#9482C9',	// Warlock
			10	=> '#C79C6E',	// Warrior
		);

		protected $glang		= array();
		protected $lang_file	= array();
		protected $path			= '';
		public $lang			= false;

		public function __construct() {
			parent::__construct();

			$this->strStaticIconUrl = $this->config->get('itt_icon_small_loc').'%s'.$this->config->get('itt_icon_ext');
			$this->pdh->register_read_module($this->this_game, $this->path . 'pdh/read/'.$this->this_game);
		}

		public function install($blnEQdkpInstall=false){
			$arrEventIDs = array();
			$arrEventIDs[] = $this->game->addEvent($this->glang('wotlk'), 0, "wotlk.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('cataclysm'), 0, "cata.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('burning_crusade'), 0, "bc.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('classic'), 0, "classic.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('mop'), 0, "mop.png");

			$this->game->updateDefaultMultiDKPPool('Default', 'Default MultiDKPPool', $arrEventIDs);

			//Ranks
			$this->game->addRank(0, "Guildmaster");
			$this->game->addRank(1, "Officer");
			$this->game->addRank(2, "Veteran");
			$this->game->addRank(3, "Member");
			$this->game->addRank(4, "Initiate", true);
			$this->game->addRank(5, "Dummy Rank #1");
			$this->game->addRank(6, "Dummy Rank #2");
			$this->game->addRank(7, "Dummy Rank #3");
			$this->game->addRank(8, "Dummy Rank #4");
			$this->game->addRank(9, "Dummy Rank #5");

		}

		public function uninstall(){
			$this->game->removeLink("WoW Battle.net");
		}

		protected function load_filters($langs){
			if(!count($this->classes)) {
				$this->load_type('classes', $langs);
			}
			foreach($langs as $lang) {
				$names = $this->classes[$this->lang];
				$this->filters[$lang] = array(
					array('name' => '-----------', 'value' => false),
					array('name' => $names[0], 'value' => 'class:0'),
					array('name' => $names[1], 'value' => 'class:1'),
					array('name' => $names[2], 'value' => 'class:2'),
					array('name' => $names[3], 'value' => 'class:3'),
					array('name' => $names[4], 'value' => 'class:4'),
					array('name' => $names[5], 'value' => 'class:5'),
					array('name' => $names[6], 'value' => 'class:6'),
					array('name' => $names[7], 'value' => 'class:7'),
					array('name' => $names[8], 'value' => 'class:8'),
					array('name' => $names[9], 'value' => 'class:9'),
					array('name' => $names[10], 'value' => 'class:10'),
					array('name' => $names[11], 'value' => 'class:11'),
					array('name' => $names[12], 'value' => 'class:12'),
					array('name' => '-----------', 'value' => false),
					array('name' => $this->glang('plate', true, $lang), 'value' => 'class:1,5,10'),
					array('name' => $this->glang('mail', true, $lang), 'value' => 'class:3,8'),
					array('name' => $this->glang('leather', true, $lang), 'value' => 'class:2,7,11,12'),
					array('name' => $this->glang('cloth', true, $lang), 'value' => 'class:4,6,9'),
					array('name' => '-----------', 'value' => false),
					array('name' => $this->glang('tier_token', true, $lang).$names[3].', '.$names[10].', '.$names[8].', '.$names[11].', '.$names[12], 'value' => 'class:3,8,10,11,12'),
					array('name' => $this->glang('tier_token', true, $lang).$names[5].', '.$names[6].', '.$names[9], 'value' => 'class:5,6,9'),
					array('name' => $this->glang('tier_token', true, $lang).$names[1].', '.$names[2].', '.$names[4].', '.$names[7], 'value' => 'class:1,2,4,7'),
				);
			}
		}

		public function decorate_classes($class_id, $profile=array(), $size=16, $pathonly=false) {
			$big = ($size > 40) ? '_b' : '';
			if(is_file($this->root_path.'games/'.$this->this_game.'/icons/classes/'.$class_id.$big.'.png')){
				$icon_path = $this->server_path.'games/'.$this->this_game.'/icons/classes/'.$class_id.$big.'.png';
				return ($pathonly) ? $icon_path : '<img src="'.$icon_path.'" height="'.$size.'" alt="class '.$class_id.'" class="'.$this->this_game.'_classicon classicon'.'" title="'.$this->game->get_name('classes', $class_id).'" />';
			}
			return false;
		}

		public function profilefields(){
			// Category 'character' is a fixed one! All others are created dynamically!
			$this->load_type('professions', array($this->lang));
			$xml_fields = array(
				'guild'	=> array(
					'type'			=> 'text',
					'category'		=> 'character',
					'lang'			=> 'uc_guild',
					'size'			=> 32,
					'undeletable'	=> true,
					'sort'			=> 1
				),
				'gender'	=> array(
					'type'			=> 'dropdown',
					'category'		=> 'character',
					'lang'			=> 'uc_gender',
					'options'		=> array('male' => 'uc_male', 'female' => 'uc_female'),
					'tolang'		=> true,
					'undeletable'	=> true,
					'sort'			=> 3
				),
				'level'	=> array(
					'type'			=> 'spinner',
					'category'		=> 'character',
					'lang'			=> 'uc_level',
					'max'			=> 110,
					'min'			=> 1,
					'undeletable'	=> true,
					'sort'			=> 4
				),
				'health_bar'	=> array(
					'type'			=> 'int',
					'category'		=> 'character',
					'lang'			=> 'uc_bar_health',
					'undeletable'	=> true,
					'size'			=> 4,
					'sort'			=> 5
				),
				'second_bar'	=> array(
					'type'			=> 'int',
					'category'		=> 'character',
					'lang'			=> 'uc_bar_2value',
					'size'			=> 4,
					'undeletable'	=> true,
					'sort'			=> 6
				),
				'second_name'	=> array(
					'type'			=> 'dropdown',
					'category'		=> 'character',
					'lang'			=> 'uc_bar_2name',
					'options'		=> array('rage' => 'uc_bar_rage', 'energy' => 'uc_bar_energy', 'mana' => 'uc_bar_mana', 'focus' => 'uc_bar_focus', 'runic-power' => 'uc_bar_runic-power'),
					'tolang'		=> true,
					'size'			=> 32,
					'undeletable'	=> true,
					'sort'			=> 7
				),
				'prof1_name'	=> array(
					'type'			=> 'dropdown',
					'category'		=> 'profession',
					'lang'			=> 'uc_prof1_name',
					'options'		=> $this->professions[$this->lang],
					'undeletable'	=> true,
					'image'			=> "games/wow/profiles/professions/{VALUE}.jpg",
					'options_lang'	=> "professions",
					'sort'			=> 1,
				),
				'prof1_value'	=> array(
					'type'			=> 'int',
					'category'		=> 'profession',
					'lang'			=> 'uc_prof1_value',
					'size'			=> 4,
					'undeletable'	=> true,
					'sort'			=> 2
				),
				'prof2_name'	=> array(
					'type'			=> 'dropdown',
					'category'		=> 'profession',
					'lang'			=> 'uc_prof2_name',
					'options'		=> $this->professions[$this->lang],
					'undeletable'	=> true,
					'image'			=> "games/wow/profiles/professions/{VALUE}.jpg",
					'options_lang'	=> "professions",
					'sort'			=> 3,
				),
				'prof2_value'	=> array(
					'type'			=> 'int',
					'category'		=> 'profession',
					'lang'			=> 'uc_prof2_value',
					'size'			=> 4,
					'undeletable'	=> true,
					'sort'			=> 4
				),
			);
			return $xml_fields;
		}

		public function admin_settings() {
			return array();
		}

		######################################################################
		##																	##
		##							EXTRA FUNCTIONS							##
		##																	##
		######################################################################

		/**
		 *	Content for the Chartooltip
		 *
		 */
		public function chartooltip($intCharID){
			$template = $this->root_path.'games/'.$this->this_game.'/chartooltip/chartooltip.tpl';
			$content = file_get_contents($template);
			$charicon = $this->pdh->get('wow', 'charicon', array($intCharID));
			if ($charicon == '') {
				$charicon = $this->server_path.'images/global/avatar-default.svg';
			}
			$charhtml = '<b>'.$this->pdh->get('member', 'html_name', array($intCharID)).'</b><br />';
			$guild = $this->pdh->get('member', 'profile_field', array($intCharID, 'guild'));
			if (strlen($guild)) $charhtml .= '<br />&laquo;'.$guild.'&raquo;';

			$charhtml .= '<br />'.$this->pdh->get('member', 'html_racename', array($intCharID));
			$charhtml .= ' '.$this->pdh->get('member', 'html_classname', array($intCharID));
			$charhtml .= '<br />'.$this->user->lang('level').' '.$this->pdh->get('member', 'level', array($intCharID));


			$content = str_replace('{CHAR_ICON}', $charicon, $content);
			$content = str_replace('{CHAR_HTML}', $charhtml, $content);

			return $content;
		}

		/**
		 * Per game data for the calendar Tooltip
		 */
		public function calendar_membertooltip($memberid){
			$talents			= $this->game->glang('talents');
			$member_data	= $this->pdh->get('member', 'array', array($memberid));

			return array(
				$this->game->glang('talents_tt_1').': '.$this->pdh->geth('member', 'profile_field', array($memberid, 'talent1', true)),
				$this->game->glang('talents_tt_2').': '.$this->pdh->geth('member', 'profile_field', array($memberid, 'talent2', true)),
			);
		}

	}#class
}
?>
