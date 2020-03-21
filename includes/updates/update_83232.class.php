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

class update_83232 extends sql_update_task {
	public $author			= 'Wallenium';
	public $version			= '8.3.23.2';
	public $ext_version		= '8.3.23.2';
	public $name			= '8.3.23.2';
	public $game_path		= 'wow';
	public $type			= 'game_update';

	public function __construct(){
		parent::__construct();

		$this->langs = array(
			'english' => array(
				'update_83232'		=> 'WoW Game 8.3.2',
				'update_function'	=> 'Add events for the awakened city',
			),
			'german' => array(
				'update_83232'		=> 'WoW Game 8.3.2',
				'update_function'	=> 'Füge Ereignisse für die erwachte Stadt hinzu',
			),
		);
	}

	public function update_function(){
		$this->game->addEvent($this->game->glang('bfa_twc_normal'), 0, "twc.png");
		$this->game->addEvent($this->game->glang('bfa_twc_heroic'), 0, "twc.png");
		$this->game->addEvent($this->game->glang('bfa_twc_mythic'), 0, "twc.png");
		return true;
	}
}
?>