<?php
/*	Project:	EQdkp-Plus
 *	Package:	EQdkp-plus
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2019 EQdkp-Plus Developer Team
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

class update_81234 extends sql_update_task {
	public $author			= 'Wallenium';
	public $version			= '8.1.23.4';
	public $ext_version		= '8.1.23.4';
	public $name			= '8.1.23.4';
	public $game_path		= 'wow';
	public $type			= 'game_update';

	public function __construct(){
		parent::__construct();

		$this->langs = array(
			'english' => array(
				'update_81234'		=> 'WoW Game 8.1.4',
				'update_function'	=> 'Add events for Crucible of Storms',
			),
			'german' => array(
				'update_81234'		=> 'WoW Game 8.1.4',
				'update_function'	=> 'Füge Ereignisse für Tiegel der Stürme hinzu',
			),
		);
	}

	public function update_function(){
		$this->game->addEvent($this->game->glang('bfa_cos_normal'), 0, "cos.png");
		$this->game->addEvent($this->game->glang('bfa_cos_heroic'), 0, "cos.png");
		$this->game->addEvent($this->game->glang('bfa_cos_mythic'), 0, "cos.png");
		return true;
	}
}
?>