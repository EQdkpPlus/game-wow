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

	$this->jquery->Tab_header('char1_tabs');

	// init infotooltip
	infotooltip_js();

	// Add css & JS Code
	$this->tpl->add_css("
		.uc_logo_Alliance {
			background:url('".$this->server_path."games/wow/profiles/factions/alliance-icon.png');
		}
		.uc_logo_Horde {
			background:url('".$this->server_path."games/wow/profiles/factions/horde-icon.png');
		}
		.uc_name {
			text-align: left;
			color: white;
			font-weight: bold;
			font-size: 20px;
			padding-bottom: 0px;
			padding-left: 6px;
			z-index: 9;
		}
		.uc_subname {
			text-align: left;
			color: white;
			font-size: 11px;
			margin-top:2px;
			margin-left:0px;
			padding-left: 6px;
			z-index: 10;
		}

		ul#wow_icons_left img, ul#wow_icons_right img, ul#wow_icons_bottom_right img,  ul#wow_icons_bottom_left img{
			box-shadow: 0 0 8px black;
			border-radius: 4px;
		}

		.uc_nametd {
			border-bottom: 1px solid white;
		}
		ul#wow_icons_left {
			margin: 5px; padding: 0;
		}
		ul#wow_icons_left li {
			list-style: none;
			padding: 3px; margin-bottom: 3px;
			padding-top: 10px;
		}
		ul#wow_icons_right {
			margin: 5px; padding: 0;
		}
		ul#wow_icons_right li {
			list-style: none;
			padding: 3px; margin-bottom: 3px;
			padding-top: 10px;
		}
		ul#wow_icons_bottom_left, ul#wow_icons_bottom_right {
			margin: 0; padding: 0;
			padding-top: 50px;
		}
		ul#wow_icons_bottom_left li, ul#wow_icons_bottom_right li {
			list-style: none;
			display: inline;
			padding: 3px; margin-left: 3px;
		}

		.wow_achiev_bar{
			width: 32%;
			float: left;
			padding: 5px;
			cursor: pointer;
		}

		#bar_total {
			display: block;
			width: 98%
		}

		#profile {
			padding: 6px;
			position: relative;
		}

		#profile_charicon {
			height:100px;
			float: left;
		}

		#profile_charname {
			font-size: 56px;
			font-weight: bold;
			letter-spacing: -0.05em;
			line-height: 1.1em;
			margin-left: 8px;
			vertical-align: top;
			height:60px;
			float: left;
		}

		#profile_titel_guild {
			height: 60px;
			float: left;
			padding-left: 16px;
			padding-top: 10px;
			font-size: 14px;
			line-height: 25px;
		}

		.profile_guild {
			font-size: 20px;
			color: #FFB100;
			line-height: 15px;
		}

		#profile_charinfos {
			float:left;
			font-size: 15px;
			margin-left: 8px;
			margin-top: -4px;
		}

		.profile_charname{
			top: 0;
			right: 0;
		}
		.profile_chartitle{
			font-size: 17px;
			font-weight: bolder;
			color: #FFCC33;
		}
		.profile_guildname{
			font-size: 16px;
			font-weight: bolder;
			color: #FFCC33;
		}
		.profile_charpoints{
			font-size: 17px;
			font-weight: bolder;
			color: #white;
			position:relative;
			top:0px;
			left:0px;
		}
		.profile_itemlevel{
			position: absolute;
			bottom: 0;
			right: 6px;
		}
		.profile_itemlevel_avg{
			font-size: 36px;
			font-weight: bold;
			letter-spacing: -0.05em;
			line-height: 0.8em;
			margin-right: 4px;
			vertical-align: top;
			height: 60px;
			float: left;
		}
		.profile_itemlevel_txt {
			display: inline-block;
		}

		.profile_itemlevel_avgtxt, .profile_itemlevel_eq{
			margin-left: 6px;
		}
		.raideventicon { background: url('".$this->server_path."games/wow/profiles/events/raid-icons.jpg') no-repeat; width: 59px; height: 59px; padding: 0 !important;}
		.raideventicon.id2717 { background-position:        0 0; }
		.raideventicon.id2677 { background-position:    -61px 0; }
		.raideventicon.id3429 { background-position:   -122px 0; }
		.raideventicon.id3428 { background-position:   -183px 0; }
		.raideventicon.id3457 { background-position:   -244px 0; }
		.raideventicon.id3923 { background-position:   -305px 0; }
		.raideventicon.id3836 { background-position:   -366px 0; }
		.raideventicon.id3607 { background-position:   -488px 0; }
		.raideventicon.id3845 { background-position:   -549px 0; }
		.raideventicon.id3606 { background-position:   -610px 0; }
		.raideventicon.id3959 { background-position:   -671px 0; }
		.raideventicon.id4075 { background-position:   -732px 0; }
		.raideventicon.id3456 { background-position:   -793px 0; }
		.raideventicon.id4493 { background-position:   -854px 0; }
		.raideventicon.id4603 { background-position:   -915px 0; }
		.raideventicon.id4500 { background-position:   -976px 0; }
		.raideventicon.id4273 { background-position:  -1037px 0; }
		.raideventicon.id4722 { background-position:  -1098px 0; }
		.raideventicon.id2159 { background-position:  -1159px 0; }
		.raideventicon.id4812 { background-position:  -1220px 0; }
		.raideventicon.id4987 { background-position:  -1281px 0; }
		.raideventicon.id5600 { background-position:  -1342px 0; }
		.raideventicon.id5334 { background-position:  -1403px 0; }
		.raideventicon.id5094 { background-position:  -1464px 0; }
		.raideventicon.id5638 { background-position:  -1525px 0; }
		.raideventicon.id5723 { background-position:  -1586px 0; }
		.raideventicon.id5892 { background-position:  -1647px 0; }
		.raideventicon.id6125 { background-position:  -1708px 0; }
		.raideventicon.id6297 { background-position:  -1769px 0; }
		.raideventicon.id6067 { background-position:  -1830px 0; }
		.raideventicon.id6622 { background-position:  -1891px 0; }
		.raideventicon.id6738 { background-position:  -1952px 0; }
		.raideventicon.id6967 { background-position:  -2013px 0; }
		.raideventicon.id6996 { background-position:  -2074px 0; }
		.raideventicon.id7545 { background-position:  -2135px 0; }
		.raideventicon.id8026 { background-position:  -2196px 0; }
		.raideventicon.id8025 { background-position:  -2257px 0; }
		.raideventicon.id8440 { background-position:  -2318px 0; }
		.raideventicon.id8524 { background-position:  -2379px 0; }
		.raideventicon.id8638 { background-position:  -2440px 0; }

		#wow_icons_left .q img, #wow_icons_right .q img, #wow_icons_bottom_right .q img, #wow_icons_bottom_left .q img {
			border: 1px solid #ffd100;
		}
		#wow_icons_left .q0 img, #wow_icons_right .q0 img, #wow_icons_bottom_right .q0 img, #wow_icons_bottom_left .q0 img {
			border: 1px solid #9d9d9d;
		}
		#wow_icons_left .q1 img, #wow_icons_right .q1 img, #wow_icons_bottom_right .q1 img, #wow_icons_bottom_left .q1 img {
			border: 1px solid #ffffff;
		}
		#wow_icons_left .q2 img, #wow_icons_right .q2 img, #wow_icons_bottom_right .q2 img, #wow_icons_bottom_left .q2 img {
			border: 1px solid #1eff00;
		}
		#wow_icons_left .q3 img, #wow_icons_right .q3 img, #wow_icons_bottom_right .q3 img, #wow_icons_bottom_left .q3 img {
			border: 1px solid #0070dd;
		}
		#wow_icons_left .q4 img, #wow_icons_right .q4 img, #wow_icons_bottom_right .q4 img, #wow_icons_bottom_left .q4 img {
			border: 1px solid #a335ee;
		}
		#wow_icons_left .q5 img, #wow_icons_right .q5 img, #wow_icons_bottom_right .q5 img, #wow_icons_bottom_left .q5 img {
			border: 1px solid #ff8000;
		}
		#wow_icons_left .q6 img, #wow_icons_right .q6 img, #wow_icons_bottom_right .q6 img, #wow_icons_bottom_left .q6 img {
			border: 1px solid #ff0000;
		}
		#wow_icons_left .q7 img, #wow_icons_right .q7 img, #wow_icons_bottom_right .q7 img, #wow_icons_bottom_left .q7 img {
			border: 1px solid #E5CC80;
		}
		#wow_icons_left .q8 img, #wow_icons_right .q8 img, #wow_icons_bottom_right .q8 img, #wow_icons_bottom_left .q8 img {
			border: 1px solid #ffff98;
		}

		.accountwide { color: #00AEFF !important; }

		.icon-frame {
			background-color: #000000;
			background-position: 1px 1px;
			background-repeat: no-repeat;
			border-color: #B1B2B4 #434445 #2F3032;
			border-image: none;
			border-left: 1px solid #434445;
			border-radius: 3px 3px 3px 3px;
			border-right: 1px solid #434445;
			border-style: solid;
			border-width: 1px;
			padding: 1px;
			border-radius:4px; /* CSS3 */
			//display: inline-block !important;
		}
		.icon-frame.frame-14 { height: 14px; width: 14px; }
		.icon-frame.frame-18 { height: 18px; width: 18px; }
		.icon-frame.empty {
			border-color: black;
			border-radius: 2px 2px 2px 2px;
			box-shadow: 0 0 0 0 transparent;
			color: #572E1B;
			font-family: Arial,sans-serif;
			font-size: 12px;
			font-weight: bold;
			line-height: 18px;
			text-align: center;
			vertical-align: top;
			display: inline-block !important;
		}

		.talenttab_select{ margin-left: 8px; }
		.talenttab_name{ margin-left: 4px; }

		.profession-name { padding-left: 4px; }
		.profession-icon { margin-left: 4px; }
		.profession-row { height: 30px; }

		.wowsvg {
			fill: currentColor;
		}

		.wow-char-left, .icon-health {
			color: #fff;
		}


.Media-icon {
    width: 48px;
    height: 48px;
}

.Media-icon {
    display: inline-block;
}
	
.wow-health-value {
	text-transform: uppercase;
	padding: 3px;
}

.wow-health-name {
	font-weight: bold;
	padding: 3px;
	padding-bottom: 6px;
}


.wow-profilers-container {
	float: right;
}

.wow-profilers-container li {
	list-style: none;
	float: left;
}

.wow-profilers-container img {
	max-height: 40px;
}

.wow-char-container {
	max-width:1140px;
}

#talent_tabs .icon-frame {
	display: inline-block;
}

