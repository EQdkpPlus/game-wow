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

	// Add css code:
	$this->tpl->add_css("
		#guild_header_wrap {
			display: flex;
			-webkit-box-align: center;
   			-ms-flex-align: center;
    		align-items: center;
		}

		#guild_data_wrapper {
			padding: 0 20px;
   			border-left: 1px solid rgba(0,0,0,.2);
			align-self: stretch;
    		overflow: hidden;
		}

		#guild_emblem, #guild_emblem img { 
			height: 100px;
		}

		#guild_name_wrapper {
			padding: 0 20px;
		}		

		#guild_name {
			font-size: 2em; 
			font-weight:bold;
		}

		#guild_realm {
			padding-top: 5px;
			font-size: 1.2em; 
			color: #FFCC33 ;
		}

		#guild_data_wrapper {
			color: #FFCC33;
			-webkit-box-orient: vertical;
		    -webkit-box-direction: normal;
		    -ms-flex-direction: column;
		    flex-direction: column;
		    -webkit-box-pack: center;
		    -ms-flex-pack: center;
		    justify-content: center;
		    display: flex;
		}

		.guild_data_container svg {
			width: 16px;height: 16px;fill: currentColor;
		}
		
		.guild_data_container {
			margin-right: 20px;
			display: flex;
		}

		#guild_data_wrapper > div {
			display: flex;
		}

		#bar_15088, #bar_15077, #bar_15078, #bar_15079, #bar_15080, #bar_15089, #bar_15093{
			width: 31%;
			float: left;
			padding: 5px;
			cursor: pointer;
		}
	");
	$this->tpl->css_file($this->path.'games/wow/roster/challenge.css');

