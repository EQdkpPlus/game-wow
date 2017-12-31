<?php
/*	Project:	EQdkp-Plus
 *	Package:	Local Itembase Plugin
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

if(!class_exists('localitembase_parser')) {
	class localitembase_parser extends itt_parser {
		
		public static $shortcuts = array('puf' => 'urlfetcher');
		
		public static $plugin_version = '1.2.0';
		
		public $av_langs = array('en' => 'en_US', 'de' => 'de_DE', 'fr' => 'fr_FR', 'ru' => 'ru_RU', 'es' => 'es_ES');
		
		public $settings = array(
				'itt_icon_loc' => array(
						'type' => 'text',
						'default' => ''),
				'itt_icon_ext' => array(
						'type' => 'text',
						'default' => ''),
				'itt_default_icon' => array(
						'type' => 'text',
						'default' => ''),
		);
		
		private $searched_langs = array();
		
		public function __destruct(){
			unset($this->searched_langs);
			parent::__destruct();
		}
		
		protected function searchItemID($itemname, $lang, $searchagain=0) {
			$searchagain++;
			$this->pdl->log('infotooltip', 'localitembase->searchItemID called: itemname: '.$itemname.', lang: '.$lang.', searchagain: '.$searchagain);
			$item_id = 0;
			
			// Ignore blank names.
			$name = trim($itemname);
			if (empty($name)) { return null; }
			
			$arrItemData = $this->getItemByName($this->unsanitize($name));
			if($arrItemData){
				$gameid = $arrItemData['item_gameid'];
				$item_id = ($gameid != "") ? $gameid : 'lit:'.$arrItemData['id'];
				
				$debug_out = 'Item-ID found: '.$item_id;
			} else {
				$item_id = false;
				$debug_out = 'No Item-ID found';
			}
			
			$this->pdl->log('infotooltip', $debug_out);
			return array($item_id, 'items');
		}
		
		private function getItemByName($strItemname){
			//Sanitize, because it is in database sanitized
			$strItemname = filter_var($strItemname, FILTER_SANITIZE_STRING);
			
			$objQuery = $this->db->query("SELECT * FROM __plugin_localitembase WHERE LOWER(item_name) LIKE ".$this->db->escapeString('%'.$this->utf8_strtolower($strItemname).'%'));
			if($objQuery){
				while($row = $objQuery->fetchAssoc()){
					$arrNames = unserialize($row['item_name']);
					foreach($arrNames as $key => $val){
						if($this->utf8_strtolower($val) === $this->utf8_strtolower($strItemname)){
							return $row;
						}
					}
				}
			}
			
			return false;
		}
		
		private function getItemFromDatabase($intLitItemID){
			$objQuery = $this->db->prepare("SELECT * FROM __plugin_localitembase WHERE id=?")->execute($intLitItemID);
			if($objQuery){
				$arrItemData = $objQuery->fetchAssoc();
				if(count($arrItemData)){
					return $arrItemData;
				}
			}
			
			return false;
		}
		
		private function getItemFromIngameID($strIngameID){
			$objQuery = $this->db->prepare("SELECT * FROM __plugin_localitembase WHERE item_gameid=?")->execute($strIngameID);
			if($objQuery){
				$arrItemData = $objQuery->fetchAssoc();
				if(count($arrItemData)){
					return $arrItemData;
				}
			}
			
			return false;
		}
		
		private function getPluginConfig(){
			$objQuery = $this->db->prepare("SELECT * FROM __config WHERE config_plugin='localitembase'")->execute();
			if($objQuery){
				while($row = $objQuery->fetchAssoc()){
					$arrConfigData[$row['config_name']] = $row['config_value'];
				}
				
				return $arrConfigData;
			}
			
			return false;
		}
		
		protected function getItemData($item_id, $lang, $itemname='', $type='items'){
			$orig_id = $item_id;
			$intLitItemID = false;
			
			if(!$item_id) {
				$item['baditem'] = true;
				return $item;
			}
			
			//lit: means, that following id is the internal primary key
			if(strpos($item_id, 'lit:') === 0){
				$intLitItemID = (int)substr($item_id, 4);
				$arrItemData = $this->getItemFromDatabase($intLitItemID);
			} else {
				//User can prefix the ItemID with lib: just to use localitembase.
				if(strpos($item_id, 'lib:') === 0) $item_id = (int)substr($item_id, 4);
				
				$arrItemData = $this->getItemFromIngameID($item_id);
				if($arrItemData) $intLitItemID = $arrItemData['id'];
			}
			
			if($intLitItemID !== false && count($arrItemData)){
				
				$myLang = isset($this->av_langs[$lang]) ? $this->av_langs[$lang] : false;
				
				$myLang = ($myLang == 'en_US') ? "en_EN" : $myLang;
				
				$arrNames = unserialize($arrItemData['item_name']);
				if(isset($arrNames[$myLang]) && strlen($arrNames[$myLang])){
					$item['name'] = $arrNames[$myLang];
				} else {
					foreach($arrNames as $key => $val){
						if($val != "") {
							$item['name'] = $val; break;
						}
					}
				}
				$item['lang'] = $lang;
				$item['color'] = $arrItemData['quality'];
				
				//Icon
				$item['icon'] = ($arrItemData['icon'] != "") ? $this->pfh->FileLink('icons/'.$arrItemData['icon'], 'localitembase', 'absolute') : '';
				
				//HTML
				$arrConfig = $this->getPluginConfig();
				$strBaseLayout = $arrConfig['base_layout'];
				
				//Wenn Kein Inhalt, aber Bild, nehme Bild. Wenn Inhalt, replace Image
				$arrText = unserialize($arrItemData['text']);
				if(isset($arrText[$myLang]) && strlen($arrText[$myLang])){
					$itemText = $arrText[$myLang];
				} else {
					foreach($arrNames as $key => $val){
						if($val != "") {
							$itemText = $val; break;
						}
					}
				}
				$itemImage = false;
				$arrImage = unserialize($arrItemData['image']);
				
				if(isset($arrImage[$myLang]) && strlen($arrImage[$myLang])){
					$itemImage = $arrImage[$myLang];
				}
				
				if($itemImage && strlen($itemText)){
					$itemImage = $this->pfh->FileLink('images/'.$itemImage, 'localitembase', 'absolute');
					$itemText = str_replace('{IMAGE}', '<img src="'.$itemImage.'" />', $itemText);
				}elseif($itemImage){
					$itemText = '<img src="'.$itemImage.'" />';
				} else {
					$itemText = str_replace('{IMAGE}', '', $itemText);
				}
				
				$itemText = str_replace("{ITEM_CONTENT}", $itemText, $strBaseLayout);
				if($item['icon'] == ""){
					$item['icon'] = $this->env->link.'plugins/localitembase/images/unknown.jpg';
				}
				
				//Icon
				$itemText = str_replace("{ICON}", $item['icon'], $itemText);
				
				$item['html'] = $itemText;
				
				return $item;
			}
			
			$item['baditem'] = true;
			return $item;
		}
		
		
		private function unsanitize($input){
			if (is_array($input)){
				return array_map("unsanitize", $input);
			}
			
			$input = str_replace("&#34;", "&quot;", $input);
			return htmlspecialchars_decode($input, ENT_QUOTES);
		}
		
		//A workaround because strtolower() does not support UTF8
		private function utf8_strtolower($string){
			if (function_exists('mb_strtolower')){
				$string = mb_strtolower($string,'UTF-8');
			} else {
				$convert_to = array(
						"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
						"v", "w", "x", "y", "z", "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï",
						"ð", "ñ", "ò", "ó", "ô", "õ", "ö", "ø", "ù", "ú", "û", "ü", "ý", "а", "б", "в", "г", "д", "е", "ё", "ж",
						"з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы",
						"ь", "э", "ю", "я"
				);
				$convert_from = array(
						"A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U",
						"V", "W", "X", "Y", "Z", "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï",
						"Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "Ø", "Ù", "Ú", "Û", "Ü", "Ý", "А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж",
						"З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ъ",
						"Ь", "Э", "Ю", "Я"
				);
				
				$string = str_replace($convert_from, $convert_to, $string);
			}
			
			
			return $string;
		}
		
	}
}
?>