.wow-char-talents-spec {
	padding: 3px;
}

.wow-char-talents-spec-value {
	font-weight: bold;
	min-width: 30px;
	display: inline-block;
}

.wow-char-talents-spec-desc {
	font-style: italic;
	margin-bottom: 4px;
}

.wowsvg.icon-health {
	color: #27cc4e;
}

.wowsvg.icon-mana {
	color: #1c8aff;
}

.wowsvg.icon-intellect {
color: #d26cd1;
}

.wowsvg.icon-stamina {
color: #ff8b2d;
}

.wowsvg.icon-crit {
color: #e01c1c;
}

.wowsvg.icon-haste {
color: #0ed59b;
}

.wowsvg.icon-mastery {
color: #9256ff;
}

.wowsvg.icon-versatibility {
color: #bfbfbf;
}

.wowsvg.icon-sword {
width:24px;
}


		@media all and (max-width: 1100px) {
			.responsive .profile_itemlevel {
				position: relative;
				clear: both;
				float: none;
			}

			.wow-char-left, .wow-char-right {
				float: none;
			}

			.wow-char-left > div, .wow-char-right {
				float: none;
				width: 100% !important;
			}

			.wow-char-right {
				max-width: max-content !important;
			}

			.wow_achiev_bar {
				float: none;
				width: 98%;
			}

			#wow_icons_bottom_left .floatRight  {
				float: left;
			}
		}
	");

	// Armory based information
	$this->game->new_object('bnet_armory', 'armory', array(unsanitize($this->config->get('uc_server_loc')), $this->config->get('uc_data_lang')));
	$member_servername	= unsanitize($this->pdh->get('member', 'profile_field', array($this->url_id, 'servername')));
	$servername			= ($member_servername != '') ? $member_servername : unsanitize($this->config->get('servername'));

	$chardata			= $this->game->obj['armory']->character(unsanitize($member['name']), $servername);

	if($this->config->get('game_importer_apikey') != '' && $this->config->get('servername') != '' && !isset($chardata['status'])){

		// profilers
		$a_profilers	= array(
			1	=> array(
					'icon'	=> $this->server_path.'games/wow/profiles/profilers/wowicon.png',
					'name'	=> 'worldofwarcraft.com',
					'url'	=> $this->game->obj['armory']->bnlink(unsanitize($member['name']), unsanitize($servername), 'char')
			),
			2	=> array(
					'icon'	=> $this->server_path.'games/wow/profiles/profilers/askmrrobot.png',
					'name'	=> 'AskMrRobot.com',
					'url'	=> $this->game->obj['armory']->bnlink(unsanitize($member['name']), unsanitize($servername), 'askmrrobot')
			),
		);

		$this->jquery->Tab_header('talent_tabs');
		$this->jquery->Tab_header('achievement_tabs');
		$this->tpl->add_js("
			$('#base').hide();
			$('#melee').hide();
			$('#range').hide();
			$('#spell').hide();
			$('#defenses').hide();
			$('#char_infos').change(function(){
			if(this.value == 'all'){
				$('#boxes').children().show();
			}else{
				$('#' + this.value).show().siblings().hide();}
			});
			$('#char_infos').change();
		", 'docready');

		$items = $this->game->callFunc('getItemArray', array($chardata['items'], unsanitize($member['name'])));

		// talents
		$a_talents = $this->game->callFunc('talents', array($chardata));
		
		$arrSelectedTalents = array();

		foreach ($a_talents as $id_talents => $v_talents){
			if(count($v_talents['talents']) === 0) continue;
			
			if(($v_talents['selected'] == '1')){
				$arrSelectedTalents = $v_talents;
			}
			
			$this->tpl->assign_block_vars('talents', array(
				'ID'			=> $id_talents,
				'SELECTED'		=> ($v_talents['selected'] == '1') ? true : false,
				'ICON'			=> $v_talents['icon'],
				'NAME'			=> $v_talents['name'],
				'ROLE'			=> strtolower($v_talents['role']),
				'DESCRIPTION'	=> $v_talents['desc'],
			));

			// talent specialization
			for ($i_ts = 0; $i_ts < 7; $i_ts ++) {
				$this->tpl->assign_block_vars('talents.special', array(
					'NAME'			=> (isset($v_talents['talents'][$i_ts]) && $v_talents['talents'][$i_ts]['name']) ? $v_talents['talents'][$i_ts]['name'] : $this->game->glang('empty'),
					'ICON'			=> (isset($v_talents['talents'][$i_ts]) && $v_talents['talents'][$i_ts]['icon']) ? '<img src="'.$v_talents['talents'][$i_ts]['icon'].'" class="gameicon" />' : '<div class="icon-frame frame-18 empty"></div>',
					'DESCRIPTION'	=> (isset($v_talents['talents'][$i_ts]) && $v_talents['talents'][$i_ts]['description']) ? $v_talents['talents'][$i_ts]['description'] : false,
					'VALUE'			=> (isset($v_talents['talents'][$i_ts]) && $v_talents['talents'][$i_ts]['value']) ? $v_talents['talents'][$i_ts]['value'] : '  ',
				));
			}
		}
		
		//Calculate Infos to talents link
		//numbers
		$numbers = $arrSelectedTalents['calcTalent'];
		$newNumbers = "";
		$strlen = strlen( $numbers );
		for( $i = 0; $i < $strlen; $i++ ) {
			$char = (int)$numbers[$i];
			$newNumbers .= (string)$char+1;
		}
		
		$arrSelectedTalents['calcTalent'] = $newNumbers;
		$strFirstTalentIcon = $arrSelectedTalents['background'];
		$strFilename = pathinfo($strFirstTalentIcon, PATHINFO_FILENAME);
		$arrParts = explode('-', $strFilename);
		$arrSelectedTalents['class'] = $arrParts[1];
		$arrSelectedTalents['type'] = $arrParts[2];
		
		// talents & professions
		$this->tpl->assign_array('bnetlinks',	$this->game->obj['armory']->a_bnlinks(unsanitize($member['name']),unsanitize($servername), $chardata['guild']['name'], $arrSelectedTalents));
		$this->tpl->assign_array('items',		$items);
		

		// professions
		$a_professions = $this->game->callFunc('professions', array($chardata));
		foreach ($a_professions as $v_professions){
			$this->tpl->assign_block_vars('professions', array(
				'ICON'			=> $v_professions['icon'],
				'NAME'			=> $v_professions['name'],
				'BAR'			=> $v_professions['progressbar'],
			));
		}


		// the profilers
		foreach ($a_profilers as $v_profilers){
			$this->tpl->assign_block_vars('profilers', array(
				'IMG'			=> $v_profilers['icon'],
				'ALT'			=> $v_profilers['name'],
				'URL'			=> $v_profilers['url'],
			));
		}

		// Character News Feed
		$d_charfeed = $this->game->callFunc('ParseCharNews', array($chardata));
		$cnf_output = '';
		if (is_array($d_charfeed)) {
			$strStaticIconUrl			= $this->config->get('itt_icon_small_loc').'%s'.$this->config->get('itt_icon_ext');
			$arrCharacterAchievements	= $this->game->obj['armory']->getdata();
			foreach ($d_charfeed as $v_charfeed){
				switch ($v_charfeed['type']){
						case 'achievement':
							$achievCat = $this->game->obj['armory']->getCategoryForAchievement((int)$v_charfeed['achievementID'], $arrCharacterAchievements);
							$achievCatName =  $this->game->obj['armory']->achievementIDMapping($achievCat);
							$bnetLink = $this->game->obj['armory']->bnlink($chardata['name'], unsanitize($servername), 'achievements', unsanitize($this->config->get('guildtag'))).'/'.$achievCatName;
							$class='';
							if ($v_charfeed['accountWide']) $class = 'accountwide';

							$cnf_output = ($v_charfeed['hero']) ? sprintf($this->game->glang('charnf_achievement_hero'), '<a href="'.$bnetLink.'" class="'.$class.'">'.$v_charfeed['title'].'</a>') : sprintf($this->game->glang('charnf_achievement'), '<a href="'.$bnetLink.'" class="'.$class.'">'.$v_charfeed['title'].'</a>', $v_charfeed['points']);

						break;
						case 'bosskill':
							$cnf_output = sprintf($this->game->glang('charnf_bosskill'), $v_charfeed['quantity'], $v_charfeed['title']);
						break;
						case 'criteria':
							$achievCat = $this->game->obj['armory']->getCategoryForAchievement((int)$v_charfeed['achievementID'], $arrCharacterAchievements);
							$achievCatName =  $this->game->obj['armory']->achievementIDMapping($achievCat);
							$bnetLink = $this->game->obj['armory']->bnlink($chardata['name'], unsanitize($servername), 'achievements', unsanitize($this->config->get('guildtag'))).'/'.$achievCatName;

							$cnf_output = sprintf($this->game->glang('charnf_criteria'), '<b>'.$v_charfeed['criteria'].'</b>', '<a href="'.$bnetLink.'">'.$v_charfeed['title'].'</a>');
						break;
						case 'item':
							$itemData = $this->game->obj['armory']->item($v_charfeed['itemid']);
							$item = infotooltip($itemData['name'], $v_charfeed['itemid'], false, false, false, true, array(unsanitize($servername), $chardata['name']));
							$cnf_output = sprintf($this->game->glang('charnf_item'), $item);
							$v_charfeed['icon'] = sprintf($strStaticIconUrl, $itemData['icon']);
						break;
				}
				$this->tpl->assign_block_vars('charfeed', array(
					'TEXT'	=> $cnf_output,
					'ICON'	=> $v_charfeed['icon'],
					'DATE'	=> $this->time->nice_date($v_charfeed['timestamp'], 60*60*24*7),
				));
			}
		}

		// item icons
		
		foreach ($items as $items_pos=>$v_items){
			foreach ($v_items as $slots){
				$this->tpl->assign_block_vars('itemicons_'.$items_pos, array(
						'ICON'	=> $slots['icon'],
						'LEVEL' => $slots['level'],
						'NAME'	 => $slots['name_tt'],
						'QUALITY' => (int)$slots['quality'],
				));
			}
		}
		$this->tpl->assign_array('itemlevel',		$items['itemlevel']);

		// boss progress
		$d_bossprogress		= $this->game->callFunc('ParseRaidProgression', array($chardata));
		if(is_array($d_bossprogress)){
			foreach($d_bossprogress as $v_progresscat=>$a_bossprogress){
				// skip the category if hidden
				$config_bk_hidden	= (is_array($this->config->get('profile_boskills_hide'))) ? $this->config->get('profile_boskills_hide') : array();
				if(in_array($v_progresscat, $config_bk_hidden)){ continue; }

				$this->tpl->assign_block_vars('bossprogress_cat', array(
					'NAME'	=> $this->game->glang('uc_achievement_tab_'.$v_progresscat),
					'ID'	=> $v_progresscat
				));

				$a_bossprogress =  array_reverse($a_bossprogress);
				foreach($a_bossprogress as $v_bossprogress){

					// build the tooltip
					$tt_bossprogress = "<div class='table'>
											<div class='tr'>
												<div class='td'></div>
												<div class='td'>".$this->game->glang('normalrun')."</div>
												<div class='td'>".$this->game->glang('heroicrun')."</div>
												<div class='td'>".$this->game->glang('mythicrun')."</div>
											</div>";
					foreach($v_bossprogress['bosses'] as $bosses){
						$tt_bossprogress .= "<div class='tr'>
												<div class='td nowrap'>".$bosses['name']."</div>
												<div class='td nowrap'>".(isset($bosses['normalKills']) ? $bosses['normalKills'] : 0)."</div>
												<div class='td nowrap'>".(isset($bosses['heroicKills']) ? $bosses['heroicKills'] : 0)."</div>
												<div class='td nowrap'>".(isset($bosses['mythicKills']) ? $bosses['mythicKills'] : 0)."</div>
											</div>";
					}
					$tt_bossprogress .= '</div>';

					// normal
					$bar_bc_normal		= $this->jquery->progressbar('bcnormal_'.$v_bossprogress['id'], 0, array('completed' => $v_bossprogress['bosses_normal'], 'total' => $v_bossprogress['bosses_max'], 'text' => '%progress% (%percentage%)'));

					// heroic
					$bar_bc_heroic		= $this->jquery->progressbar('bcheroic_'.$v_bossprogress['id'], 0, array('completed' => $v_bossprogress['bosses_heroic'], 'total' => $v_bossprogress['bosses_max'], 'text' => '%progress% (%percentage%)'));

					// mythic
					$bar_bc_mythic		= $this->jquery->progressbar('bcmythic_'.$v_bossprogress['id'], 0, array('completed' => $v_bossprogress['bosses_mythic'], 'total' => $v_bossprogress['bosses_max'], 'text' => '%progress% (%percentage%)'));

					$this->tpl->assign_block_vars('bossprogress_cat.bossprogress_val', array(
						'ID'		=> $v_bossprogress['id'],
						'NAME'		=> $v_bossprogress['name'],
						'BARS_TT'	=> $tt_bossprogress,
						'BARS_BAR'	=> $bar_bc_normal.$bar_bc_heroic.$bar_bc_mythic,
						'RUNS'		=> sprintf($this->game->glang('bossprogress_normalruns'), $v_bossprogress['runs_normal']).' | '.sprintf($this->game->glang('bossprogress_heroicruns'), $v_bossprogress['runs_heroic']).' | '.sprintf($this->game->glang('bossprogress_mythicruns'), $v_bossprogress['runs_mythic'])
					));
				}
			}
		}

		// achievements
		$a_achievements = $this->game->callFunc('parseCharAchievementOverview', array($chardata));
		foreach ($a_achievements as $id_achievements => $v_achievements){
			
			$strCategory = $this->game->obj['armory']->achievementIDMapping($id_achievements);
			
			$this->tpl->assign_block_vars('achievements', array(
				'NAME'	=> $v_achievements['name'],
				'BAR'	=> $this->jquery->progressbar('guildachievs_'.$id_achievements, 0, array('completed' => $v_achievements['completed'], 'total' => $v_achievements['total'], 'text' => '%progress% (%percentage%)')),
				'ID'	=> $id_achievements,
					'LINK'	=> ($id_achievements != 'total') ? $this->game->obj['armory']->bnlink($chardata['name'], register('config')->get('servername'), 'achievements').'/'.$strCategory : $this->game->obj['armory']->bnlink($chardata['name'], register('config')->get('servername'), 'achievements'),
			));
		}

		// latest achievements
		$a_latestAchievements = $this->game->callFunc('parseLatestCharAchievements', array($chardata,$chardata['name']));
		foreach ($a_latestAchievements as $v_latestAchievements){
			$this->tpl->assign_block_vars('latestachievements', array(
					'NAME'	=> $v_latestAchievements['name'],
					'ICON'	=> $v_latestAchievements['icon'],
					'DESC'	=> $v_latestAchievements['desc'],
					'POINTS'=> $v_latestAchievements['points'],
					'DATE'	=> $this->time->nice_date($v_latestAchievements['date'], 60*60*24*7),
			));
		}

		$this->tpl->assign_vars(array(
			'ARMORY'				=> 1,
			'CHARDATA_ICON'			=> $this->game->obj['armory']->characterIcon($chardata),
			'CHARACTER_IMG'			=> $this->game->obj['armory']->characterImage($chardata, 'big'),
			'CHARDATA_NAME'			=> $chardata['name'],
			'CHARDATA_GUILDNAME'	=> $chardata['guild']['name'],
			'CHARDATA_GUILDREALM'	=> ($member['servername'] && $member['servername'] != $chardata['guild']['realm']) ? $member['servername'] : $chardata['guild']['realm'],
			'CHARDATA_POINTS'		=> $chardata['achievementPoints'],
			'CHARDATA_TITLE'		=> $this->game->obj['armory']->selectedTitle($chardata['titles'], true),

			// Bars
			'HEALTH_VALUE'			=> $chardata['stats']['health'],
			'POWER_VALUE'			=> $chardata['stats']['power'],
			'POWER_TYPE'			=> $chardata['stats']['powerType'],
			'POWER_NAME'			=> $this->game->glang('uc_bar_'.$chardata['stats']['powerType']),
				
			'INTELLECT_VALUE'		=> $chardata['stats']['int'],
			'STAMINA_VALUE'			=> $chardata['stats']['sta'],
			'SPELCRIT_VALUE'		=> round($chardata['stats']['spellCrit'], 0).'%',
			'HASTE_VALUE'			=> round($chardata['stats']['hasteRatingPercent'], 0).'%',
			'MASTERY_VALUE'			=> round($chardata['stats']['mastery'], 0). '%',
			'VERSATILITY_VALUE'		=> round($chardata['stats']['versatilityDamageDoneBonus'], 0).'%',
			
			'CRIT_RATING'			=> $chardata['stats']['critRating'],
			'HASTE_RATING'			=> $chardata['stats']['hasteRating'],
			'MASTERY_RATING'		=> $chardata['stats']['masteryRating'],
			'VERSATILITY_RATING'	=> $chardata['stats']['versatility'],
		));

	// the non armory charview
	}else{
		$a_lang_profession = $this->game->get('professions');
		$a_professions = array(
			0	=> array(
				'icon'			=> $this->server_path."games/wow/profiles/professions/".(($member['prof1_name']) ? $member['prof1_name'] : '0').".jpg",
				'name'			=> $a_lang_profession[$member['prof1_name']],
				'progressbar'	=> $this->jquery->progressbar('profession1', 0, array('completed' => $member['prof1_value'], 'total' => 600, 'text' => '%progress%'))
			),
			1	=> array(
				'icon'			=> $this->server_path."games/wow/profiles/professions/".(($member['prof2_name']) ? $member['prof2_name'] : '0').".jpg",
				'name'			=> $a_lang_profession[$member['prof2_name']],
				'progressbar'	=> $this->jquery->progressbar('profession2', 0, array('completed' => $member['prof2_value'], 'total' => 600, 'text' => '%progress%'))
			)
		);
		foreach ($a_professions as $v_professions){
			$this->tpl->assign_block_vars('professions', array(
				'ICON'			=> $v_professions['icon'],
				'NAME'			=> $v_professions['name'],
				'BAR'			=> $v_professions['progressbar'],
			));
		}
		
		$this->tpl->assign_vars(array(
			'ARMORY'				=> 0,
			'CHARDATA_GUILDREALM'	=> $servername,
			'NO_SERVER_SET'			=> ($this->config->get('servername') != '') ? false : true,
			'CHARACTER_IMG'			=> $this->game->obj['armory']->characterIconSimple($this->game->obj['armory']->ConvertID($member['race'], 'int', 'races', true), ((strtolower($member['gender']) == 'female') ? '1' : '0')),
			'POWER_BAR_NAME'		=> ($this->game->glang('uc_bar_'.$member['second_name'])) ? $this->game->glang('uc_bar_'.$member['second_name']) : $member['second_name'],
			'ERRORMSG_BNET'			=> sprintf($this->game->glang('no_armory'), $chardata['reason']),
			'CHARDATA_NAME'			=> $member['name'],
		));
	}
?>