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

class bnet_armory extends gen_class {

	private $version			= '20200517-1';
	private $chariconUpdates	= 0;
	private $chardataUpdates	= 0;
	private $ratepersecond		= 100;
	private $blnOauthChecked	= false;

	// new URL
	const apiurl				= 'https://{region}.api.blizzard.com/';
	const oauthurl				= 'https://{region}.battle.net/oauth/';
	const charimageurl			= 'http://render-{region}.worldofwarcraft.com/character/';

	// old URL to be checked
	const staticrenderurl		= 'http://{region}.battle.net/static-render/';		// http://us.battle.net/forums/en/bnet/topic/20748205383
	const staticimages			= 'http://{region}.battle.net/wow/static/images/';
	const staticicons			= 'http://{region}.media.blizzard.com/wow/icons/';
	const tabardrenderurl		= 'http://{region}.battle.net/wow/static/images/guild/tabards/';
	const profileurlChar		= 'https://worldofwarcraft.com/{locale}/';
	const profileurlGuild		= 'https://worldofwarcraft.com/{locale}/guild/{region}/';

	private $_config			= array(
		'serverloc'					=> 'us',
		'locale'					=> 'en',
		'caching'					=> true,
		'image-caching'				=> true,
		'caching_time'				=> 24,
		'apiUrl'					=> '',
		'apiRenderUrl'				=> '',
		'apiTabardRenderUrl'		=> '',
		'client_id'					=> '',
		'client_secret'				=> '',
		'access_token'				=> false,
		'maxChariconUpdates'		=> 5,
		'maxChardataUpdates'		=> 2*8, //One Character update needs up to 8 single queries
	);

	protected $convert			= array(
		'classes' => array(
			1		=> '10',	// warrior
			2		=> '5',		// paladin
			3		=> '3',		// hunter
			4		=> '7',		// rogue
			5		=> '6',		// priest
			6		=> '1',		// DK
			7		=> '8',		// shaman
			8		=> '4',		// mage
			9		=> '9',		// warlock
			11		=> '2',		// druid
			10		=> '11',	//monk
			12		=> '12',	// demon hunter
		),
		// https://wow.gamepedia.com/RaceId
		'races' => array(
			'1'		=> 2,		// human
			'2'		=> 7,		// orc
			'3'		=> 3,		// dwarf
			'4'		=> 4,		// night elf
			'5'		=> 6,		// undead
			'6'		=> 8,		// tauren
			'7'		=> 1,		// gnome
			'8'		=> 5,		// troll
			'9'		=> 12,		// Goblin
			'10'	=> 10,		// blood elf
			'11'	=> 9,		// draenei
			'22'	=> 11,		// Worgen
			'24'	=> 13,		// Pandaren neutral
			'25'	=> 13,		// Pandaren alliance
			'26'	=> 13,		// Pandaren horde
			'27'	=> 14,		// Nightborne (horde)
			'28'	=> 15,		// Highmountain Tauren (horde)
			'29'	=> 16,		// Void Elf (alliance)
			'30'	=> 17,		// Lightforged Draenei (alliance)
			'31'	=> 20,		// ZandalariTroll
			'32'	=> 21,		// Kul Tiran
			'34'	=> 18, 		// Dark Iron Dwarf
			'35'	=> 22,		// Vulpera
			'36'	=> 19, 		// Mag'har Orc
			'37'	=> 23,		// Mechagnome
		),
		'gender' => array(
			'0'		=> 'male',
			'1'		=> 'female',
		),
		'talent'	=> array(
			//DK
			0 => 'spell_deathknight_bloodpresence',
			1 => 'spell_deathknight_frostpresence',
			2 => 'spell_deathknight_unholypresence',
			//Druid
			3 => 'spell_nature_starfall',
			4 => 'ability_druid_catform',
			5 => 'ability_racial_bearform',
			6 => 'spell_nature_healingtouch',
			//Hunter
			7 => 'ability_hunter_bestialdiscipline',
			8 => 'ability_hunter_focusedaim',
			9 => 'ability_hunter_camouflage',
			//Mage
			10 => 'spell_holy_magicalsentry',
			11 => 'spell_fire_firebolt02',
			12 => 'spell_frost_frostbolt02',
			//Paladin
			13 => 'spell_holy_holybolt',
			14 => 'ability_paladin_shieldofthetemplar',
			15 => 'spell_holy_auraoflight',
			//Priest
			16 => 'spell_holy_powerwordshield',
			17 => 'spell_holy_guardianspirit',
			18 => 'spell_shadow_shadowwordpain',
			//Rogue
			19 => 'ability_rogue_deadlybrew',
			20 => 'inv_sword_30',
			21 => 'ability_stealth',
			//Shaman
			22 => 'spell_nature_lightning',
			23 => 'spell_shaman_improvedstormstrike',
			24 => 'spell_nature_magicimmunity',
			//Warlock
			25 => 'spell_shadow_deathcoil',
			26 => 'spell_shadow_metamorphosis',
			27 => 'spell_shadow_rainoffire',
			//Warrior
			28 => 'ability_warrior_savageblow',
			29 => 'ability_warrior_innerrage',
			30 => 'ability_warrior_defensivestance',
			//Monk
			31 => 'spell_monk_brewmaster_spec',
			32 => 'spell_monk_mistweaver_spec',
			33 => 'spell_monk_windwalker_spec',
			//Demonhunter
			34 => 'ability_demonhunter_specdps',
			35 => 'ability_demonhunter_spectank',
		),
	);

	private $serverlocs			= array(
		'eu'	=> 'EU',
		'us'	=> 'US',
		'kr'	=> 'KR',
		'tw'	=> 'TW',
		'sea'	=> 'SEA',
	);
	private $converts			= array();

	/**
	* Initialize the Class
	*
	* @param $serverloc		Location of Server
	* @param $locale		The Language of the data
	* @return bool
	*/
	public function __construct($serverloc='us', $locale='en_EN'){
		// set the correct server location & locale
		$this->_config['serverloc']	= ($serverloc != '') ? $serverloc : 'en_EN';
		$this->_config['locale']	= $locale;
		$this->setApiUrl($this->_config['serverloc']);

		// the client id & secret
		$this->_config['client_id']		= (defined('GAME_IMPORTER_CLIENTID')) ? GAME_IMPORTER_CLIENTID : ((class_exists('registry')) ? registry::register('game')->get_import_apikey('game_importer_clientid') : '');
		$this->_config['client_secret']	= (defined('GAME_IMPORTER_CLIENTSECRET')) ? GAME_IMPORTER_CLIENTSECRET : ((class_exists('registry')) ? registry::register('game')->get_import_apikey('game_importer_clientsecret') : '');
	}

	public function __get($name) {
		if(class_exists('registry')) {
			if($name == 'pfh') return registry::register('file_handler');
			if($name == 'puf') return registry::register('urlfetcher');
			if($name == 'pdl') return registry::register('plus_debug_logger');
		}
		return null;
	}

	/**
	* Debug function
	*
	* @param $strValue		String Value
	* @return none
	*/
	private function _debug($strValue){
		if(class_exists('plus_debug_logger')){
			if(!$this->pdl->type_known('bnet_armory')) $this->pdl->register_type('bnet_armory', null, null, array(2,3,4));
			$this->pdl->log('bnet_armory', $strValue);
		}
	}

	/**
	* Set some settings
	*
	* @param $setting	Which language to import
	* @return bool
	*/
	public function setSettings($setting){
		if(isset($setting['loc'])){
			$this->_config['serverloc']	= $setting['loc'];
			$this->setApiUrl($this->_config['serverloc']);
		}
		if(isset($setting['locale'])){
			$this->_config['locale']	= $setting['locale'];
		}
		if(isset($setting['caching_time'])){
			$this->_config['caching_time']	= $setting['caching_time'];
		}
		if(isset($setting['caching'])){
			$this->_config['caching']	= $setting['caching'];
		}
		if(isset($setting['image-caching'])){
			$this->_config['image-caching']	= $setting['image-caching'];
		}
		if(isset($setting['client_id'])){
			$this->_config['client_id']	= $setting['client_id'];
		}
		if(isset($setting['client_secret'])){
			$this->_config['client_secret']	= $setting['client_secret'];
		}
	}