# Armory Stuff
$guilddata = false;
if($this->config->get('servername') && $this->config->get('uc_server_loc')){
	$this->game->new_object('bnet_armory', 'armory', array(unsanitize($this->config->get('uc_server_loc')), $this->config->get('uc_data_lang')));
	$guilddata = $this->game->obj['armory']->guild($this->config->get('guildtag'), $this->config->get('servername'));
	
	$this->tpl->assign_array('guilddata', $guilddata);
	
	if ($guilddata && !isset($guilddata['code'])){
		infotooltip_js();
		
		//Guildnews
		//TODO rework for new API - but currently not available in API
		#$arrActivity = $this->game->obj['armory']->guildActivity($this->config->get('guildtag'), $this->config->get('servername'));
		
		/*
		$arrNews = register('pdc')->get('roster_wow.guildnews');
		if (!$arrNews){
			$arrNews = $this->game->callFunc('parseGuildnews', array($guilddata['news']));
			register('pdc')->put('roster_wow.guildnews', $arrNews, 3600);
		}
		
		foreach ($arrNews as $news){
			$this->tpl->assign_block_vars('guildnews', array(
				'TEXT'	=> $news['text'],
				'ICON'	=> '<img src="'.$news['icon'].'" alt="" />',
				'DATE'	=> register('time')->nice_date($news['date'], 60*60*24*7),
			));
		}
		*/
		
		//Achievements
		//TODO rework for new API
		#$arrAchievements = $this->game->obj['armory']->guildAchievements($this->config->get('guildtag'), $this->config->get('servername'));
		#d($arrAchievements);
		/*
		$arrAchievs = register('pdc')->get('roster_wow.guildachievs');
		if (!$arrAchievs){
			$arrAchievs = $this->game->callFunc('parseGuildAchievementOverview', array($guilddata['achievements']));
			 register('pdc')->put('roster_wow.guildachievs', $arrAchievs, 3600);
		}
		
		foreach ($arrAchievs as $id => $val){
			$this->tpl->assign_block_vars('guildachievs', array(
				'NAME'	=> $val['name'],
				'BAR'	=> $this->jquery->progressbar('guildachievs_'.$id, 0, array('completed' => $val['completed'], 'total' => $val['total'],'text' => '%progress% (%percentage%)')),
				'ID'	=> $id,
				'LINK'	=> ($id != 'total') ? $this->game->obj['armory']->bnlink('', register('config')->get('servername'), 'guild-achievements', register('config')->get('guildtag')).'#achievement#'.$id : '',
			));
		}
		*/
		//Latest Achievements
		#$arrLatestAchievs = register('pdc')->get('roster_wow.guildlatestachievs');
		/*
		if (!$arrLatestAchievs){
			$arrLatestAchievs = $this->game->callFunc('parseLatestGuildAchievements', array($arrAchievements['recent_events']));	
			d($arrLatestAchievs);
			#register('pdc')->put('roster_wow.guildlatestachievs', $arrLatestAchievs, 3600);
		}
		*/
		/*
		foreach ($arrLatestAchievs as $val){
			$this->tpl->assign_block_vars('latestachievs', array(
					'NAME'	=> $val['name'],
					'ICON'	=> $val['icon'],
					'DESC'	=> $val['desc'],
					'POINTS'=> $val['points'],
					'DATE'	=> register('time')->nice_date($val['date'], 60*60*24*7),
			));
		}
		
		// the challenges
		$arrChallenge = register('pdc')->get('roster_wow.challenge');
		if (!$arrChallenge){
			$arrChallenge = $this->game->callFunc('parseGuildChallenge', array($guilddata));
			register('pdc')->put('roster_wow.challenge', $arrChallenge, 3600);
		}

		foreach ($arrChallenge as $val){
			$this->tpl->assign_block_vars('challenges', array(
				'NAME'		=> $val['name'],
				'ICON'		=> $val['icon'],
				'TIME'		=> $val['time']
			));
			foreach ($val['group'] as $challgroups){
				$this->tpl->assign_block_vars('challenges.groups', array(
					'NAME'		=> $challgroups['name'],
					'TIME'		=> $challgroups['time'],
					'DATE'		=> $challgroups['date'],
					'MEDAL'		=> strtolower($challgroups['medal']),
				));
				
				foreach ($challgroups['members'] as $chalmember){
					$this->tpl->assign_block_vars('challenges.groups.members', array(
						'NAME'			=> $chalmember['name'],
						'OFF_REALM'		=> ($chalmember['memberid'] == 0) ? true : false,
						'BNETLINK'		=> $this->game->obj['armory']->bnlink($chalmember['name'], $chalmember['realm']),
						'CLASSID'		=> $chalmember['class'],
						'SHOW_LINK'		=> ($chalmember['memberid'] > 0) ? true : false,
						'MEMBERID'		=> $chalmember['memberid'],
						'MEMBER_LINK'	=> $this->pdh->get('member', 'memberlink', array($chalmember['memberid'], $this->routing->simpleBuild('character'), '', true)),
					));
				}
			}
		}
		*/
		
		// the tab things
		$this->jquery->Tab_header('wow_roster');
		$this->tpl->assign_vars(array(
			'S_ARMORY_INFO' => true,
		));
	}
}

$faction = ($this->config->get('faction')) ? $this->config->get('faction') : 'alliance';

#$strTabard = $this->game->obj['armory']->guildTabard($guilddata['emblem'], $guilddata['side'], $guilddata['name'], 180);

$this->tpl->assign_vars(array(
		'FACTION'		=> $faction,
		'REALM'			=> $this->config->get('servername'),
		'REGION'		=> strtoupper($this->config->get('uc_server_loc')),
		'GUILD'			=> $this->config->get('guildtag'),
		'ACHIEV_POINTS'	=> (isset($guilddata['achievement_points'])) ? $guilddata['achievement_points'] : 0,
		'L_SKILLS'		=> $this->game->glang('skills'),
		'L_ACHIEVEMENT_POINTS'	=> $this->game->glang('achievement_points'),
		'TABARD'		=> $this->server_path.'games/wow/guild/tabard_'.$faction.'.png',
));

