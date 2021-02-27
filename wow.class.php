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
		public $version				= '8.3.23.12'; //Version for EQdkp Plus 2.3
		protected $this_game		= 'wow';
		protected $types			= array('factions', 'races', 'classes', 'talents', 'filters', 'realmlist', 'roles', 'classrole', 'professions', 'chartooltip');	// which information are stored?
		protected $classes			= array();
		protected $roles			= array();
		protected $races			= array();															// for each type there must be the according var
		protected $factions			= array();															// and the according function: load_$type
		protected $filters			= array();
		protected $realmlist		= array();
		protected $professions		= array();
		public $objects				= array('bnet_armory');												// eventually there are some objects (php-classes) in this game
		public $no_reg_obj			= array('bnet_armory');												// a list with all objects, which dont need registry
		public $langs				= array('english', 'german');										// in which languages do we have information?
		public $importers 			= array();

		public $character_unique_ids = array('servername');

		public $game_settings		= array(
			'calendar_hide_emptyroles'	=> false,
		);

		// http://eu.battle.net/wow/static/images/character/summary/raid-icons.jpg
		protected $ArrInstanceCategories = array(
			'classic'	=> array(2717, 2677, 3429, 3428),
			'bc'		=> array(3457, 3836, 3923, 3607, 3845, 3606, 3959, 4075),
			'wotlk'		=> array(4603, 3456, 4493, 4500, 4273, 2159, 4722, 4812, 4987),
			'cataclysm'	=> array(5600, 5094, 5334, 5638, 5723, 5892),
			'mop'		=> array(6125, 6297, 6067, 6622, 6738),
			'wod'		=> array(6967, 6996, 7545),
			'leg'		=> array(8026, 8440, 8025, 8524, 8638),
			'bfa'		=> array(9389, 8670, 10057, 10425, 10522),
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
						'alliance'	=> array(0,1,2,3,4,9,11,13,16,17,18,21,23),
						'horde'		=> array(0,5,6,7,8,10,12,13,14,15,19,20,22),
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
				'recruitment' 	=> true,
				'parent'	=> array(
					'race' => array(
						0	=> 'all',							// Unknown
						1	=> array(1,3,4,6,7,9,10,11),		// Gnome
						2	=> array(1,3,4,5,6,7,9,10,11),		// Human
						3	=> array(1,3,4,5,6,7,8,9,10,11),	// Dwarf
						4	=> array(1,2,3,4,6,7,10,11,12),		// Night Elf
						5	=> array(1,2,3,4,6,7,8,9,10,11),	// Troll
						6	=> array(1,3,4,6,7,9,10,11),		// Undead
						7	=> array(1,3,4,7,8,9,10,11),		// Orc
						8	=> array(1,2,3,5,6,8,10,11),		// Tauren
						9	=> array(1,3,4,5,6,8,10,11),		// Draenei
						10	=> array(1,3,4,5,6,7,9,10,11,12),	// Blood Elf
						11	=> array(1,2,3,4,6,7,9,10),			// Worgen
						12	=> array(1,3,4,6,7,8,9,10),			// Goblin
						13	=> array(1,3,4,6,7,8,10,11),		// Pandaren
						14	=> array(1,3,4,11,6,7,9,10),		// Nightborne
						15	=> array(1,2,3,11,8,10),			// Highmountain Tauren
						16	=> array(1,3,4,11,6,7,9,10), 		// Void Elf
						17	=> array(1,3,4,5,6,10),				// Lightforged Draenei
						18	=> array(1,3,4,5,6,7,8,9,10,11),	// Dark Iron Dwarf
						19	=> array(1,3,4,11,6,7,8,10),		// Mag'har Orc
						20	=> array(1,2,3,4,11,5,6,7,8,10),	// Zandalari Troll
						21	=> array(1,2,3,4,11,6,7,8,10), 		// Kul Tiran
						22	=> array(1,3,4,6,7,8,9,10,11),		// Vulpera
						23	=> array(1,3,4,6,7,9,10,11),		// Mechagnome
					),
				),
			),
			array(
				'name'		=> 'talent1',
				'type'		=> 'talents',
				'admin'		=> false,
				'decorate'	=> false,
				'recruitment' 	=> true,
				'parent'	=> array(
					'class' => array(
						0	=> 'all',			// Unknown
						1	=> array(0,1,2),	// Death Knight
						2	=> array(3,4,5,6),	// Druid
						3	=> array(7,8,9),	// Hunter
						4	=> array(10,11,12),	// Mage
						5	=> array(13,14,15),	// Paladin
						6	=> array(16,17,18),	// Priest
						7	=> array(19,20,21),	// Rogue
						8	=> array(22,23,24),	// Shaman
						9	=> array(25,26,27),	// Warlock
						10	=> array(28,29,30),	// Warrior
						11	=> array(31,32,33),	// Monk
						12	=> array(34,35),	// Demon Hunter
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
						0	=> 'all',			// Unknown
						1	=> array(0,1,2),	// Death Knight
						2	=> array(3,4,5,6),	// Druid
						3	=> array(7,8,9),	// Hunter
						4	=> array(10,11,12),	// Mage
						5	=> array(13,14,15),	// Paladin
						6	=> array(16,17,18),	// Priest
						7	=> array(19,20,21),	// Rogue
						8	=> array(22,23,24),	// Shaman
						9	=> array(25,26,27),	// Warlock
						10	=> array(28,29,30),	// Warrior
						11	=> array(31,32,33),	// Monk
						12	=> array(34,35),	// Demon Hunter
					),
				),
			),
		);

		public $default_roles = array(
			1	=> array(2, 5, 6, 8, 11),					// healer
			2	=> array(1, 2, 5, 10, 11, 12),				// tank
			3	=> array(2, 3, 4, 6, 8, 9),					// dd distance
			4	=> array(1, 2, 3, 5, 7, 8, 10, 11, 12)		// dd near
		);

		public $default_classrole = array(
			1	=> 4,	// Death Knight
			2	=> 4,	// Druid
			3	=> 3,	// Hunter
			4	=> 3,	// Mage
			5	=> 4,	// Paladin
			6	=> 1,	// Priest
			7	=> 4,	// Rogue
			8	=> 4,	// Shaman
			9	=> 3,	// Warlock
			10	=> 1,	// Warrior
			11	=> 4,	// Monk
			12	=> 2,	// Demon Hunter
		);

		// source http://wow.gamepedia.com/Class_colors
		protected $class_colors = array(
			1	=> '#C41F3B',	// Death Knight
			2	=> '#FF7D0A',	// Druid
			3	=> '#ABD473',	// Hunter
			4	=> '#69CCF0',	// Mage
			5	=> '#F58CBA',	// Paladin
			6	=> '#FFFFFF',	// Priest
			7	=> '#FFF569',	// Rogue
			8	=> '#0070DE',	// Shaman
			9	=> '#9482C9',	// Warlock
			10	=> '#C79C6E',	// Warrior
			11	=> '#00FF96',	// Monk
			12	=> '#A330C9',	// Demon Hunter
		);

		protected $glang		= array();
		protected $lang_file	= array();
		protected $path			= '';
		public $lang			= false;

		public function __construct() {
			parent::__construct();
			$this->importers = array(
				'char_import'		=> 'charimporter.php',						// filename of the character import
				'char_update'		=> 'charimporter.php',						// filename of the character update, member_id (POST) is passed
				'char_mupdate'		=> 'charimporter.php'.$this->SID.'&massupdate=true',		// filename of the "update all characters" aka mass update
				'guild_import'		=> 'guildimporter.php',						// filename of the guild import
				'import_reseturl'	=> 'charimporter.php'.$this->SID.'&resetcache=true',		// filename of the reset cache
				'guild_imp_rsn'		=> true,									// Guild import & Mass update requires server name
				'import_data_cache'	=> true,									// Is the data cached and requires a reset call?
				'apikey'			=> array(
					'version'	=> 2,
					'status'	=> 'required',
					'form'		=> array(
						'game_importer_clientid' => array(
							'type'			=> 'text',
							'size'			=> 30,
						),
						'game_importer_clientsecret' => array(
							'type'			=> 'text',
							'size'			=> 30,
						)
					),
					'steps'		=> array(
						'apikey_title_step1'	=> 'apikey_content_step1',
						'apikey_title_step2'	=> 'apikey_content_step2',
						'apikey_title_step3'	=> 'apikey_content_step3'
					),
				)
			);

			$this->strStaticIconUrl = $this->config->get('itt_icon_small_loc').'%s'.$this->config->get('itt_icon_ext');
			$this->pdh->register_read_module($this->this_game, $this->path . 'pdh/read/'.$this->this_game);
			$this->game_avatar();
		}

		public function install($blnEQdkpInstall=false){

			$arrEventIDs = array();

			// Battle for Azeroth
			$arrEventIDs[] = $this->game->addEvent($this->glang('bfa_uldir_normal'), 0, "uldir.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('bfa_uldir_heroic'), 0, "uldir.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('bfa_uldir_mythic'), 0, "uldir.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('bfa_bod_normal'), 0, "bod.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('bfa_bod_heroic'), 0, "bod.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('bfa_bod_mythic'), 0, "bod.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('bfa_cos_normal'), 0, "cos.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('bfa_cos_heroic'), 0, "cos.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('bfa_cos_mythic'), 0, "cos.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('bfa_tep_normal'), 0, "tep.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('bfa_tep_heroic'), 0, "tep.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('bfa_tep_mythic'), 0, "tep.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('bfa_twc_normal'), 0, "twc.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('bfa_twc_heroic'), 0, "twc.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('bfa_twc_mythic'), 0, "twc.png");

			// Legion events
			$arrEventIDs[] = $this->game->addEvent($this->glang('leg_en_normal'), 0, "en.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('leg_en_heroic'), 0, "en.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('leg_en_mythic'), 0, "en.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('leg_nh_normal'), 0, "nh.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('leg_nh_heroic'), 0, "nh.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('leg_nh_mythic'), 0, "nh.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('leg_tov_normal'), 0, "tov.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('leg_tov_heroic'), 0, "tov.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('leg_tov_mythic'), 0, "tov.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('leg_tos_normal'), 0, "tos.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('leg_tos_heroic'), 0, "tos.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('leg_tos_mythic'), 0, "tos.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('leg_atbt_normal'), 0, "atbt.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('leg_atbt_heroic'), 0, "atbt.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('leg_atbt_mythic'), 0, "atbt.png");

			// WoD Events
			$arrEventIDs[] = $this->game->addEvent($this->glang('wod_hm_normal'), 0, "hm.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('wod_hm_heroic'), 0, "hm.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('wod_hm_mythic'), 0, "hm.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('wod_brf_normal'), 0, "brf.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('wod_brf_heroic'), 0, "brf.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('wod_brf_mythic'), 0, "brf.png");

			// Mop Events
			$arrEventIDs[] = $this->game->addEvent($this->glang('mop_mogushan_10'), 0, "mv.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('mop_mogushan_25'), 0, "mv.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('mop_heartoffear_10'), 0, "hf.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('mop_heartoffear_25'), 0, "hf.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('mop_endlessspring_10'), 0, "tes.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('mop_endlessspring_25'), 0, "tes.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('mop_throneofthunder_10'), 0, "tot.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('mop_throneofthunder_25'), 0, "tot.png");
			$arrEventIDs[] = $this->game->addEvent($this->glang('mop_siegeoforgrimmar'), 0, "soo.png");

			// Classic
			$arrClassicEventIDs = array();
			$arrClassicEventIDs[] = $this->game->addEvent($this->glang('wotlk'), 0, "wotlk.png");
			$arrClassicEventIDs[] = $this->game->addEvent($this->glang('cataclysm'), 0, "cata.png");
			$arrClassicEventIDs[] = $this->game->addEvent($this->glang('burning_crusade'), 0, "bc.png");
			$arrClassicEventIDs[] = $this->game->addEvent($this->glang('classic'), 0, "classic.png");
			$arrClassicEventIDs[] = $this->game->addEvent($this->glang('mop'), 0, "mop.png");

			$this->game->updateDefaultMultiDKPPool('Default', 'Default MultiDKPPool', $arrEventIDs);

			$intItempoolClassic = $this->game->addItempool("Classic", "Classic Itempool");
			$this->game->addMultiDKPPool("Classic", "Classic MultiDKPPool", $arrClassicEventIDs, array($intItempoolClassic));

			//Links
			$this->game->addLink('WoW Battle.net', 'http://eu.battle.net/wow/');

			//Columns for Roster
			$this->pdh->add_object_tablepreset('roster', 'hptt_roster',
					array('name' => 'wow_charicon', 'sort' => false, 'th_add' => 'width="52"', 'td_add' => '')
			);

			$this->pdh->add_object_tablepreset('roster', 'hptt_roster',
					array('name' => 'profile_guild', 'sort' => true, 'th_add' => 'width="160"', 'td_add' => '')
			);

			$this->pdh->add_object_tablepreset('roster', 'hptt_roster',
					array('name' => 'wow_achievementpoints', 'sort' => true, 'th_add' => 'width="160"', 'td_add' => '')
			);

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

		public function profilefields(){
			// Category 'character' is a fixed one! All others are created dynamically!
			$this->load_type('professions', array($this->lang));
			$this->load_type('realmlist', array($this->lang));
			$xml_fields = array(
				'guild'	=> array(
					'type'			=> 'text',
					'category'		=> 'character',
					'lang'			=> 'uc_guild',
					'size'			=> 32,
					'undeletable'	=> true,
					'sort'			=> 1
				),
				'servername'	=> array(
					'category'		=> 'character',
					'lang'			=> 'servername',
					'type'			=> 'text',
					'size'			=> '21',
					'edecode'		=> true,
					'autocomplete'	=> $this->realmlist[$this->lang],
					'undeletable'	=> true,
					'sort'			=> 2
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
					'options'		=> array('rage' => 'uc_bar_rage', 'energy' => 'uc_bar_energy', 'mana' => 'uc_bar_mana', 'focus' => 'uc_bar_focus', 'runic-power' => 'uc_bar_runic-power', 'maelstrom' => 'uc_bar_maelstrom'),
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

		public function game_avatar(){
			$this->hooks->register('avatar_provider', 'battlenet_user_avatarimg_hook', 'avatar_provider', 'games/wow/hooks/', array());
			$this->hooks->register('user_avatarimg', 'battlenet_user_avatarimg_hook', 'user_avatarimg', 'games/wow/hooks/', array());
		}

		public function cronjobOptions(){
			$arrOptions = array(
				'sync_ranks'	=> array(
						'lang'	=> 'Sync Ranks',
						'name'	=> 'sync_ranks',
						'type'	=> 'radio',
				),
				'char_import_ranks_level'	=> array(
						'lang'	=> 'Import characters with a level higher than',
						'name'	=> 'char_import_ranks_level',
						'type'	=> 'text',
				),
				'char_import_only_ranks' 	=> array(
						'lang'	=> 'Only import characters with rank (comma separated)',
						'help'	=> 'Leave empty to import all ranks',
						'name'	=> 'char_import_only_ranks',
						'type'	=> 'text',
				),
				'delete_chars'	=> array(
						'lang'	=> 'Delete Chars that have left the Guild',
						'name'	=> 'delete_chars',
						'type'	=> 'radio',
				),
			);
			return $arrOptions;
		}

		public function cronjob($arrParams = array()){
			$blnSyncRanks = ((int)$arrParams['sync_ranks'] == 1) ? true : false;
			$blnDeleteChars = ((int)$arrParams['delete_chars'] == 1) ? true : false;

			// Sanitize the 'only import ranks' param, if provided
			$onlyImportRanks = false;
			if (!empty($arrParams['char_import_only_ranks'])) {
				$onlyImportRanks = array();
				$rawOnlyImportRanks = explode(',', $arrParams['char_import_only_ranks']);

				foreach ($rawOnlyImportRanks as $rank) {
					$rank = trim($rank);
					if (is_numeric($rank)) {
						$onlyImportRanks[] = $rank;
					}
				}
			}


			$this->game->new_object('bnet_armory', 'armory', array($this->config->get('uc_server_loc'), $this->config->get('uc_data_lang')));

			//Guildimport
			$guilddata	= $this->game->obj['armory']->guildRoster($this->config->get('guildtag'), unsanitize($this->config->get('servername')), true);

			if($guilddata && !isset($guilddata['status'])){
				//Suspend all Chars
				if ($blnDeleteChars){
					$this->pdh->put('member', 'suspend', array('all'));
				}
				$slugCache = array();
				foreach($guilddata['members'] as $guildchars){
					if(isset($slugCache[$guildchars['character']['realm']['slug']])){
						$servername = $slugCache[$guildchars['character']['realm']['slug']];
					} else {
						$realm = $this->game->obj['armory']->realm($guildchars['character']['realm']['slug']);
						$servername = $realm['name'];
						$slugCache[$guildchars['character']['realm']['slug']] = $servername;
					}
					
					
					$jsondata = array(
							'thumbnail'		=> false,
							'name'			=> $guildchars['character']['name'],
							'class'			=> $this->game->obj['armory']->ConvertID( $guildchars['character']['playable_class']['id'], 'int', 'classes'),
							'race'			=> $this->game->obj['armory']->ConvertID( $guildchars['character']['playable_race']['id'], 'int', 'races'),
							'level'			=> $guildchars['character']['level'],
							//'gender'		=> $guildchars['character']['gender'],
							'rank'			=> $guildchars['rank'],
							'servername'	=> $servername,
							'server_slug'	=> $guildchars['character']['realm']['slug'],
							'guild'			=> $guilddata['guild']['name'],
					);

					//Build Rank ID
					$intRankID = $this->pdh->get('rank', 'default', array());
					if ($blnSyncRanks){
						$arrRanks = $this->pdh->get('rank', 'id_list');
						$inRankID = (int)$jsondata['rank'];
						if (isset($arrRanks[$inRankID])) $intRankID = $arrRanks[$inRankID];
					}

					//char available
					$intMemberID = $this->pdh->get('member', 'id', array($jsondata['name'], array('servername' => $jsondata['servername'])));

					if($intMemberID){
						//Sync Rank
						if ($blnSyncRanks){
							$dataarry = array(
								'rankid'	=> $intRankID,
							);
							//Disable logging for chars that will be updated
							$myStatus = $this->pdh->put('member', 'addorupdate_member', array($intMemberID, $dataarry,false, false));
						}

						//Revoke Char
						if($blnDeleteChars){
							$this->pdh->put('member', 'revoke', array($intMemberID));
							$this->pdh->process_hook_queue();
						}

					} else {
						if ((int)$arrParams['char_import_ranks_level'] > 0 && (int)$jsondata['rank'] < (int)$arrParams['char_import_ranks_level']) {
							continue;
						} else if ($onlyImportRanks) {
							// Skip member unless they have a rank in the list
							if (!in_array((int)$jsondata['rank'], $onlyImportRanks)) {
								continue;
							}
						}

						//Create new char
						$jsondata['rankid'] = $intRankID;

						//Logging is still active, because its a new char
						$myStatus = $this->pdh->put('member', 'addorupdate_member', array(0, $jsondata));

						echo "<br/>add member ".$jsondata['name'];

						// reset the cache
						$this->pdh->process_hook_queue();
					}
				}
			}

			//Guildupdate
			$ratepersecond = 100;
			$rate 		= 1000000/$ratepersecond;

			$arrMemberIDs = $this->pdh->get('member', 'id_list', array());
			shuffle($arrMemberIDs);
			foreach($arrMemberIDs as $memberID){
				$strMemberName = $this->pdh->get('member', 'name', array($memberID));
				if (strlen($strMemberName)){

					$char_server	= $this->pdh->get('member', 'profile_field', array($memberID, 'servername'));
					$servername		= ($char_server != '') ? $char_server : $this->config->get('servername');
					$chardata		= $this->game->obj['armory']->character($strMemberName, unsanitize($servername), true);


					if($chardata && !isset($chardata['status']) && !empty($chardata['name']) && $chardata['name'] != 'none'){
						$errormsg	= '';
						$charname	= $chardata['name'];

						// insert into database

						$info = $this->pdh->put('member', 'addorupdate_member', array($memberID, array(
								'level'				=> $chardata['level'],
								'gender'			=> strtolower($chardata['gender']['type']),
								'race'				=> $this->game->obj['armory']->ConvertID($chardata['race'], 'int', 'races'),
								'class'				=> $this->game->obj['armory']->ConvertID($chardata['class'], 'int', 'classes'),
								'guild'				=> sanitize($chardata['guild']['name']),
								'servername'		=> $servername,
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
						), 0));

						echo "<br/>update memberid ".$memberID;
					}

					if($rate > 0){
						usleep($rate);
					}
				}
			}

			$this->pdh->process_hook_queue();
			$this->config->set(array('uc_profileimported'=> $this->time->time));
		}

		public function admin_settings() {
			$settingsdata_admin = array(
				'uc_server_loc'	=> array(
					'lang'		=> 'uc_server_loc',
					'type' 		=> 'dropdown',
					'options'	=> array('eu' => 'EU', 'us' => 'US', 'tw' => 'TW', 'kr' => 'KR', 'cn' => 'CN'),
				),
				'uc_data_lang'	=> array(
					'lang'		=> 'uc_data_lang',
					'type' 		=> 'dropdown',
					'options'	=> array(
						'en_US' => 'English',
						'en_GB' => 'English (Great Britain)',
						'de_DE'	=> 'German',	
						'es_MX' => 'Spanish (Mexico)',
						'es_ES' => 'Spanish (Spain)',
						'pt_BR' => 'Portuguese',					
						'fr_FR' => 'French',
						'it_IT' => 'Italian',
						'ru_RU' => 'Russian',
						'ko_KR'	=> 'Korean',
						'zh_TW'	=> 'Chinese (Traditional)',
						'zh_CN'	=> 'Chinese (Simplified)',
					),
				),

				'servername'	=> array(
					'lang'			=> 'servername',
					'type'			=> 'text',
					'size'			=> '21',
					'autocomplete'	=> $this->game->get('realmlist'),
				),
				'profile_boskills_hide'	=> array(
					'lang'		=> 'uc_profile_boskills_hide',
					'type' 		=> 'multiselect',
					'options'	=> array(
						68	=> $this->game->glang('uc_achievement_tab_classic'),
						70		=> $this->game->glang('uc_achievement_tab_bc'),
						72		=> $this->game->glang('uc_achievement_tab_wotlk'),
						73	=> $this->game->glang('uc_achievement_tab_cataclysm'),
						74		=> $this->game->glang('uc_achievement_tab_mop'),
						124		=> $this->game->glang('uc_achievement_tab_wod'),
						395	=> $this->game->glang('uc_achievement_tab_leg'),
						396 => $this->game->glang('uc_achievement_tab_bfa'),
					),
				)
			);
			return $settingsdata_admin;
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

			// itemlevel in tooltip
			$this->game->new_object('bnet_armory', 'armory', array($this->config->get('uc_server_loc'), $this->config->get('uc_data_lang')));
			$char_server	= $this->pdh->get('member', 'profile_field', array($memberid, 'servername'));
			$servername		= ($char_server != '') ? $char_server : $this->config->get('servername');
			$chardata		= $this->game->obj['armory']->character($member_data['name'], unsanitize($servername), false);
			$itemlevel		= (isset($chardata['items']['averageItemLevel'])) ? $chardata['items']['averageItemLevel'] : '--';

			return array(
				$this->game->glang('talents_tt_1').': '.$this->pdh->geth('member', 'profile_field', array($memberid, 'talent1', true)),
				$this->game->glang('talents_tt_2').': '.$this->pdh->geth('member', 'profile_field', array($memberid, 'talent2', true)),
				$this->game->glang('caltooltip_itemlvl').': '.$itemlevel,
			);
		}

		/**
		 * Append Servername to Charname
		 *
		 * {@inheritDoc}
		 * @see game_generic::handle_export_charnames()
		 */
		public function handle_export_charnames($strCharname, $intCharID){
			$char_server	= $this->pdh->get('member', 'profile_field', array($intCharID, 'servername'));
			$servername		= ($char_server != '') ? $char_server : $this->config->get('servername');

			return $strCharname.(($servername != "") ? '-'.unsanitize($servername) : '');
		}

		/**
		 * Parse the guild news of armory
		 */
		public function parseGuildnews($arrNews, $intCount = 50, $arrTypes = false){
			$this->game->new_object('bnet_armory', 'armory', array($this->config->get('uc_server_loc'), $this->config->get('uc_data_lang')));

			$arrOut = array();

			if(is_array($arrNews)){
				$i = 0;
				foreach($arrNews as $val){
					if ($i == $intCount) break;

					$type = strtolower($val['activity']['type']);
					$time = $val['timestamp'];

					switch($type){
						case 'encounter':
							if (is_array($arrTypes) && !in_array('encounter_completed', $arrTypes)) continue 2;

							$data = $val['encounter_completed'];

							$arrOut[] = array(
									'text' => sprintf($this->glang('news_encounterCompleted'), $data['encounter']['name'], $data['mode']['name']),
									'icon' => '',
									'desc' => '',
									'date' => substr($time, 0, -3),
							);

							break;

						case 'character_achievement':{
							if (is_array($arrTypes) && !in_array('character_achievement', $arrTypes)) continue 2;

							$data = $val[$type];

							$charID = register('pdh')->get('member', 'id', array(trim($data['character']['name'])));
							if ($charID) {
								$charLink = register('pdh')->get('member', 'html_memberlink', array($charID, $this->routing->simpleBuild('character'),'', false, false, true, true));
							} else {
								$charLink = $data['character']['name'];
							}

							$arrAchiev =  $this->game->obj['armory']->achievement($data['achievement']['id'], false);
							$arrAchievMedia = $this->game->obj['armory']->achievement($data['achievement']['id'], true);

							$arrOut[] = array(
								'text' => sprintf($this->glang('news_playerAchievement'), $charLink, $data['achievement']['name'], $arrAchiev['points']),
								'icon' => $arrAchievMedia['assets'][0]['value'],
								'desc' => $arrAchiev['description'],
								'date' => substr($time, 0, -3),
							);
						}
						break;
					}
					$i++;
				}
			}

			return $arrOut;
		}

		/*
		 * parse the guild achievement overview of armory
		 */
		public function parseGuildAchievementOverview($arrAchievs){
			$this->game->new_object('bnet_armory', 'armory', array($this->config->get('uc_server_loc'), $this->config->get('uc_data_lang')));

			$arrGuildAchievementsData = $this->game->obj['armory']->getdata('guild', 'achievements');
			$arrOut = array();
			$done = array();
			$doneIDs = array();
			$arrOut['total'] = array(
				'total' => 0
			);
			foreach ($arrGuildAchievementsData['achievements'] as $arrCatAchievs){
				$completed = 0;
				$achievs = 0;

				foreach ($arrCatAchievs['achievements'] as $arrCatAchievs2){

					//if (isset($done[$arrCatAchievs2['title']])) continue;
					if (isset($doneIDs[$arrCatAchievs2['id']])) continue;
					$done[$arrCatAchievs2['title']] = true;
					$doneIDs[$arrCatAchievs2['id']] = true;

					if (in_array((int)$arrCatAchievs2['id'], $arrAchievs['achievementsCompleted'])) $completed++;
					$achievs++;
				}

				if (isset($arrCatAchievs['categories'])){
					foreach ($arrCatAchievs['categories'] as $arrCatAchievs2){

						foreach ($arrCatAchievs2['achievements'] as $arrCatAchievs3){
							//if (isset($done[$arrCatAchievs3['title']])) continue;
							if (isset($doneIDs[$arrCatAchievs3['id']])) continue;
							$done[$arrCatAchievs3['title']] = true;
							$doneIDs[$arrCatAchievs3['id']] = true;

							if (in_array((int)$arrCatAchievs3['id'], $arrAchievs['achievementsCompleted'])) $completed++;
							$achievs++;
						}
					}
				}

				$arrOut[$arrCatAchievs['id']] = array(
					'id'	=> $arrCatAchievs['id'],
					'name'	=> $arrCatAchievs['name'],
					'total' => $achievs,
					'completed' => $completed,
				);
			}

			//Now, let's cheat a bit
			$arrOut[15088]['total'] = $arrOut[15088]['total'] - 8;
			$arrOut[15078]['total'] = $arrOut[15078]['total'] - 13;
			$arrOut[15079]['total'] = $arrOut[15079]['total'] - 2;
			$arrOut[15089]['total'] = $arrOut[15089]['completed'];
			$arrOut[15093]['total'] = $arrOut[15093]['completed'];

			$total = 0;
			foreach ($arrOut as $val){
				$total += $val['total'];
			}

			$arrOut['total'] = array(
				'total' 	=> $total,
				'completed' => count($arrAchievs['achievementsCompleted']),
				'name' 		=> $this->glang('guildachievs_total_completed'),
			);

			return $arrOut;
		}

		/*
		 * parse the guild achievement overview of armory
		 */
		public function parseCharAchievementOverview($chardata){
			$this->game->new_object('bnet_armory', 'armory', array($this->config->get('uc_server_loc'), $this->config->get('uc_data_lang')));

			$arrAchievs = $chardata['achievements'];
			$arrCharAchievementsData = $this->game->obj['armory']->getdata('character', 'achievements');
			$arrOut = array();
			$done = array();
			$doneIDs = array();
			$arrOut['total'] = array(
				'total' => 0
			);
			if(is_array($arrCharAchievementsData['achievements'])){
				foreach ($arrCharAchievementsData['achievements'] as $arrCatAchievs){
					$completed = 0;
					$achievs = 0;

					foreach ($arrCatAchievs['achievements'] as $arrCatAchievs2){

						//if (isset($done[$arrCatAchievs2['title']])) continue;
						if (isset($doneIDs[$arrCatAchievs2['id']])) continue;
						$done[$arrCatAchievs2['title']] = true;
						$doneIDs[$arrCatAchievs2['id']] = true;

						if (is_array($arrAchievs['achievementsCompleted']) && in_array((int)$arrCatAchievs2['id'], $arrAchievs['achievementsCompleted'])) $completed++;
						$achievs++;
					}

					if (isset($arrCatAchievs['categories'])){
						foreach ($arrCatAchievs['categories'] as $arrCatAchievs2){

							foreach ($arrCatAchievs2['achievements'] as $arrCatAchievs3){
								//if (isset($done[$arrCatAchievs3['title']])) continue;
								if (isset($doneIDs[$arrCatAchievs3['id']])) continue;
								$done[$arrCatAchievs3['title']] = true;
								$doneIDs[$arrCatAchievs3['id']] = true;

								if (is_array($arrAchievs['achievementsCompleted']) && in_array((int)$arrCatAchievs3['id'], $arrAchievs['achievementsCompleted'])) $completed++;
								$achievs++;
							}
						}
					}

					$arrOut[$arrCatAchievs['id']] = array(
						'id'	=> $arrCatAchievs['id'],
						'name'	=> $arrCatAchievs['name'],
						'total' => $achievs,
						'completed' => $completed,
					);
				}
			}

			$total = 0;
			foreach ($arrOut as $val){
				$total += $val['total'];
			}

			$arrOut['total'] = array(
				'total' 	=> $total,
				'completed' => count($arrAchievs['achievementsCompleted']),
				'name' 		=> $this->glang('guildachievs_total_completed'),
			);

			return $arrOut;
		}

		/*
		 * parse the latest guild achievements of armory
		 */
		public function parseLatestGuildAchievements($arrAchievs, $intCount = 10){
			$this->game->new_object('bnet_armory', 'armory', array($this->config->get('uc_server_loc'), $this->config->get('uc_data_lang')));

			$arrAchieveTimes = $arrAchievs['achievementsCompletedTimestamp'];
			$arrAchievs		 = $arrAchievs['achievementsCompleted'];
			array_multisort($arrAchieveTimes, SORT_DESC, SORT_NUMERIC, $arrAchievs);
			$count = 0;
			$arrGuildAchievementsData = $this->game->obj['armory']->getdata('guild', 'achievements');

			$arrAchievsOut = array();
			foreach($arrAchievs as $key => $achievID){
				if ($count == $intCount) break;
				$count++;
				$achievData = $this->game->obj['armory']->achievement($achievID);
				if ($achievData){
					$intCategory = $this->game->obj['armory']->getCategoryForAchievement($achievID, $arrGuildAchievementsData);
					$strCategory = $this->game->obj['armory']->achievementIDMapping($intCategory);

					$arrAchievsOut[] = array(
						'name'	=> '<a href="'.$this->game->obj['armory']->bnlink('', $this->config->get('servername'), 'guild-achievements', $this->config->get('guildtag')).'/'.$strCategory.'">'.$achievData['title'].'</a>',
						'icon'	=> '<img class="gameicon" src="'.sprintf($this->strStaticIconUrl, $achievData['icon']).'" alt="" loading="lazy"/>',
						'desc'	=> $achievData['description'],
						'points'=> $achievData['points'],
						'date'	=> substr($arrAchieveTimes[$key], 0, -3),
					);
				}
			}
			return $arrAchievsOut;
		}

		/*
		 * parse the latest char achievements of armory
		 */
		public function parseLatestCharAchievements($chardata, $charname, $intCount = 10){
			$this->game->new_object('bnet_armory', 'armory', array($this->config->get('uc_server_loc'), $this->config->get('uc_data_lang')));

			$arrAchievs			= $chardata['achievements'];
			$arrAchieveTimes	= $arrAchievs['achievementsCompletedTimestamp'];
			$arrAchievs			= $arrAchievs['achievementsCompleted'];
			if(is_array($arrAchieveTimes)){
				array_multisort($arrAchieveTimes, SORT_DESC, SORT_NUMERIC, $arrAchievs);
			}
			$count = 0;
			$arrCharAchievementsData = $this->game->obj['armory']->getdata('character', 'achievements');

			$arrAchievsOut = array();
			if(is_array($arrAchievs)){
				foreach($arrAchievs as $key => $achievID){
					if ($count == $intCount) break;
					$count++;
					$achievData = $this->game->obj['armory']->achievement($achievID);
					if ($achievData){
						$class = ($achievData['accountWide'] == 1) ? 'accountwide' : '';

						$intCategory = $this->game->obj['armory']->getCategoryForAchievement($achievID, $arrCharAchievementsData);
						$strCategory = $this->game->obj['armory']->achievementIDMapping($intCategory);

						$arrAchievsOut[] = array(
							'name'	=> '<a href="'.$this->game->obj['armory']->bnlink($charname, $this->config->get('servername'), 'achievements').'/'.$strCategory.'" class="'.$class.'">'.$achievData['title'].'</a>',
							'icon'	=> '<img class="gameicon" src="'.sprintf($this->strStaticIconUrl, $achievData['icon']).'" alt="" loading="lazy"/>',
							'desc'	=> $achievData['description'],
							'points'=> $achievData['points'],
							'date'	=> substr($arrAchieveTimes[$key], 0, -3),

						);
					}
				}
			}

			return $arrAchievsOut;
		}

		/*
		 * parse the guild challenges of armory
		 */
		public function parseGuildChallenge($arrInput){
			$arrChallengeOut	= array();
			foreach($arrInput['challenge'] as $a_values){
				$a_groupout = array();
				foreach($a_values['groups'] as $a_groupid => $a_groups){
					$a_membersout = array();
					foreach($a_groups['members'] as $a_memid => $a_members){
						if(isset($a_members['character']['name']) && $a_members['character']['name'] != ''){
							$memberid = $this->pdh->get('member', 'id', array($a_members['character']['name']));
							$a_membersout[] = array(
								'name'			=> $a_members['character']['name'],
								'realm'			=> $a_members['character']['realm'],
								'guild'			=> $a_members['character']['guild'],
								'class'			=> $this->game->obj['armory']->ConvertID($a_members['character']['class'], 'int', 'classes'),
								'off_realm'		=> ($this->config->get('servername') != $a_members['character']['realm']) ? true : false,
								'memberid'		=> (isset($memberid) && $memberid > 0) ? $memberid : 0,
							);
						}
					}
					$a_groupout[] = array(
						'name'		=> $a_groups['ranking'],
						'medal'		=> $a_groups['medal'],
						'faction'	=> $a_groups['faction'],
						'date'		=> $this->time->user_date($this->time->fromformat($a_groups['date'], 1)),
						'time'		=> sprintf('%02d', $a_groups['time']['hours']).':'.sprintf('%02d', $a_groups['time']['minutes']).':'.sprintf('%02d', $a_groups['time']['seconds']),
						'members'	=> $a_membersout
					);
				}
				$arrChallengeOut[$a_values['map']['id']] = array(
					'name'		=> $a_values['map']['name'],
					'icon'		=> $a_values['map']['slug'],
					'time'		=> '',
					'group'		=> $a_groupout
				);
			}
			return $arrChallengeOut;
		}

		/*
		 * generate an array with the profession data
		 */
		public function professions($chardata){
			$professions = array();
			if (is_array($chardata['professions']['primary'])) {
				foreach ($chardata['professions']['primary'] as $k_profession => $v_profession){
					$akt = (int)$v_profession['rank'];
					$max = (int)$v_profession['max'];

					if($akt>$max){
						$max = $akt;
					}

					$professions[$k_profession] = array(
						'name'			=> $v_profession['name'],
						'icon'			=> $this->server_path."games/wow/profiles/professions/".(($v_profession['icon']) ? $v_profession['icon'] : '0').".jpg",
						'progressbar'	=> $this->jquery->progressbar('profession_'.$v_profession['id'], 0, array('completed' => $akt, 'total' => $max, 'text' => '%progress%'))
					);
				}
			}
			return $professions;
		}

		/*
		 * generate an array with the talent data
		 */
		public function talents($chardata){
			$talents = array();
			$arrValues = array(15, 30, 45, 60, 75, 90, 100);

			#d($chardata);

			foreach($chardata as $key => $val){
				if(is_numeric($key)){
					$spezialisation = [];

					if(isset($val['talents'])){
						foreach($val['talents'] as $key => $v_spezialisation){

							$arrSpell = $this->game->obj['armory']->spell($v_spezialisation['spell_tooltip']['spell']['id'], true);

							$spezialisation[$v_spezialisation['tier_index']] = array(
									'name'			=> $v_spezialisation['talent']['name'],
									'description'	=> $v_spezialisation['spell_tooltip']['description'],
									'icon'			=> $arrSpell['assets'][0]['value'],
									'value'			=> $arrValues[$v_spezialisation['tier_index']],
							);
						}
					}

					$talents[] = array(
							'selected'		=> (count($spezialisation)) ? '1' : '0',
							'name'			=> (isset($val['specialization']['name']) && $val['specialization']['name']) ? $val['specialization']['name'] : $this->game->glang('not_assigned'),
							'icon'			=> $this->game->obj['armory']->talentIcon(((isset($v_talents['spec']['icon']) && $v_talents['spec']['icon']) ? $v_talents['spec']['icon'] : 'inv_misc_questionmark')),
							'talents'		=> $spezialisation
					);

				}

			}

			return $talents;
		}

		public function ParseCharNews($chardata, $amount=10){
			$charfeed = array();
			if(is_array($chardata['feed'])){
				$ii = 0;
				foreach($chardata['feed'] as $d_charfeed){
					switch ($d_charfeed['type']){
						case 'ACHIEVEMENT':
							$charfeed[] = array(
								'type'		=> 'achievement',
								'timestamp'	=> $d_charfeed['timestamp']/ 1000,
								'title'		=> $d_charfeed['achievement']['title'],
								'points'	=> $d_charfeed['achievement']['points'],
								'icon'		=> sprintf($this->strStaticIconUrl, $d_charfeed['achievement']['icon']),
								'hero'		=> ($d_charfeed['featOfStrength'] == 1) ? true : false,
								'achievementID' => $d_charfeed['achievement']['id'],
								'accountWide'=> ($d_charfeed['achievement']['accountWide'] == 1) ? true : false,
							);
						break;
						case 'BOSSKILL':
							$charfeed[] = array(
								'type'		=> 'bosskill',
								'timestamp'	=> $d_charfeed['timestamp']/ 1000,
								'title'		=> $d_charfeed['achievement']['title'],
								'icon'		=> sprintf($this->strStaticIconUrl, $d_charfeed['achievement']['icon']),
								'quantity'  => $d_charfeed['quantity'],
							);
						break;
						case 'CRITERIA':
							$charfeed[] = array(
								'type'		=> 'criteria',
								'timestamp'	=> $d_charfeed['timestamp']/ 1000,
								'criteria'	=> $d_charfeed['criteria']['description'],
								'title'		=> $d_charfeed['achievement']['title'],
								'achievementID' => $d_charfeed['achievement']['id'],
								'icon'		=> sprintf($this->strStaticIconUrl, $d_charfeed['achievement']['icon'])
							);
						break;
						case 'LOOT':
							$charfeed[] = array(
								'type'		=> 'item',
								'timestamp'	=> $d_charfeed['timestamp']/ 1000,
								'itemid'	=> $d_charfeed['itemId']
							);
						break;
					}
					// end parse process when amount is reached
					$ii++;
					if($ii == $amount){ break; }
				}
			}
			return $charfeed;
		}

		/**
		 * Return an array(left,right,button) with the wow char icons
		 *
		 * @param array $data
		 * @param string $member_name
		 * @return array
		 */
		public function getItemArray($data, $member_name, $icons_size = 53){
			$d_itemoptions = array(
				'head'		=> array('position' => 'left',		'bnetid' => '0'),
				'neck'		=> array('position' => 'left',		'bnetid' => '1'),
				'shoulder'	=> array('position' => 'left',		'bnetid' => '2'),
				'back'		=> array('position' => 'left',		'bnetid' => '14'),
				'chest'		=> array('position' => 'left',		'bnetid' => '4'),
				'shirt'		=> array('position' => 'left',		'bnetid' => '3'),
				'tabard'	=> array('position' => 'left',		'bnetid' => '18'),
				'wrist'		=> array('position' => 'left',		'bnetid' => '8'),

				'hands'		=> array('position' => 'right',		'bnetid' => '9'),
				'waist'		=> array('position' => 'right',		'bnetid' => '5'),
				'legs'		=> array('position' => 'right',		'bnetid' => '6'),
				'feet'		=> array('position' => 'right',		'bnetid' => '7'),
				'finger_1'	=> array('position' => 'right',		'bnetid' => '10'),
				'finger_2'	=> array('position' => 'right',		'bnetid' => '11'),
				'trinket_1'	=> array('position' => 'right',		'bnetid' => '12'),
				'trinket_2'	=> array('position' => 'right',		'bnetid' => '13'),

				'main_hand'	=> array('position' => 'bottom_left',	'bnetid' => '15'),
				'off_hand'	=> array('position' => 'bottom_right',	'bnetid' => '16')
			);

			// reset the array
			$a_items = array();
			$arrItemsBySlot = array();

			foreach($data as $arrItem){
				$slot = utf8_strtolower($arrItem['slot']['type']);
				$arrItemsBySlot[$slot] = $arrItem;
			}

			// fill the item slots with data
			foreach ($d_itemoptions as $slot=>$options){
				$arrItem = isset($arrItemsBySlot[$slot]) ? $arrItemsBySlot[$slot] : 0;
				$item_id_full	= $this->game->obj['armory']->armory2itemid($arrItem['item']['id'], $arrItem['context'], $arrItem['bonus_list'], $arrItem['level']['value']);
				$itemname = $arrItem['name'];

				if($arrItem === 0){
					$a_items[$options['position']][] =
					array(
						'itemid' => $item_id_full,
						'icon' => "<img src='".$this->server_path."games/wow/profiles/slots/".$options['bnetid'].".png' height='$icons_size' width='$icons_size' alt='' class='itt-icon' />",
						'level' => 0,
						'name' => "",
						'quality' => 0,
						'name_tt' => ""
					);
				} else {
					$a_items[$options['position']][] =
					array(
							'itemid' => $item_id_full,
							'icon' => infotooltip($itemname, $item_id_full, false, 0, $icons_size, false, array(false, $member_name, $slot), " ", '', true),
							'level' => $arrItem['level']['value'],
							'name' => $itemname,
							'quality' => $arrItem['quality']['type'],
							'name_tt' => infotooltip($itemname, $item_id_full, false, 0, false, true, array(false, $member_name, $slot), false, '', true)
					);
				}

			}

			return $a_items;
		}

		public function ParseRaidProgression($chardata){
			#d($chardata);

			$a_raidprogress = array();

			#d($chardata);

			foreach($chardata as $expansion){
				$a_progress = array();

				foreach($expansion['instances'] as $v_progression){
					$a_category		= array_keys(search_in_array($v_progression['instance']['id'], $this->ArrInstanceCategories));
					$v_progresscat	= (isset($a_category[0])) ? $a_category[0] : 'default';

					$v_progresscat = $expansion['name'];

					// parse the bosses
					$a_bosses = array('progress_normal' => 0, 'progress_lfr' => 0, 'progress_heroic' => 0, 'progress_mythic' => 0, 'runs_normal'=>0, 'runs_heroic'=>0, 'runs_mythic'=>0, 'runs_lfr');

					$intBosses = 0;
					foreach($v_progression['modes'] as $arrMode){
						$difficulty = strtolower($arrMode['difficulty']['type']);
						if(strpos($difficulty, 'legacy') === 0) $difficulty = 'normal';

						$a_bosses['progress_'.$difficulty] += $arrMode['progress']['completed_count'];
						$a_bosses['runs_'.$difficulty] = $arrMode['progress']['total_count'];
						$intBosses += count($arrMode['progress']['encounters']);

						foreach($arrMode['progress']['encounters'] as $encounter){
							$a_bosses['bosses'][$encounter['encounter']['id']][$difficulty] = $encounter['completed_count'];
							$a_bosses['bosses'][$encounter['encounter']['id']]['_name'] = $encounter['encounter']['name'];

						}
					}

					$arrExpansion = $this->game->obj['armory']->instance($v_progression['instance']['id'], true);

					$a_progress[$v_progression['instance']['id']] = array(
							'id'			=> $v_progression['instance']['id'],
							'name'			=> $v_progression['instance']['name'],
							'icon'			=> $arrExpansion['assets'][0]['value'],

							'bosses'		=> $a_bosses['bosses'],
							'bosses_max'	=> max(array($a_bosses['runs_normal'], $a_bosses['runs_heroic'], $a_bosses['runs_mythic'], $a_bosses['runs_lfr'])),
							'bosses_normal'	=> $a_bosses['progress_normal'],
							'bosses_heroic'	=> $a_bosses['progress_heroic'],
							'bosses_mythic'	=> $a_bosses['progress_mythic'],
							'bosses_lfr'	=> $a_bosses['progress_lfr'],

							'runs_normal'	=> $a_bosses['runs_normal'],
							'runs_heroic'	=> $a_bosses['runs_heroic'],
							'runs_mythic'	=> $a_bosses['runs_mythic'],
							'runs_lfr'		=> $a_bosses['runs_lfr'],

					);

				}

				$a_raidprogress[] = array(
					'id' => $expansion['expansion']['id'],
					'name' => $expansion['expansion']['name'],
					'raids' => $a_progress
				);

			}
			return array_reverse($a_raidprogress);
		}
	}#class
}
?>