	/**
	* Fetch the token data with client credentials from API via OAUTH
	*
	* @return string json token data
	*/
	private function get_access_token($force=false){
		// do not load the access token if none is set or if the token is not properly defined
		if(empty($this->_config['client_id']) || empty($this->_config['client_secret']) || is_array($this->_config['client_id']) || is_array($this->_config['client_secret'])){
			return false;
		}

		$json		= $this->get_CachedData('client_token_'.$this->_config['client_id'], $force);
		if((!$json || ($force)) && !$this->blnOauthChecked){
			// the OAUTH URL to receive the token, read the data
			$tokenurl 	= $this->_config['oauthurl'].'token?grant_type=client_credentials&client_id='.$this->_config['client_id'].'&client_secret='.$this->_config['client_secret'];
			$json		= $this->read_url($tokenurl, 5);
			$this->set_CachedData($json, 'client_token_'.$this->_config['client_id']);
			$this->blnOauthChecked = true;
		}

		// decode & check for errors
		$tokendata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($tokendata);
		return (!$errorchk) ? $tokendata: $errorchk;
	}

	/**
	* Check if the token is still valid and set it to config var
	*
	* @return string token data
	*/
	public function check_access_tocken(){
		if(empty($this->_config['client_id']) || is_array($this->_config['client_id'])){
			$this->_config['access_token'] = false;
			return false;
		}

		// load the cached data
		$client_token_ts				= $this->get_CachedData('client_token_ts_'.$this->_config['client_id']);
		$this->_config['access_token']	= $this->get_CachedData('client_token_id_'.$this->_config['client_id']);

		// check if the token is available and still valid
		if(!isset($client_token_ts) || $client_token_ts < 1 || ($client_token_ts > 0 && $client_token_ts < time()) || !$this->_config['access_token']){
			// fetch the token data from API
			$tokendata = $this->get_access_token(true);

			// Save the tokens to cache & _config array
			$this->_config['access_token']		= isset($tokendata['access_token']) ? $tokendata['access_token'] : false;
			$this->set_CachedData((isset($tokendata['access_token']) ? $tokendata['access_token'] : false), 'client_token_id_'.$this->_config['client_id']);
			$this->set_CachedData((isset($tokendata['expires_in']) ? time() + $tokendata['expires_in'] : time()), 'client_token_ts_'.$this->_config['client_id']);
			return $tokendata;
		}
		return false;
	}

	/**
	* Get the server location
	*
	* @return string server location
	*/
	public function getServerLoc(){
		return $this->serverlocs;
	}

	/**
	* Get the bnet armory class loader version
	*
	* @return string version
	*/
	public function getVersion(){
		return $this->version.((preg_match('/\d+/', $this->build, $match))? '#'.$match[0] : '');
	}

	/**
	* GEt the profile URL
	*
	* @return string URL
	*/
	public function getProfileULR($type='char'){
		if($type=='char'){
			return str_replace('{locale}', $this->_config['locale'], self::profileurlChar);
		}else{
			$tmp_url	= str_replace('{region}', $this->_config['serverloc'], self::profileurlGuild);
			return str_replace('{locale}', $this->_config['locale'], $tmp_url);
		}
	}

	public function getWoWNamespace($namespace="profile", $regionality=true){
		return ($regionality) ? $namespace.'-'.$this->_config['serverloc'] : $namespace;
	}

	/**
	* Generate Link to Armory
	*
	* @param $user			Name of the User
	* @param $server		Name of the WoW Server
	* @param $mode			Which page to open? (char, talent, statistics, reputation, guild, achievements)
	* @param $guild			Name of the guild
	* @return string		output
	*/
	public function bnlink($user, $server, $mode='char', $guild='', $talents=array()){
		switch ($mode) {
			case 'char':
				return $this->getProfileULR().sprintf('character/%s/%s', $this->ConvertInput($server, true, true), $this->ConvertInput($user));break;
			case 'reputation':
				return $this->getProfileULR().sprintf('character/%s/%s/reputation', $this->ConvertInput($server, true, true), $this->ConvertInput($user));break;
			case 'pvp':
				return $this->getProfileULR().sprintf('character/%s/%s/pvp', $this->ConvertInput($server, true, true), $this->ConvertInput($user));break;
			case 'pve':
				return $this->getProfileULR().sprintf('character/%s/%s/pve', $this->ConvertInput($server, true, true), $this->ConvertInput($user));break;
			case 'achievements':
				return $this->getProfileULR().sprintf('character/%s/%s/achievements', $this->ConvertInput($server, true, true), $this->ConvertInput($user));break;
			case 'collections':
				return $this->getProfileULR().sprintf('character/%s/%s/collections', $this->ConvertInput($server, true, true), $this->ConvertInput($user));break;
			case 'talent-calculator':
				return $this->getProfileULR().sprintf('game/talent-calculator#%s/%s/talents=%s', $talents['class'], $talents['type'], $talents['calcTalent']);break;
			case 'guild':
				return $this->getProfileULR('guild').sprintf('%s/%s/', $this->ConvertInput($server, true, true), $this->ConvertInput($guild, false, true));break;
			case 'guild-achievements':
				return $this->getProfileULR('guild').sprintf('%s/%s/achievements', $this->ConvertInput($server, true, true), $this->ConvertInput($guild, false, true));break;
			case 'askmrrobot':
				return sprintf('https://www.askmrrobot.com/optimizer#%s/%s/%s', $this->_config['serverloc'], $this->ConvertInput($server, true, true), $this->ConvertInput($user));break;
			case 'raiderio':
				return sprintf('http://raider.io/characters/%s/%s/%s', $this->_config['serverloc'], $this->ConvertInput($server, true, true), $this->ConvertInput($user));break;
			case 'wowprogress':
				return sprintf('http://www.wowprogress.com/character/%s/%s/%s', $this->_config['serverloc'], $this->ConvertInput($server, true, true), $this->ConvertInput($user));break;
			case 'warcraftlogs':
				return sprintf('https://www.warcraftlogs.com/character/%s/%s/%s', $this->_config['serverloc'], $this->ConvertInput($server, true, true), $this->ConvertInput($user));break;


		}
	}

	/**
	* Return an array with all links for one char
	*
	* @param $user			Name of the User
	* @param $server		Name of the WoW Server
	* @return string		output
	*/
	public function a_bnlinks($user, $server, $guild=false, $talents=array()){
		return array(
			'profil'				=> $this->bnlink($user, $server, 'char'),
			'pvp'					=> $this->bnlink($user, $server, 'pvp'),
			'pve'					=> $this->bnlink($user, $server, 'pve'),
			'reputation'			=> $this->bnlink($user, $server, 'reputation'),
			'achievements'			=> $this->bnlink($user, $server, 'achievements'),
			'collections'			=> $this->bnlink($user, $server, 'collections'),
			'guild'					=> $this->bnlink($user, $server, 'guild', $guild),
			'talents'				=> $this->bnlink($user, $server, 'talent-calculator', $guild, $talents),

			// external ones
			'askmrrobot'			=> $this->bnlink($user, $server, 'askmrrobot'),
		);
	}