$this->hptt_page_settings = $this->pdh->get_page_settings('roster', 'hptt_roster');

if ($this->config->get('roster_classorrole') == 'role'){
	$members = $this->pdh->aget('member', 'defaultrole', 0, array($this->pdh->get('member', 'id_list', array($this->skip_inactive, $this->skip_hidden, true, $this->skip_twinks))));
	$arrRoleMembers = array();
	foreach ($members as $memberid => $defaultroleid){
		if ((int)$defaultroleid == 0){
			$arrAvailableRoles = array_keys($this->pdh->get('roles', 'memberroles', array($this->pdh->get('member', 'classid', array($memberid)))));
			if (isset($arrAvailableRoles[0])) $arrRoleMembers[$arrAvailableRoles[0]][] = $memberid;
		} else {
			$arrRoleMembers[$defaultroleid][] = $memberid;
		}
	}
	
	foreach ($this->pdh->aget('roles', 'name', 0, array($this->pdh->get('roles', 'id_list', array()))) as $key => $value){
		if ($key == 0) continue;

		$hptt = $this->get_hptt($this->hptt_page_settings, $arrRoleMembers[$key], $arrRoleMembers[$key], array('%link_url%' => $this->routing->simpleBuild('character'), '%link_url_suffix%' => '', '%with_twink%' => $this->skip_twinks, '%use_controller%' => true), 'role_'.$key);
		
		$this->tpl->assign_block_vars('class_row', array(
			'CLASS_NAME'	=> $value,
			'CLASS_ICONS'	=> $this->game->decorate('roles', $key),
			'CLASS_LEVEL'	=> 2,
			'ENDLEVEL'		=> true,
			'MEMBER_LIST'	=> $hptt->get_html_table($this->in->get('sort')),
		));
	}
	
	
} elseif($this->config->get('roster_classorrole') == 'raidgroup') {
	$arrMembers = $this->pdh->aget('member', 'defaultrole', 0, array($this->pdh->get('member', 'id_list', array($this->skip_inactive, $this->skip_hidden, true, $this->skip_twinks))));
	$arrRaidGroups = $this->pdh->get('raid_groups', 'id_list', array());
	foreach($arrRaidGroups as $intRaidGroupID){
		$arrGroupMembers = $this->pdh->get('raid_groups_members', 'member_list', array($intRaidGroupID));
				
		$hptt = $this->get_hptt($this->hptt_page_settings, $arrGroupMembers, $arrGroupMembers, array('%link_url%' => $this->routing->simpleBuild('character'), '%link_url_suffix%' => '', '%with_twink%' => $this->skip_twinks, '%use_controller%' => true), 'raidgroup_'.$intRaidGroupID);
		
		$this->tpl->assign_block_vars('class_row', array(
				'CLASS_NAME'	=> $this->pdh->get('raid_groups', 'name', array($intRaidGroupID)),
				'CLASS_ICONS'	=> '',
				'CLASS_LEVEL'	=> 2,
				'ENDLEVEL'		=> true,
				'MEMBER_LIST'	=> $hptt->get_html_table($this->in->get('sort')),
		));
	}
	
} elseif($this->config->get('roster_classorrole') == 'rank') {
	
	$arrMembers = $this->pdh->get('member', 'id_list', array($this->skip_inactive, $this->skip_hidden, true, $this->skip_twinks));
	$arrRanks = $this->pdh->get('rank', 'id_list', array());
	foreach($arrRanks as $intRankID){
		if($this->pdh->get('rank', 'is_hidden', array($intRankID))) continue;
			
		$arrGroupMembers = array();
		foreach($arrMembers as $intMemberID){
			if($this->pdh->get('member', 'rankid', array($intMemberID)) == $intRankID){
				$arrGroupMembers[] = $intMemberID;
			}
		}
		
		//Remove category if empty
		if(count($arrGroupMembers) === 0) continue;
			
		$hptt = $this->get_hptt($this->hptt_page_settings, $arrGroupMembers, $arrGroupMembers, array('%link_url%' => $this->routing->simpleBuild('character'), '%link_url_suffix%' => '', '%with_twink%' => $this->skip_twinks, '%use_controller%' => true), 'rank_'.$intRankID);

		$this->tpl->assign_block_vars('class_row', array(
				'CLASS_NAME'	=> $this->pdh->get('rank', 'name', array($intRankID)),
				'CLASS_ICONS'	=> $this->game->decorate('ranks', $intRankID),
				'CLASS_LEVEL'	=> 2,
				'ENDLEVEL'		=> true,
				'MEMBER_LIST'	=> $hptt->get_html_table($this->in->get('sort')),
		));
	}

} elseif($this->config->get('roster_classorrole') == 'none') {
	
	$arrMembers = $this->pdh->get('member', 'id_list', array($this->skip_inactive, $this->skip_hidden, true, $this->skip_twinks));

	$hptt = $this->get_hptt($this->hptt_page_settings, $arrMembers, $arrMembers, array('%link_url%' => $this->routing->simpleBuild('character'), '%link_url_suffix%' => '', '%with_twink%' => $this->skip_twinks, '%use_controller%' => true), 'none');
		
	$this->tpl->assign_block_vars('class_row', array(
			'CLASS_NAME'	=> '',
			'CLASS_ICONS'	=> '',
			'CLASS_LEVEL'	=> 2,
			'ENDLEVEL'		=> true,
			'MEMBER_LIST'	=> $hptt->get_html_table($this->in->get('sort')),
	));
	
} elseif($this->config->get('roster_classorrole') == 'guild') {
	$arrMembers = $this->pdh->get('member', 'id_list', array($this->skip_inactive, $this->skip_hidden, true, $this->skip_twinks));
	$arrGuilds = array();
	foreach($arrMembers as $intMemberID){
		$guild = $this->pdh->get('member', 'profile_field', array($intMemberID, 'guild'));
		if(!strlen($guild)) $guild = $this->config->get('guildtag');
		
		if(!isset($arrGuilds[$guild])) $arrGuilds[$guild] = array();
		$arrGuilds[$guild][] = $intMemberID;
	}
	
	foreach($arrGuilds as $strGuildname=>$arrGroupMembers){
		$hptt = $this->get_hptt($this->hptt_page_settings, $arrGroupMembers, $arrGroupMembers, array('%link_url%' => $this->routing->simpleBuild('character'), '%link_url_suffix%' => '', '%with_twink%' => $this->skip_twinks, '%use_controller%' => true), 'rank_'.$intRankID);
		
		$this->tpl->assign_block_vars('class_row', array(
				'CLASS_NAME'	=> $strGuildname,
				'CLASS_ICONS'	=> "",
				'CLASS_LEVEL'	=> 2,
				'ENDLEVEL'		=> true,
				'MEMBER_LIST'	=> $hptt->get_html_table($this->in->get('sort')),
		));
	}
		
} else {
	$arrMembers = $this->pdh->get('member', 'id_list', array($this->skip_inactive, $this->skip_hidden, true, $this->skip_twinks));
	
	$rosterClasses = $this->game->get_roster_classes();
	
	$arrRosterMembers = array();
	foreach($arrMembers as $memberid){
		$string = "";
		foreach($rosterClasses['todisplay'] as $key => $val){
			$string .= $this->pdh->get('member', 'profile_field', array($memberid, $this->game->get_name_for_type($val)))."_";
		}
	
		$arrRosterMembers[$string][] = $memberid;
	}
	
	$this->build_class_block($rosterClasses['data'], $rosterClasses['todisplay'], $arrRosterMembers);
}

$this->tpl->assign_vars(array(
		'MEMBER_COUNT'		=> count($arrMembers),
));
?>