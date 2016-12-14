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

if (!defined('EQDKP_INC')){
	header('HTTP/1.0 404 Not Found');exit;
}

if (!class_exists('battlenet_user_avatarimg_hook')){
	class battlenet_user_avatarimg_hook extends gen_class{
		/* List of dependencies */
		public static $shortcuts = array();
		
		
		public function avatar_provider($arrParams)
		{
			return array('wow' => array('name' => 'WoW'));
		}

		public function user_avatarimg($arrParams){
			$user_id = $arrParams['user_id'];
			$fullSize = $arrParams['fullsize'];
			$avatarimg = $arrParams['avatarimg'];
			$strAvatarType = $arrParams['avatartype'];
			$blnDefault= $arrParams['default'];
			if($strAvatarType != 'wow') return array('wow' => '');
			
			// get the main char of the user
			$mainchar	= $this->pdh->get('member', 'mainchar', array($user_id));
			if($mainchar != ''){
				$char_server	= $this->pdh->get('member', 'profile_field', array($mainchar, 'servername'));
				$servername		= ($char_server != '') ? $char_server : $this->config->get('servername');
				$strMemberName	= $this->pdh->get('member', 'name', array($mainchar));
			
				$this->game->new_object('bnet_armory', 'armory', array($this->config->get('uc_server_loc'), $this->config->get('uc_data_lang')));
				$chardata		= $this->game->obj['armory']->character($strMemberName, unsanitize($servername), true);
				$charicon		= $this->game->obj['armory']->characterIcon($chardata);

				if($charicon != ''){
					return array('wow' => $charicon);
				}
			}
			return array('wow' => '');
		}
	}
}
?>