	/**
	* Fetch character information
	*
	* @param $user		Character Name
	* @param $realm		Realm Name
	* @param $force		Force the cache to update
	* @param $params	array, optional fields such as reputation, appearance, mounts, pets, hunterPets,petSlots, quests, pvp
	* @return bol
	*/
	public function character_singlefeed($user, $realm, $feed, $force=false){
		// the feeds
		switch($feed){
			case 'profile':			$parameter = ''; 					break;
			case 'achievements':	$parameter = '/achievements'; 		break;
			case 'appearance':		$parameter = '/appearance'; 		break;
			case 'hunterpets':		$parameter = '/hunter-pets'; 		break;
			case 'equipment':		$parameter = '/equipment'; 			break;
			case 'mounts':			$parameter = '/collections/mounts'; break;
			case 'pets':			$parameter = '/collections/pets'; 	break;
			//case 'professions':		$parameter = '/professions'; 		break;
			case 'raids':			$parameter = '/encounters/raids'; 	break;
			case 'reputatiom':		$parameter = '/reputations'; 		break;
			case 'talents':			$parameter = '/specializations'; 	break;
			case 'titles':			$parameter = '/titles'; 			break;
			case 'media':			$parameter = '/character-media'; 	break;
			case 'statistics':		$parameter = '/statistics'; 		break;
			default : 				$parameter = '';
		}

		$this->check_access_tocken();
		$realm		= $this->translateRealmToSlug($realm);
		$user		= $this->ConvertInput(utf8_strtolower($user));
		$wowurl		= $this->_config['apiUrl'].sprintf('profile/wow/character/%s/%s%s?namespace=%s&locale=%s&access_token=%s', $realm, $user, $parameter, $this->getWoWNamespace(), $this->_config['locale'], $this->_config['access_token']);
		$this->_debug('Character: '.$wowurl);
		$json		= $this->get_CachedData('chardata_'.$feed.'_'.$user.$realm, $force);
		if(!$json && ($force || $this->chardataUpdates < $this->_config['maxChardataUpdates']) && $this->_config['access_token']){
			$json	= $this->read_url($wowurl);
			$this->set_CachedData($json, 'chardata_'.$feed.'_'.$user.$realm);
			$this->chardataUpdates++;
		}
		//Try to get old data
		if(!$json) $json = $this->get_CachedData('chardata_'.$feed.'_'.$user.$realm, false, false, true);

		$chardata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($chardata);
		if(!$errorchk){
			$thumbnaildata = explode("/", $chardata['thumbnail']);
			if(!empty($thumbnaildata[0])) {
				$chardata['realm_english']	= $thumbnaildata[0];
			}
			return $chardata;
		}
		return $errorchk;
	}

	/**
	* Fetch character information
	*
	* @param $user		Character Name
	* @param $realm		Realm Name
	* @param $force		Force the cache to update
	* @param $params	array, optional fields such as reputation, appearance, mounts, pets, hunterPets,petSlots, quests, pvp
	* @return bol
	*/
	public function character($user, $realm, $force=false){
		$profile		= $this->character_singlefeed($user, $realm, 'profile', $force);
		//If the profile is empty, there is a high change that the character does not exist. Therefore cancel all other calls.
		if(empty($profile)){
			return array();
		// if there is an error message in the first call, do not perform the rest of the calls. Output the error message
		}elseif(isset($profile['status'])){
			return $profile;
		}else {
			//Do not set the status for the following calls, as they are optional
			$statistics		= $this->character_singlefeed($user, $realm, 'statistics', $force);
			if(isset($statistics['status'])) $statistics = array();
			$achievements	= $this->character_singlefeed($user, $realm, 'achievements', $force);
			if(isset($achievements['status'])) $achievements = array();
			$appearance		= $this->character_singlefeed($user, $realm, 'appearance', $force);
			if(isset($appearance['status'])) $appearance = array();
			$equipment		= $this->character_singlefeed($user, $realm, 'equipment', $force);
			if(isset($equipment['status'])) $equipment = array();
			$raids			= $this->character_singlefeed($user, $realm, 'raids', $force);
			if(isset($raids['status'])) $raids = array();
			$talents		= $this->character_singlefeed($user, $realm, 'talents', $force);
			if(isset($talents['status'])) $talents = array();
			$media			= $this->character_singlefeed($user, $realm, 'media', $force);
			if(isset($media['status'])) $media = array();
			#$titles		= $this->character_singlefeed($user, $realm, 'titles', $force);
			#if(isset($titles['status'])) $titles = array();
			#$professions	= $this->character_singlefeed($user, $realm, 'professions', $force);
			#if(isset($professions['status'])) $professions = array();
		
			$combined_char	= array_replace_recursive($profile, $appearance, $media);
			return array_merge_recursive($combined_char, $achievements, $equipment, $raids, $talents, $statistics);
		}
	}

	/**
	* Create full character Icon Link
	*
	* @param $thumb		Thumbinformation returned by battlenet JSON feed
	* @return string
	*/
	public function characterIcon($user, $realm, $type='icon', $force=false){
		$_user = $user;
		$_realm = $realm;
		
		$realm		= $this->translateRealmToSlug($realm);
		$user		= $this->ConvertInput(utf8_strtolower($user));
		
		#use the plain values here, as character_singlefeed will convert them
		$chardata	= $this->character_singlefeed($_user, $_realm, 'media', $force);
		
		//Default icon for unknown chars
		if(!$chardata){
			return '';
		}

		// get the cached image
		$img_charicon	= $this->get_CachedData('img_'.$type.'_'.$user.$realm, false, true);
		$img_charicon_sp= $this->get_CachedData('img_'.$type.'_'.$user.$realm, false, true, false, true);

		if(!$img_charicon && ($force || ($this->chariconUpdates < $this->_config['maxChariconUpdates']))){
			switch($type){
				case 'icon':	$image_url = $chardata['avatar_url']; 	break;
				case 'render':	$image_url = $chardata['render_url']; 	break;
				case 'inset':	$image_url = $chardata['bust_url']; 	break;
			}
			
			$imageData = $this->read_url($image_url);
			if($imageData && strlen($imageData)){
				$this->set_CachedData($this->read_url($image_url), 'img_'.$type.'_'.$user.$realm, true);
				$img_charicon	= $this->get_CachedData('img_'.$type.'_'.$user.$realm, false, true);
				$img_charicon_sp= $this->get_CachedData('img_'.$type.'_'.$user.$realm, false, true, false, true);
			}
			$this->chariconUpdates++;
		}

		if (!$img_charicon){
			//Try to get old data
			$img_charicon	= $this->get_CachedData('img_'.$type.'_'.$user.$realm, false, true, true);
			$img_charicon_sp= $this->get_CachedData('img_'.$type.'_'.$user.$realm, false, true, true, true);
		}
		
		if(filesize($img_charicon) < 150){
			return "";
		}
		
		return $img_charicon_sp;
	}

	public function characterIconSimple($race, $gender='0'){
		return $this->cacheIcon($this->_config['staticimageURL'].sprintf('2d/profilemain/race/%s-%s.jpg', $race, $gender), false);
	}

	public function talentIcon($name){
		return $this->cacheIcon($this->_config['staticiconURL'].'36/'.$name.'.jpg');
	}

	public function cacheIcon($url, $withsize = true, $forceUpdateAll = false){
		if(!$this->_config['image-caching']) { return $url; }
		$path			= explode('/', $url);
		$image_name		= (($withsize) ? $path[count($path)-2].'_' : '').$path[count($path)-1];
		$img_icon		= $this->get_CachedData($image_name, false, true, false,true);

		// download the icon
		if(!$img_icon || $forceUpdateAll){
			$this->set_CachedData($this->read_url($url), $image_name, true);
			$img_icon	= $this->get_CachedData($image_name, false, true, false,true);
		}
		return $img_icon;
	}

	public function selectedTitle($titles, $cleantitle=false){
		if(is_array($titles)){
			foreach($titles as $titledata){
				if(isset($titledata['selected']) && $titledata['selected'] == '1'){
					if($cleantitle){
						$temp_data = str_replace('%s, ', '', $titledata['name']);
						$temp_data = str_replace(' %s', '', $temp_data);
						$temp_data = str_replace('%s ', '', $temp_data);
						$temp_data = str_replace('%s', '', $temp_data);
						return $temp_data;
					}else{
						return $titledata['name'];
					}
				}
			}
		}
	}

	/**
	* Fetch roster information
	*
	* @param $user		Character Name
	* @param $realm		Realm Name
	* @param $force		Force the cache to update?
	* @return bol
	*/
	public function guildRoster($guild, $realm, $force=false){
		$this->check_access_tocken();
		$realm = $this->translateRealmToSlug($realm);
		$guild	= $this->createSlug($guild);

		$wowurl	= $this->_config['apiUrl'].sprintf('data/wow/guild/%s/%s/roster?namespace=%s&locale=%s&access_token=%s', $realm, $guild, $this->getWoWNamespace(),$this->_config['locale'], $this->_config['access_token']);
		$this->_debug('Guild: '.$wowurl);
		if((!$json	= $this->get_CachedData('guilddata_roster_'.$guild.$realm, $force)) && $this->_config['access_token']){
			$json	= $this->read_url($wowurl);
			$this->set_CachedData($json, 'guilddata_roster_'.$guild.$realm);
		}
		//get old data
		if(!$json) {
			$json = $this->get_CachedData('guilddata_roster_'.$guild.$realm, false, false, true);
		}
		$chardata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($chardata);
		
		return (!$errorchk) ? $chardata: $errorchk;
	}
	
