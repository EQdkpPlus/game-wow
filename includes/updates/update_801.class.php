<?php
/*	Project:	EQdkp-Plus
 *	Package:	EQdkp-plus
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

include_once(registry::get_const('root_path').'maintenance/includes/sql_update_task.class.php');

class update_801 extends sql_update_task {
	public $author			= 'Wallenium';
	public $version			= '8.0.1'; //new plus-version
	public $ext_version		= '8.0.1'; //new plus-version
	public $name			= '8.0.1';
	public $game_path		= 'wow';
	public $type			= 'game_update';

	public function __construct(){
		parent::__construct();

		$this->langs = array(
			'english' => array(
				'update_801'		=> 'WoW Game 8.0.1 Update',
				'update_function'	=> 'Add events for Battle for Azeroth',
			),
			'german' => array(
				'update_801'		=> 'WoW Game 8.0.1 Update',
				'update_function'	=> 'Füge Ereignisse für Battle for Azeroth hinzu',
			),
		);
	}

	public function update_function(){
		$this->game->addEvent($this->game->glang('bfa_uldir_normal'), 0, "uldir.png");
		$this->game->addEvent($this->game->glang('bfa_uldir_heroic'), 0, "uldir.png");
		$this->game->addEvent($this->game->glang('bfa_uldir_mythic'), 0, "uldir.png");
		return true;
	}
}
?>