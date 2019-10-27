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

if(!class_exists('wowhead')) {
	class wowhead extends itt_parser {

		public static $shortcuts = array('puf' => 'urlfetcher');

		public $av_langs = array('en' => 'en_US', 'de' => 'de_DE', 'fr' => 'fr_FR', 'ru' => 'ru_RU', 'es' => 'es_ES', 'it' => 'it_IT', 'pt' => 'pt_PT', 'ko' => 'KO', 'cn' => 'CN');

		public $settings = array(
			'itt_icon_loc' => array(
				'type' => 'text',
				'default' => 'https://wow.zamimg.com/images/wow/icons/large/'
			),
			'itt_icon_small_loc' => array(
				'type' => 'text',
				'default' => 'https://wow.zamimg.com/images/wow/icons/small/'
			),
			'itt_icon_ext' => array(
				'type' => 'text',
				'default' => '.jpg'
			),
			'itt_default_icon' => array(
				'type' => 'text',
				'default' => 'inv_misc_questionmark'
			)
		);

		private $searched_langs = array();

		public function __destruct(){
			unset($this->searched_langs);
			parent::__destruct();
		}

		protected function searchItemID($itemname, $lang, $searchagain=0) {
			$searchagain++;
			$this->pdl->log('infotooltip', 'wowhead->searchItemID called: itemname: '.$itemname.', lang: '.$lang.', searchagain: '.$searchagain);
			$item_id = 0;

			// Ignore blank names.
			$name = trim($itemname);
			if (empty($name)) { return null; }

			$encoded_name = urlencode($name);
			$encoded_name = str_replace('+' , '%20' , $encoded_name);
			$lang_prefix = ($lang == 'en') ? 'www' : $lang;

			$item_encoded_name = $encoded_name;
			$item_encoded_name = str_replace("%C3%", "%C3%20%", $item_encoded_name);

			$url = 'https://'.$lang_prefix.'.wowhead.com/item='.$item_encoded_name.'&xml';
			$this->pdl->log('infotooltip', 'Search for ItemID at '.$url);
			$item_data = $this->puf->fetch($url);

			$xml = simplexml_load_string($item_data);
			if(is_object($xml) && !isset($xml->error)) {
				$item_id = (int)$xml->item->attributes()->id;
			} else {
				$this->pdl->log('infotooltip', 'Invalid XML');
			}

			//Use normal search
			if(!$item_id){
			
				$url = 'https://'.$lang_prefix.'.wowhead.com/search?q='.$encoded_name;
				$search_data = $this->puf->fetch($url);
				$arrSearchMatches = array();
				preg_match_all("/\"id\":([0-9]*),\"level\":([0-9]*),\"name\":\"([0-9])".$itemname."\"/", $search_data, $arrSearchMatches);
				if (isset($arrSearchMatches[1]) && count($arrSearchMatches[1])){
					$arrUniqueIDs = array_unique($arrSearchMatches[1]);
					//Take the first one
					$item_id = $arrUniqueIDs[0];
				}
			}

			//search in other languages
			if(!$item_id AND $searchagain < count($this->av_langs)) {
				$this->pdl->log('infotooltip', 'No Items found.');
				if(count($this->config['lang_prio']) >= $searchagain) {
					$this->pdl->log('infotooltip', 'Search again in other language.');
					$this->searched_langs[] = $lang;
					foreach($this->config['lang_prio'] as $slang) {
						if(!in_array($slang, $this->searched_langs)) {
							return $this->searchItemID($itemname, $slang, $searchagain);
						}
					}
				}
			}
			$debug_out = ($item_id > 0) ? 'Item-ID found: '.$item_id : 'No Item-ID found';
			$this->pdl->log('infotooltip', $debug_out);
			return array($item_id, 'items');
		}

		protected function getItemData($item_id, $lang, $itemname='', $type='items'){
			$orig_id = $item_id;

			if(!$item_id) {
				$item['baditem'] = true;
				return $item;
			}

			$bonus = 0;

			$myItemData = array(
					'ench' => 0,
					'gems' => array(),
					'lvl'  => 0,
					'spez' => 0,
					'upgd_type' => 0,
					'bonus' => array(),
					'num_bonus' => 0,
					'upgd_id' => '',
			);

			$arrItemData = explode(':', $item_id);
			if (count($arrItemData) > 1){
				$item_id = $arrItemData[0];

				//Detect if old or new (6.2.0) format
				$intTotalCount = count($arrItemData);
				$intBonusCount = $arrItemData[12];
				$isUpgrade = ((int)$arrItemData[10] != 0) ? true : false;
				$intShouldCount = 13 + $intBonusCount + (($isUpgrade) ? 1 : 0);
				$blnIsNewFormat = ($intShouldCount === $intTotalCount) ? true : false;

				$arrBonus = array();

				if($blnIsNewFormat){
					foreach($arrItemData as $key => $val){
						if ($key == 1) $myItemData['ench'] = $val;
						if ($key == 8) $myItemData['lvl'] = $val;
						if ($key == 9) $myItemData['spez'] = $val;
						if ($key == 10) $myItemData['upgd_type'] = $val;
						if ($key == 11) $myItemData['inst_diff'] = $val;
						if ($key == 12) $myItemData['num_bonus'] = $val;
						if ($key > 2 && $key < 7){
							$myItemData['gems'][] = $val;
						}

						if ($key > 12 && $key < (13+$myItemData['num_bonus'])){
							$myItemData['bonus'][] = $val;
						}

						if($key > (12+$myItemData['num_bonus'])){
							$myItemData['upgd_id'] = $val;
						}

						//124139:0      :     0:     0:     0:     0:       0:       0:      100:           267:              0:                   6:          1:     567
						//itemID:enchant:gemID1:gemID2:gemID3:gemID4:suffixID:uniqueID:linkLevel:specializationID:upgradeTypeID:instanceDifficultyID:numBonusIDs:bonusID1:bonusID2
					}
				} else {
					foreach($arrItemData as $key => $val){
						if ($key == 1) $myItemData['ench'] = $val;
						if ($key == 8) $myItemData['lvl'] = $val;
						if ($key == 9) $myItemData['upgd_id'] = $val;
						if ($key > 2 && $key < 7){
							$myItemData['gems'][] = $val;
						}

						if ($key > 11){
							$myItemData['bonus'][] = $val;
						}
					}
					//112417:0:0:0:0:0:0:0:lvl90:upg 491:dif 5:2:448:449
					//itemID:enchant:gem1:gem2:gem3:gem4:suffixID:uniqueID:level:upgradeId:instanceDifficultyID:numBonusIDs:bonusID1:bonusID2...
				}
			}

			$item = array('id' => $item_id, 'origid' => $orig_id);
			$url = ($lang == 'en') ? 'www' : $lang;

			$item['link'] = 'https://'.$url.'.wowhead.com/tooltip/item/'.$item['id'].'&json&power&bonus='.implode(':', $myItemData['bonus']).'&upgd='.$myItemData['upgd_id'].'&lvl='.$myItemData['lvl'].'&ench='.$myItemData['ench'].'&gems='.implode(',',$myItemData['gems']);
			
			$this->pdl->log('infotooltip', 'fetch item-data from: '.$item['link']);
			$someJS = $this->puf->fetch($item['link'], array('Cookie: cookieLangId="'.$lang.'";'));

			if ($someJS){
				$arrMatches = array();
				
				//$intCount = preg_match("/name_(.*):\"(.*)\",(\s*)\"quality\":(.*),(\s*)\"icon\":\"(.*)\",(\s*)\"tooltip_(.*)\":\"(.*)\"/", $someJS, $arrMatches);
				
				$intCount = preg_match('/name":"(.*)",/U', $someJS, $arrMatches);
				if ($intCount){
					$item['name'] = htmlentities(stripslashes(json_decode('"'.$arrMatches[1].'"')));

					//Quality
					$arrMatches = array();
					preg_match('/quality":(.*),/U', $someJS, $arrMatches);
					$item['color'] = 'q'.(int)$arrMatches[1];
					
					//Icon
					$arrMatches = array();
					preg_match('/icon":"(.*)",/U', $someJS, $arrMatches);
					$item['icon'] = htmlentities($arrMatches[1]);
					
					//Tooltip
					$arrMatches = array();
					preg_match('/tooltip":"(.*)"(,"|})/', $someJS, $arrMatches);
					$ahtml = $arrMatches[1];
					
					$template_html = trim(file_get_contents($this->root_path.'games/wow/infotooltip/templates/wow_popup.tpl'));
					
					$html = json_decode('"'.$ahtml.'"');
					if($html == NULL){
						$html = preg_replace('/","(.*)/', "", $ahtml);
						$html = json_decode('"'.$html.'"');
					}
					
					$item['html'] = str_replace('{ITEM_HTML}', stripslashes($html), $template_html);
					$item['lang'] = $lang;

					//Reset Item ID, because the full name is the one we should store in DB
					$item['id'] = $orig_id;
					
					return $item;

					
				} else {
					$this->pdl->log('infotooltip', 'no match found');
					$item['baditem'] = true;
					return $item;
				}
			} else {
				$this->pdl->log('infotooltip', 'no data from URL');
				$item['baditem'] = true;

				return $item;
			}

			$item['baditem'] = true;
			return $item;
		}

	}
}
?>