	/**
	 * Fetch guild activity information
	 *
	 * @param $user		Character Name
	 * @param $realm		Realm Name
	 * @param $force		Force the cache to update?
	 * @return bol
	 */
	public function guildActivity($guild, $realm, $force=false){
		$this->check_access_tocken();
		$realm = $this->translateRealmToSlug($realm);
		$guild	= $this->createSlug($guild);
		
		$wowurl	= $this->_config['apiUrl'].sprintf('data/wow/guild/%s/%s/activity?namespace=%s&locale=%s&access_token=%s', $realm, $guild, $this->getWoWNamespace(),$this->_config['locale'], $this->_config['access_token']);
		$this->_debug('Guild: '.$wowurl);
		if((!$json	= $this->get_CachedData('guilddata_activity_'.$guild.$realm, $force)) && $this->_config['access_token']){
			$json	= $this->read_url($wowurl);
			
			$this->set_CachedData($json, 'guilddata_activity_'.$guild.$realm);
		}
		//get old data
		if(!$json) {
			$json = $this->get_CachedData('guilddata_activity_'.$guild.$realm, false, false, true);
		}
		$chardata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($chardata);
		
		return (!$errorchk) ? $chardata: $errorchk;
	}
	
	/**
	 * Fetch basic guild information
	 *
	 * @param $user		Character Name
	 * @param $realm		Realm Name
	 * @param $force		Force the cache to update?
	 * @return bol
	 */
	public function guild($guild, $realm, $force=false){
		$this->check_access_tocken();
		$realm = $this->translateRealmToSlug($realm);
		$guild	= $this->createSlug($guild);
		
		$wowurl	= $this->_config['apiUrl'].sprintf('data/wow/guild/%s/%s?namespace=%s&locale=%s&access_token=%s', $realm, $guild, $this->getWoWNamespace(),$this->_config['locale'], $this->_config['access_token']);
		$this->_debug('Guild: '.$wowurl);
		if((!$json	= $this->get_CachedData('guilddata_basic_'.$guild.$realm, $force)) && $this->_config['access_token']){
			$json	= $this->read_url($wowurl);
			
			$this->set_CachedData($json, 'guilddata_basic_'.$guild.$realm);
		}
		//get old data
		if(!$json) {
			$json = $this->get_CachedData('guilddata_basic_'.$guild.$realm, false, false, true);
		}
		$chardata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($chardata);
		
		return (!$errorchk) ? $chardata: $errorchk;
	}
	
	/**
	 * Fetch guild activity information
	 *
	 * @param $user		Character Name
	 * @param $realm		Realm Name
	 * @param $force		Force the cache to update?
	 * @return bol
	 */
	public function guildAchievements($guild, $realm, $force=false){
		$this->check_access_tocken();
		$realm = $this->translateRealmToSlug($realm);
		$guild	= $this->createSlug($guild);
		
		$wowurl	= $this->_config['apiUrl'].sprintf('data/wow/guild/%s/%s/achievements?namespace=%s&locale=%s&access_token=%s', $realm, $guild, $this->getWoWNamespace(),$this->_config['locale'], $this->_config['access_token']);
		$this->_debug('Guild: '.$wowurl);
		if((!$json	= $this->get_CachedData('guilddata_achievements_'.$guild.$realm, $force)) && $this->_config['access_token']){
			$json	= $this->read_url($wowurl);
			
			$this->set_CachedData($json, 'guilddata_achievements_'.$guild.$realm);
		}
		//get old data
		if(!$json) {
			$json = $this->get_CachedData('guilddata_achievements_'.$guild.$realm, false, false, true);
		}
		$chardata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($chardata);
		
		return (!$errorchk) ? $chardata: $errorchk;
	}
	
	

	/**
	* Generate guild tabard & save in cache
	*
	* @param $emblemdata	emblem data array of battle.net api
	* @param $faction		name of the faction
	* @param $guild			name of the guild
	* @param $imgwidth		width of the image
	* @return bol
	*/
	public function guildTabard($emblemdata, $faction, $guild, $imgwidth=215){
		$cached_img	= sprintf('image_tabard_%s_w%s.png', utf8_strtolower(str_replace(' ', '', $this->clean_name($guild))), $imgwidth);
		$imgfile_sp = $this->get_CachedData($cached_img, false, true, false, true);
		if(!$imgfile = $this->get_CachedData($cached_img, false, true)){
			if(!function_exists('imagecreatefrompng') || version_compare(PHP_VERSION, "5.3.0", '<')){
				return sprintf('games/wow/guild/tabard_%s.png', (($faction == 0) ? 'alliance' : 'horde'));
			}
			$imgfile	= $this->get_CachedData($cached_img, false, true, true);
			$imgfile_sp	= $this->get_CachedData($cached_img, false, true, true, true);

			// set the URL of the required image parts
			$img_emblem		= $this->_config['apiTabardRenderUrl'].sprintf('emblem_%02s', $emblemdata['icon']) .'.png';
			$img_border		= $this->_config['apiTabardRenderUrl']."border_".(($emblemdata['border'] == '-1') ? sprintf("%02s", $emblemdata['border']) : '00').".png";
			$img_ring		= $this->_config['apiTabardRenderUrl'].sprintf('ring-%s', (($faction == 0) ? 'alliance' : 'horde')) .'.png';
			$img_background	= $this->_config['apiTabardRenderUrl'].'bg_00.png';
			$img_shadow		= $this->_config['apiTabardRenderUrl'].'shadow_00.png';
			$img_overlay	= $this->_config['apiTabardRenderUrl'].'overlay_00.png';
			$img_hooks		= $this->_config['apiTabardRenderUrl'].'hooks.png';

			// set the image size (max width 215px) & generate the guild tabard image
			$img_resampled	= false;
			if ($imgwidth > 1 && $imgwidth < 215){
				$img_resampled	= true;
				$imgheight		= ($imgwidth/215)*230;
				$img_tabard		= imagecreatetruecolor($imgwidth, $imgheight);
				$tranparency	= imagecolorallocatealpha($img_tabard, 0, 0, 0, 127);
				imagefill($img_tabard, 0, 0, $tranparency);
				imagesavealpha($img_tabard,true);
				imagealphablending($img_tabard, true);
			}

			// generate the output image
			$img_genoutput	= imagecreatetruecolor(215, 230);
			imagesavealpha($img_genoutput,true);
			imagealphablending($img_genoutput, true);
			$tranparency	= imagecolorallocatealpha($img_genoutput, 0, 0, 0, 127);
			imagefill($img_genoutput, 0, 0, $tranparency);

			// generate the ring
			$ring			= imagecreatefrompng($img_ring);
			$ring_size		= getimagesize($img_ring);
			$emblem_image	= imagecreatefrompng($img_emblem);
			$emblem_size	= getimagesize($img_emblem);
			if($this->checkImageLayerEffect()){
				imagelayereffect($emblem_image, IMG_EFFECT_OVERLAY);
			}
			$tmp_emblemcolor= preg_replace('/^ff/i','',$emblemdata['iconColor']);
			$emblemcolor	= array(hexdec(substr($tmp_emblemcolor,0,2)), hexdec(substr($tmp_emblemcolor,2,2)), hexdec(substr($tmp_emblemcolor,4,2)));
			if($this->checkImageLayerEffect()){
				imagefilledrectangle($emblem_image,0,0,$emblem_size[0],$emblem_size[1],imagecolorallocate($emblem_image, $emblemcolor[0], $emblemcolor[1], $emblemcolor[2]));
			}else{
				$this->imageColorize($emblem_image, $emblemcolor[0], $emblemcolor[1], $emblemcolor[2]);
			}

			// generate the border
			$border			= imagecreatefrompng($img_border);
			$border_size	= getimagesize($img_border);
			if($this->checkImageLayerEffect()){
				imagelayereffect($border, IMG_EFFECT_OVERLAY);
			}
			$tmp_bcolor		= preg_replace('/^ff/i','',$emblemdata['borderColor']);
			$bordercolor	= array(hexdec(substr($tmp_bcolor,0,2)), hexdec(substr($tmp_bcolor,2,2)), hexdec(substr($tmp_bcolor,4,2)));
			if($this->checkImageLayerEffect()){
				imagefilledrectangle($border,0,0,$border_size[0]+100,$border_size[0]+100,imagecolorallocate($border, $bordercolor[0], $bordercolor[1], $bordercolor[2]));
			}else{
				$this->imageColorize($border, $bordercolor[0], $bordercolor[1], $bordercolor[2]);
			}

			// generate the background
			$shadow			= imagecreatefrompng($img_shadow);
			$bg				= imagecreatefrompng($img_background);
			$bg_size		= getimagesize($img_background);
			if($this->checkImageLayerEffect()){
				imagelayereffect($bg, IMG_EFFECT_OVERLAY);
			}
			$tmp_bgcolor	= preg_replace('/^ff/i','',$emblemdata['backgroundColor']);
			$bgcolor		= array(hexdec(substr($tmp_bgcolor,0,2)), hexdec(substr($tmp_bgcolor,2,2)), hexdec(substr($tmp_bgcolor,4,2)));
			if($this->checkImageLayerEffect()){
				imagefilledrectangle($bg,0,0,$bg_size[0]+100,$bg_size[0]+100,imagecolorallocate($bg, $bgcolor[0], $bgcolor[1], $bgcolor[2]));
			}else{
				$this->imageColorize($bg, $bgcolor[0], $bgcolor[1], $bgcolor[2]);
			}

			// put it together...
			imagecopy($img_genoutput,$ring,0,0,0,0, $ring_size[0],$ring_size[1]);
			$size			= getimagesize($img_shadow);
			imagecopy($img_genoutput,$shadow,20,23,0,0, $size[0],$size[1]);
			imagecopy($img_genoutput,$bg,20,23,0,0, $bg_size[0],$bg_size[1]);
			imagecopy($img_genoutput,$emblem_image,37,53,0,0, $emblem_size[0],$emblem_size[1]);
			imagecopy($img_genoutput,$border,32,38,0,0, $border_size[0],$border_size[1]);
			$size			= getimagesize($img_overlay);
			imagecopy($img_genoutput,imagecreatefrompng($img_overlay),20,25,0,0, $size[0],$size[1]);
			$size			= getimagesize($img_hooks);
			imagecopy($img_genoutput,imagecreatefrompng($img_hooks),18,23,0,0, $size[0],$size[1]);

			// check if the image is the same size as the image file parts, if not, resample the image
			if ($img_resampled){
				imagecopyresampled($img_tabard, $img_genoutput, 0, 0, 0, 0, $imgwidth, $imgheight, 215, 230);
			}else{
				$img_tabard = $img_genoutput;
			}

			$strTmpFolder = (is_object($this->pfh)) ? $this->pfh->FolderPath('tmp', '').$cached_img : $imgfile;

			//Create PNG
			imagepng($img_tabard,$strTmpFolder);

			//Move from tmp-Folder to right folder
			if (is_object($this->pfh)){
				$this->pfh->FileMove($strTmpFolder, $imgfile);
			}
			return $imgfile_sp;
		}
		return $imgfile_sp;
	}

	/**
	* Fetch realm information
	*
	* @param $realm		Realm Slug
	* @param $force		Force the cache to update?
	* @return bol
	*/
	public function realm($realm, $force=false){
		$realm = $this->translateRealmToSlug($realm);
		
		$this->check_access_tocken();
		$wowurl = $this->_config['apiUrl'].sprintf('/data/wow/realm/%s?namespace=%s&locale=%s&access_token=%s', $realm, $this->getWoWNamespace('dynamic'), $this->_config['locale'], $this->_config['access_token']);
		$this->_debug('Realm: '.$wowurl);
		if((!$json	= $this->get_CachedData('realmdata_'.str_replace(",", "", $realm), $force)) && $this->_config['access_token']){
			$json	= $this->read_url($wowurl);
			$this->set_CachedData($json, 'realmdata_'.str_replace(",", "", $realm));
		}
		$realmdata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($realmdata);
		return (!$errorchk) ? $realmdata: $errorchk;
	}
	
	/**
	 * Fetch connected realm information
	 *
	 * @param $id		Connected Realms ID
	 * @param $force		Force the cache to update?
	 * @return bol
	 */
	public function connectedRealms($id, $force=false){
		$this->check_access_tocken();
		$wowurl = $this->_config['apiUrl'].sprintf('/data/wow/connected-realm/%s?namespace=%s&locale=%s&access_token=%s', $id, $this->getWoWNamespace('dynamic'), $this->_config['locale'], $this->_config['access_token']);
		$this->_debug('Realm: '.$wowurl);
		if((!$json	= $this->get_CachedData('connectedrealmdata_'.str_replace(",", "", $id), $force)) && $this->_config['access_token']){
			$json	= $this->read_url($wowurl);
			$this->set_CachedData($json, 'connectedrealmdata_'.str_replace(",", "", $id));
		}
		$realmdata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($realmdata);
		return (!$errorchk) ? $realmdata: $errorchk;
	}
	
	/**
	 * Fetch the realmlist
	 *
	 * @param $force		Force the cache to update?
	 * @return bol
	 */
	public function realmlist($force=false){
		$this->check_access_tocken();
		$wowurl = $this->_config['apiUrl'].sprintf('/data/wow/realm/index?namespace=%s&locale=%s&access_token=%s', $this->getWoWNamespace('dynamic'), $this->_config['locale'], $this->_config['access_token']);
		$this->_debug('Realm: '.$wowurl);
		if((!$json	= $this->get_CachedData('realmindex_'.$this->_config['locale'], $force)) && $this->_config['access_token']){
			$json	= $this->read_url($wowurl);
			$this->set_CachedData($json, 'realmindex_'.$this->_config['locale']);
		}
		$realmdata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($realmdata);
		return (!$errorchk) ? $realmdata: $errorchk;
	}
	
	/**
	 * Translate a 
	 * 
	 * @param string $strRealm The Realmname, like "Fordragon" or "Дракономор"
	 * @return string
	 */
	public function translateRealmToSlug($strRealm){
		$realmList = $this->realmlist();
		$strRealm = unsanitize($strRealm);
		
		$slug = $this->createSlug($strRealm);
		
		if(is_array($realmList) && isset($realmList['realms'])){
			foreach($realmList['realms'] as $arrRealm){
				if($strRealm === $arrRealm['name']){
					return $this->ConvertInput($arrRealm['slug']);
				}
				
				if($slug === $arrRealm['slug']){
					return $this->ConvertInput($slug);
				}
			}
		}
		
		return $this->ConvertInput($slug);
	}
	
	
	/**
	* Fetch item information
	*
	* @param $itemid	battlenet Item ID
	* @param $force		Force the cache to update?
	* @return bol
	*/
	public function item($itemid, $force=false){
		$this->check_access_tocken();
		$tmp_itemid		= explode(':', $itemid);
		
		$wowurl = $this->_config['apiUrl'].sprintf('wow/item/%s?namespace=%s&locale=%s&access_token=%s', $tmp_itemid[0], $this->getWoWNamespace(), $this->_config['locale'], $this->_config['access_token']);

		$this->_debug('Item: '.$wowurl);
		if((!$json		= $this->get_CachedData('itemdata_'.$itemid, $force)) && $this->_config['access_token']){
			$json		= $this->read_url($wowurl);
			$metadata	= $this->eqdkpitemid_meta($itemid);
			$json		= $this->item_context($json, $metadata);
			if(is_array($json)) $json = json_encode($json);
			$this->set_CachedData($json, 'itemdata_'.$itemid);
		}

		$itemdata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($itemdata);
		return (!$errorchk) ? $itemdata: $errorchk;
	}

	public function armory2itemid($itemid, $context, $bonuslist=array(), $itemlevel='0', $relics=array()){
		switch($context){
			case 'raid-normal':		$item_difficulty = '1'; break;
			case 'raid-heroic':		$item_difficulty = '15'; break;
			case 'raid-mythic':		$item_difficulty = '16'; break;
			case 'raid-finder':		$item_difficulty = '7'; break;
			case 'trade-skill':		$item_difficulty = '99'; break;
			default:				$item_difficulty = '1'; break;
		}

		//itemID:enchantID:gemID1:gemID2:gemID3:gemID4:suffixID:uniqueID:linkLevel:specializationID:upgradeTypeID:instanceDifficultyID:numBonusIDs[:bonusID1:bonusID2:...]
		return $itemid.':0:0:0:0:0:0:0:'.$itemlevel.':0:0:'.$item_difficulty.':'.count($bonuslist).':'.implode(':',$bonuslist);
	}

	public function eqdkpitemid_meta($item_id){
		//itemID:enchant:gem1:gem2:gem3:gem4:suffixID:uniqueID:level:specializationID:upgradeType:instanceDifficultyID:numBonusIDs:bonusID1:bonusID2...:upgradeId
		$arrItemData = explode(':', $item_id);
		if(!is_array($arrItemData) || (is_array($arrItemData) && count($arrItemData)<5)) { return false; }

		// 3 and 4 are normal, 5 and 6 are heroic
		$difficulty	= (isset($arrItemData[11])) ? $arrItemData[11] : 0;

		switch($difficulty){
			case '0':
			case '1':
			case '3':
			case '4':
			case '9':
			case '14':	$itemdiff = 'normal'; break;

			case '2':
			case '5':
			case '6':
			case '11':
			case '15':	$itemdiff = 'heroic'; break;

			case '16':
			case '23':	$itemdiff = 'mythic'; break;
			case '7':	$itemdiff = 'finder'; break;
			case '99':	$itemdiff = 'skill'; break;
			default:	$itemdiff = 'normal'; break;
		}
		return array(
			'difficulty'	=> $itemdiff,
			'bonuslist'		=> (isset($arrItemData[12]) && $arrItemData[12] > 1) ? array_slice($arrItemData, 13, $arrItemData[12]) : 0,
			'gems'			=> (isset($arrItemData[2]) || isset($arrItemData[3]) || isset($arrItemData[4])) ? array_slice($arrItemData, 2, 4) : array(),
			'lvl'			=> (isset($arrItemData[8])) ? $arrItemData[8] : 0,
			'specID'		=> (isset($arrItemData[9])) ? $arrItemData[9] : 0,
			'upgdType'		=> (isset($arrItemData[10])) ? $arrItemData[10] : 0,
			'upgd'			=> (end($arrItemData) !== false) ? end($arrItemData) : 0,
			'enchant'		=> (isset($arrItemData[1])) ? $arrItemData[1] : 0,
		);
	}

	//{"id":110050,"availableContexts":["dungeon-level-up-1","dungeon-level-up-2","dungeon-level-up-3","dungeon-level-up-4","dungeon-normal","dungeon-heroic"]}
	public function item_context($itemdata, $itemmetadata){
		$this->check_access_tocken();
		if($itemmetadata){
			$itemdata		= json_decode($itemdata, true);
			$bonuslist		= (isset($itemmetadata['bonuslist'])) ? '&bl='.implode(',',$itemmetadata['bonuslist']) : '';
			$contextname	= array_values($this->helper_partialmatch($itemmetadata['difficulty'], $itemdata['availableContexts']))[0];
			$availContexts	= $itemdata['availableContexts'];
			$itemid			= $itemdata['id'];
		}else{
			$itemdata_tmp	= json_decode($itemdata, true);
			$bonuslist		= '';
			$availContexts	= (isset($itemdata_tmp['availableContexts']) &&$itemdata_tmp['availableContexts'][0] != '') ? $itemdata_tmp['availableContexts'] : false;
			$contextname	= (in_array('raid-normal', $itemdata_tmp['availableContexts'])) ? 'raid-normal' : $itemdata_tmp['availableContexts'][0];
			$itemid			= $itemdata_tmp['id'];
		}

		if(isset($availContexts) && is_array($availContexts) && count($availContexts) > 0 && isset($contextname)){

			$wowurl		= $this->_config['apiUrl'].sprintf('wow/item/%s/%s?locale=%s&access_token=%s%s', $itemid, $contextname, $this->_config['locale'], $this->_config['access_token'],$bonuslist);
			return $this->read_url($wowurl);
		} elseif($bonuslist != ""){
			$wowurl		= $this->_config['apiUrl'].sprintf('wow/item/%s?locale=%s&access_token=%s%s', $itemid, $this->_config['locale'], $this->_config['access_token'],$bonuslist);
			$tmpdata 	= $this->read_url($wowurl);
			$tmpjson	= json_decode($tmpdata, true);
			if($itemmetadata['lvl'] > 0 && $tmpjson['itemLevel'] != $itemmetadata['lvl']){
				$tmpjson['itemLevel'] = $itemmetadata['lvl'];
			}
			return json_encode($tmpjson);
		}
		return $itemdata;
	}

	private function helper_partialmatch($search_text, $array){
		return preg_grep("/".$search_text."/", $array);
	}

	/**
	* Fetch achievement information
	*
	* @param $achievementid		battlenet Achievement ID
	* @param $force				Force the cache to update?
	* @return bol
	*/
	public function achievement($achievementid, $media=false, $force=false){
		$this->check_access_tocken();
		
		if($media){
			$cache = "_media";
			$wowurl = $this->_config['apiUrl'].sprintf('data/wow/media/achievement/%s?namespace=%s&locale=%s&access_token=%s', $achievementid, $this->getWoWNamespace('static'), $this->_config['locale'], $this->_config['access_token']);
		} else {
			$cache = "";
			$wowurl = $this->_config['apiUrl'].sprintf('data/wow/achievement/%s?namespace=%s&locale=%s&access_token=%s', $achievementid, $this->getWoWNamespace('static'), $this->_config['locale'], $this->_config['access_token']);			
		}
		
		$this->_debug('Achievement: '.$wowurl);
		
		if((!$json	= $this->get_CachedData('achievementdata_'.$achievementid.$cache, $force)) && $this->_config['access_token']){
			$json	= $this->read_url($wowurl);
			$this->set_CachedData($json, 'achievementdata_'.$achievementid.$cache);
		}
		$achievementdata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($achievementdata);
		return (!$errorchk) ? $achievementdata : $errorchk;
	}

	/**
	* Fetch recipe information
	*
	* @param $questid	battlenet quest ID
	* @param $force		Force the cache to update?
	* @return bol
	*/
	public function recipe($recipeid, $force=false){
		$this->check_access_tocken();
		$wowurl = $this->_config['apiUrl'].sprintf('wow/recipe/%s?namespace=%s&locale=%s&access_token==%s', $recipeid, $this->getWoWNamespace(), $this->_config['locale'], $this->_config['access_token']);
		$this->_debug('Recipe: '.$wowurl);
		if((!$json	= $this->get_CachedData('recipedatadata_'.$recipeid, $force)) && $this->_config['access_token']){
			$json	= $this->read_url($wowurl);
			$this->set_CachedData($json, 'recipedatadata_'.$recipeid);
		}
		$recipe	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($recipe);
		return (!$errorchk) ? $recipe : $errorchk;
	}

	/**
	* Fetch spell information
	*
	* @param $questid	battlenet quest ID
	* @param $force		Force the cache to update?
	* @return bol
	*/
	public function spell($spellid, $media=false,$force=false){
		$this->check_access_tocken();
		if($media){
			$cache = "_media";
			$wowurl = $this->_config['apiUrl'].sprintf('data/wow/media/spell/%s?namespace=static-%s&locale=%s&access_token=%s', $spellid, $this->_config['serverloc'], $this->_config['locale'], $this->_config['access_token']);
		} else {
			$cache = "";
			$wowurl = $this->_config['apiUrl'].sprintf('data/wow/spell/%s?namespace=static-%s&locale=%s&access_token=%s', $spellid, $this->_config['serverloc'], $this->_config['locale'], $this->_config['access_token']);
		}
		$this->_debug('Spell: '.$wowurl);
		
		if((!$json	= $this->get_CachedData('spelldatadata_'.$spellid.$cache, $force)) && $this->_config['access_token']){
			$json	= $this->read_url($wowurl);
			$this->set_CachedData($json, 'spelldatadata_'.$spellid.$cache);
		}
		$spell		= json_decode($json, true);
		$errorchk	= $this->CheckIfError($spell);
		return (!$errorchk) ? $spell : $errorchk;
	}
	
	/**
	 * Fetch expansion information
	 *
	 * @param $questid	battlenet quest ID
	 * @param $force		Force the cache to update?
	 * @return bol
	 */
	public function instance($spellid, $media=false,$force=false){
		$this->check_access_tocken();
		if($media){
			$wowurl = $this->_config['apiUrl'].sprintf('data/wow/media/journal-instance/%s?namespace=static-%s&locale=%s&access_token=%s', $spellid, $this->_config['serverloc'], $this->_config['locale'], $this->_config['access_token']);
		} else {
			$wowurl = $this->_config['apiUrl'].sprintf('data/wow/journal-instance/%s?namespace=static-%s&locale=%s&access_token=%s', $spellid, $this->_config['serverloc'], $this->_config['locale'], $this->_config['access_token']);
		}
		$this->_debug('Expansion: '.$wowurl);
		if((!$json	= $this->get_CachedData('instancedata_'.$spellid.'_'.($media), $force)) && $this->_config['access_token']){
			$json	= $this->read_url($wowurl);
			$this->set_CachedData($json, 'instancedata_'.$spellid.'_'.($media));
		}
		$spell		= json_decode($json, true);
		$errorchk	= $this->CheckIfError($spell);
		return (!$errorchk) ? $spell : $errorchk;
	}

	/**
	* Fetch challenge mode information
	*
	* @param $realm		battlenet realm
	* @param $force		Force the cache to update?
	* @return bol
	*/
	public function challenge($realm, $force=false){
		$this->check_access_tocken();
		$wowurl = $this->_config['apiUrl'].sprintf('wow/challenge/%s?namespace=%s&locale=%s&access_token=%s', $this->ConvertInput($realm), $this->getWoWNamespace(), $this->_config['locale'], $this->_config['access_token']);
		$this->_debug('Challenge: '.$wowurl);
		if((!$json	= $this->get_CachedData('challengedatadata_'.$realm, $force)) && $this->_config['access_token']){
			$json	= $this->read_url($wowurl);
			$this->set_CachedData($json, 'challengedatadata_'.$realm);
		}
		$challengedata	= json_decode($json, true);
		$errorchk		= $this->CheckIfError($challengedata);
		return (!$errorchk) ? $challengedata : $errorchk;
	}

	/**
	* The boss API provides information about bosses. A 'boss' in this context should be considered a boss encounter, which may include more than one NPC.
	*
	* @param $bossid	Boss ID, if non alle are listed
	* @param $force		Force the cache to update?
	* @return bol
	*/
	public function boss($bossid, $media=false, $force=false){
		$this->check_access_tocken();
		
		$wowurl = $this->_config['apiUrl'].sprintf('data/wow/journal-encounter/%s?namespace=%s&locale=%s&access_token=%s', $this->ConvertInput($bossid), $this->getWoWNamespace('static'), $this->_config['locale'], $this->_config['access_token']);
		$this->_debug('Boss: '.$wowurl);
		if((!$json	= $this->get_CachedData('bossdatadata_'.$bossid, $force)) && $this->_config['access_token']){
			$json	= $this->read_url($wowurl);
			$this->set_CachedData($json, 'bossdatadata_'.$bossid);
		}
		$bossdata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($bossdata);
		return (!$errorchk) ? $bossdata : $errorchk;
	}

	// DATA RESOURCES
	public function getdata($type='character', $sub_type='achievements', $force=false){
		$this->check_access_tocken();
		$wowurl	= $this->_config['apiUrl'].sprintf('wow/data/'.$type.'/'.$sub_type.'?namespace=%s&locale=%s&access_token=%s', $this->getWoWNamespace(), $this->_config['locale'], $this->_config['access_token']);
		$this->_debug('Data Resource: '.$wowurl);
		if((!$json	= $this->get_CachedData('data_'.$type.'_'.$sub_type, $force)) && $this->_config['access_token']){
			$this->downloadORwait();
			$json	= $this->read_url($wowurl);
			$this->set_lastdownload();
			$this->set_CachedData($json, 'data_'.$type.'_'.$sub_type);
		}
		$chardata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($chardata);
		return (!$errorchk) ? $chardata: $errorchk;
	}

	private function downloadORwait(){
		// one second = 1000000 ms. As the limit is 10/s, we have to wait 100000 = 0.1s
		list($int,$dec)=explode('.', $this->get_lastdownload());
		$rate 		= 1000000/$this->ratepersecond;
		$time2wait	= $rate-((int)$dec);
		if($time2wait > 0){
			#usleep($time2wait);
		}
	}

	private function get_lastdownload(){
		$rfilename	= (is_object($this->pfh)) ? $this->pfh->FolderPath('armory', 'cache').'_lastdownload' : 'data/_times';
		if(is_file($rfilename)){
			return @file_get_contents($rfilename);
		}
	}

	private function set_lastdownload(){
		$this->pfh->putContent($this->pfh->FolderPath('armory', 'cache').'_lastdownload', microtime());
	}

	/**
	 * Returns Category for Achievement-ID, usefull for Armory-Links
	 *
	 * @int 	$intAchievID					Armory Achievement-ID
	 * @array 	$arrAchievementData	Difference  Achievement-Data, e.g. from Armory Resources
	 * @return 	formatted String				10588 or 10589:92
	 */
	function getCategoryForAchievement($intAchievID, $arrAchievementData){
		foreach($arrAchievementData['achievements'] as $arrAchievs){
			$intCatID = $arrAchievs['id'];
			foreach ($arrAchievs['achievements'] as $arrAchievs2){
				if ((int)$arrAchievs2['id'] == $intAchievID) return $intCatID;
			}

			if (isset($arrAchievs['categories'])){
				foreach ($arrAchievs['categories'] as $arrCatAchievs2){
					$intNewCatID = $intCatID;
					foreach ($arrCatAchievs2['achievements'] as $arrCatAchievs3){
						if ((int)$arrCatAchievs3['id'] == $intAchievID) return $intNewCatID;
					}
				}
			}
		}
	}

	/**
	 * Mapping from integer AchievementCategoryID to String
	 *
	 * PvP: 95 -> player-vs-player
	 *
	 * @param int $intCategoryID
	 * @return string
	 */
	function achievementIDMapping($intCategoryID){
		$arrMapping = array(
			92 => 'general',
			96 => 'quests',
			97 => 'exploration',
			95 => 'player-vs-player',
			168 => 'dungeons-raids',
			169 => 'professions',
			201 => 'reputation',
			155 => 'world-events',
			15117 => 'pet-battles',
			15246 => 'collections',
			15275 => 'class-hall',
			15237 => 'draenor-garrison',
			15165 => 'scenarios',
			15234 => 'legacy',
			81 => 'feats-of-strength',
		);

		if(isset($arrMapping[$intCategoryID])) return $arrMapping[$intCategoryID];

		return "";
	}


	/**
	* Check if the JSON is an error result
	*
	* @param $data		XML Data of Char
	* @return error code
	*/
	protected function CheckIfError($data){
		if(isset($data['code'])){
			return array(
				'status'	=> $data['code'],
				'reason'	=> ((isset($data['detail'])) ? $data['detail'] : false)
			);
		}elseif(is_array($data) && count($data) == 0){
			return array(
				'status'	=> 'nok',
				'reason'	=> 'Battle.net API returned an empty array.'
			);
		}
		return false;
	}

	/**
	* Clean the Servername if taken from Database
	*
	* @return string output
	*/
	public function cleanServername($server){
		return strtolower(html_entity_decode($server,ENT_QUOTES,"UTF-8"));
	}

	/**
	* Convert from Armory ID to EQDKP Id or reverse
	*
	* @param $name			name/id to convert
	* @param $type			int/string?
	* @param $cat			category (classes, races, months)
	* @param $ssw			if set, convert from eqdkp id to armory id
	* @return string/int output
	*/
	public function ConvertID($name, $type, $cat, $ssw=false){
		if($ssw){
			if(!is_array($this->converts[$cat])){
				$this->converts[$cat] = array_flip($this->convert[$cat]);
			}
			return ($type == 'int') ? $this->converts[$cat][(int) $name] : $this->converts[$cat][$name];
		}else{
			return ($type == 'int') ? $this->convert[$cat][(int) $name] : $this->convert[$cat][$name];
		}
	}

	/**
	* Convert talent from icon to id
	*
	* @param $name			name/id to convert
	* @return string/int output
	*/
	public function ConvertTalent($name){
		return key(search_in_array($name, $this->convert['talent']));
	}

	/**
	* Prepare a string for beeing sent to armory
	*
	* @param $input
	* @return string output
	*/
	public function ConvertInput($input, $removeslash=false, $removespace=false){
		// new servername convention: mal'ganis = malganis
		$input = ($removespace) ? str_replace(" ", "-", $input) : $input;
		if($removeslash){
			return stripslashes(str_replace("'", "", $input));
		} else {
			if(strpos($input, "%") === false){
				$input = rawurlencode($input);
			}
			
			return stripslashes($input);
		}
	}

	
	/**
	 * Creates a slug for the given Servername
	 * 
	 * @param string $strServername 
	 * @return string Slug
	 */
	public function createSlug($strServername){
		$str = str_replace("'", "", $strServername);
		$str = str_replace(" ", "-", $str);
		
		$str = utf8_strtolower($str);
		$str = rawurlencode($str);
		
		return $str;
	}
	
	/**
	* Write JSON to Cache
	*
	* @param	$json		XML string
	* @param	$filename	filename of the cache file
	* @return --
	*/
	protected function set_CachedData($json, $filename, $binary=false){
		if($this->_config['caching']){
			$cachinglink = $this->binaryORdata($this->clean_name($filename), $binary);
			if(is_object($this->pfh)){
				$this->pfh->putContent($this->pfh->FolderPath('armory', 'cache').$cachinglink, $json);
			}else{
				file_put_contents('data/'.$cachinglink, $json);
			}
		}
	}

	/**
	* get the cached JSON if not outdated & available
	*
	* @param	$filename	filename of the cache file
	* @param	$force		force an update of the cached json file
	* @return --
	*/
	protected function get_CachedData($filename, $force=false, $binary=false, $returniffalse=false, $returnServerPath=false){
		if(!$this->_config['caching']){return false;}

		$data_ctrl = false;
		$rfilename	= (is_object($this->pfh)) ? $this->pfh->FolderPath('armory', 'cache').$this->binaryORdata($this->clean_name($filename), $binary) : 'data/'.$this->binaryORdata($this->clean_name($filename), $binary);
		$rfilenameSP= (is_object($this->pfh)) ? $this->pfh->FolderPath('armory', 'cache', 'serverpath').$this->binaryORdata($this->clean_name($filename), $binary) : 'data/'.$this->binaryORdata($this->clean_name($filename), $binary);
		if(is_file($rfilename)){
			$data_ctrl	= (!$force && (filemtime($rfilename)+(3600*$this->_config['caching_time'])) > time()) ? true : false;
		}
		return ($data_ctrl || $returniffalse) ? (($binary) ? (($returnServerPath) ? $rfilenameSP : $rfilename ) : @file_get_contents($rfilename)) : false;
	}

	/**
	* strip all non conform chars out of the filename
	*
	* @param	$name	name of the file to be cleaned
	* @return --
	*/
	protected function clean_name($name){
		return preg_replace('/[^a-zA-Z0-9_ \.\-]/s', '_', $name);
	}

	/**
	* delete the cached data
	*
	* @return --
	*/
	public function DeleteCache(){
		if(!$this->_config['caching']){return false;}
		$rfoldername	= (is_object($this->pfh)) ? $this->pfh->FolderPath('armory', 'cache') : 'data/';
		return $this->pfh->Delete($rfoldername);
	}

	/**
	* check if binary files or json/data
	*
	* @param	$input	the input
	* @param	$binary	true/false
	* @return --
	*/
	protected function binaryORdata($input, $binary=false){
		return ($binary) ? $input : 'data_'.$this->_config['locale'].md5($input);
	}

	/**
	* set the API Url
	*
	* @param	$serverloc	the location of the server
	* @return --
	*/
	protected function setApiUrl($serverloc){
		$this->_config['apiUrl']				= str_replace('{region}', $serverloc, self::apiurl);
		$this->_config['apiRenderUrl']			= str_replace('{region}', $serverloc, self::staticrenderurl);
		$this->_config['staticimageURL']		= str_replace('{region}', $serverloc, self::staticimages);
		$this->_config['apiTabardRenderUrl']	= str_replace('{region}', $serverloc, self::tabardrenderurl);
		$this->_config['staticiconURL']			= str_replace('{region}', $serverloc, self::staticicons);
		$this->_config['charImageURL']			= str_replace('{region}', $serverloc, self::charimageurl);
		$this->_config['oauthurl']				= str_replace('{region}', $serverloc, self::oauthurl);
	}

	/**
	* Fetch the Data from URL
	*
	* @param $url URL to Download
	* @return json
	*/
	protected function read_url($url, $intTimeout=false) {
		if(!is_object($this->puf)) {
			global $eqdkp_root_path;
			include_once($eqdkp_root_path.'core/urlfetcher.class.php');
			$this->puf = new urlfetcher();
		}
		
		$mixResponse = $this->puf->fetch($url, '', false, $intTimeout);
		
		if($mixResponse === false && method_exists($this->puf, 'fetch_last_response')){
			$arrResponseData = $this->puf->fetch_last_response();
			if($arrResponseData['responseBody']){
				return $arrResponseData['responseBody'];
			} elseif($arrResponseData['responseCode'] > 400){
				return '{"status":"nok", "reason": "Error #'.$arrResponseData['responseCode'].'"}';
			}
		}
		
		return $mixResponse;
	}

	/**
	* Check if an error occured
	*
	* @return error
	*/
	public function CheckError(){
		return ($this->error) ? $this->error : false;
	}

	private function has_json_data($string) {
		$array = json_decode($string, true);
		return !empty($string) && is_string($string) && is_array($array) && !empty($array) && json_last_error() == 0;
	}

	/**
	* Check if the function imagelayereffect for GD is available
	*
	* @return true/false
	*/
	private function checkImageLayerEffect(){
		$gdInfo		= gd_info();
		if (function_exists('imagelayereffect') && strpos($gdInfo['GD Version'], 'bundled')) {
			return true;
		}
	}

	/**
	* Loop over image, and colorize non transparent pixels.
	*
	* Simulates the following two lines:  (see above)
	*   imagelayereffect($image, IMG_EFFECT_OVERLAY);
	*   imagefilledrectangle($image,0,0,$emblem_size[0],$emblem_size[1],imagecolorallocatealpha($image, $color_r, $color_g, $color_b,0));
	*
	* Source: http://drupal.org/node/1154970
	*
	* @param Image $image
	* @param int $r_overlay
	* @param int $g_overlay
	* @param int $b_overlay
	*/
	private function imageColorize(&$image, $r_overlay, $g_overlay, $b_overlay) {
		$height		= imagesy($image);
		$width		= imagesx($image);

		for($y=0;$y<$height;$y++) {
			for($x=0;$x<$width;$x++) {
				$rgb		= imagecolorat($image, $x, $y);
				$alpha		= ($rgb >> 24) & 0xFF;
				$r_source	= ($rgb >> 16) & 0xFF;
				$g_source	= ($rgb >> 8) & 0xFF;
				$b_source	= $rgb & 0xFF;

				// Tweak this number if overlay looks weird.  (0 = Fully transparent, 127 = No transparancy)
				if ($alpha < 50) {
					if($r_source <= 128) {
						$final_r = (2 * $r_source * $r_overlay) / 256;
					}else{
						$final_r = 255 - (((255 - (2 * ($r_source - 128))) * (255 - $r_overlay)) / 256);
					}
					if($g_source <= 128) {
						$final_g = (2 * $g_source * $g_overlay) / 256;
					}else{
						$final_g = 255 - (((255 - (2 * ($g_source - 128))) * (255 - $g_overlay)) / 256);
					}
					if ($b_source <= 128) {
						$final_b = (2 * $b_source * $b_overlay) / 256;
					}else{
						$final_b = 255 - (((255 - (2 * ($b_source - 128))) * (255 - $b_overlay)) / 256);
					}
					$final_colour = imagecolorallocate($image, $final_r, $final_g, $final_b);
					imagesetpixel($image, $x, $y, $final_colour);
				}
			}
		}
	}
}
?>
