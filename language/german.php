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
$german_array = array(
	'factions' => array(
		'alliance'	=> 'Allianz',
		'horde'		=> 'Horde'
	),
	'races' => array(
		0	=> 'Unbekannt',
		1	=> 'Gnom',
		2	=> 'Mensch',
		3	=> 'Zwerg',
		4	=> 'Nachtelf',
		5	=> 'Troll',
		6	=> 'Untoter',
		7	=> 'Ork',
		8	=> 'Taure',
		9	=> 'Draenei',
		10	=> 'Blutelf',
		11	=> 'Worg',
		12	=> 'Goblin',
		13	=> 'Pandaren',
		14	=> 'Nachtgeborener', // Horde
		15	=> 'Hochbergtauren', // Horde
		16	=> 'Leerenelf', // Alliance
		17	=> 'Lichtgeschmiedeter Draenei', // Alliance
		18	=> 'Dunkeleisen-Zwerg', // Alliance
		19	=> 'Orcs der Mag\'har', // Horde
		20 	=> 'Zandalari Troll', // Horde
		21 	=> 'Kul Tiran', // Alliance
		22	=> 'Vulpera', // Horde
		23	=> 'Mechagnom', // Alliance
	),
	'classes' => array(
		0	=> 'Unbekannt',
		1	=> 'Todesritter',
		2	=> 'Druide',
		3	=> 'Jäger',
		4	=> 'Magier',
		5	=> 'Paladin',
		6	=> 'Priester',
		7	=> 'Schurke',
		8	=> 'Schamane',
		9	=> 'Hexenmeister',
		10	=> 'Krieger',
		11	=> 'Mönch',
		12	=> 'Dämonenjäger',
	),
	'talents'		=> array(
		// Death Knight
		0 	=> 'Blut',
		1 	=> 'Frost',
		2 	=> 'Unheilig',
		// Druid
		3 	=> 'Gleichgewicht',
		4 	=> 'Wildheit',
		5 	=> 'Wächter',
		6 	=> 'Wiederherstellung',
		// Hunter
		7 	=> 'Tierherrschaft',
		8 	=> 'Treffsicherheit',
		9 	=> 'Überleben',
		// Mage
		10 	=> 'Arkan',
		11 	=> 'Feuer',
		12 	=> 'Frost',
		// Paladin
		13 	=> 'Heilig',
		14 	=> 'Schutz',
		15 	=> 'Vergeltung',
		// Priest
		16 	=> 'Disziplin',
		17 	=> 'Heilig',
		18 	=> 'Schatten',
		// Rogue
		19 	=> 'Meucheln',
		20 	=> 'Gesetztlosigkeit',
		21 	=> 'Täuschung',
		// Shaman
		22 	=> 'Elementar',
		23 	=> 'Verstärkung',
		24 	=> 'Wiederherstellung',
		// Warlock
		25 	=> 'Gebrechen',
		26 	=> 'Dämonologie',
		27 	=> 'Zerstörung',
		// Warrior
		28 	=> 'Waffen',
		29 	=> 'Furor',
		30 	=> 'Schutz',
		// Monk
		31 	=> 'Braumeister',
		32 	=> 'Nebelwirker',
		33 	=> 'Windläufer',
		// demon hunter
		34	=> 'Verwüstung',
		35	=> 'Rachsucht',
	),
	'roles' => array(
		1	=> 'Heiler',
		2	=> 'Tank',
		3	=> 'DD Fernkampf',
		4	=> 'DD Nahkampf',
	),
	'professions' => array(
		'trade_alchemy'					=> 'Alchemie',
		'trade_blacksmithing'			=> 'Schmiedekunst',
		'trade_engraving'				=> 'Verzauberkunst',
		'trade_engineering'				=> 'Ingenieurskunst',
		'trade_herbalism'				=> 'Kräuterkunde',
		'inv_inscription_tradeskill01'	=> 'Inschriftenkunde',
		'inv_misc_gem_01'				=> 'Juwelenschleifen',
		'trade_leatherworking'			=> 'Lederverarbeitung',
		'inv_pick_02'					=> 'Bergbau',
		'inv_misc_pelt_wolf_01'			=> 'Kürschnerei',
		'trade_tailoring'				=> 'Schneiderei',
	),
	'lang' => array(
		'wow'			=> 'World of Warcraft',
		'plate'			=> 'Platte',
		'cloth'			=> 'Stoff',
		'leather'		=> 'Leder',
		'mail'			=> 'Schwere Rüstung',
		'tier_token'	=> 'Token: ',
		'talents_tt_1'	=> 'Primäres Talent',
		'talents_tt_2'	=> 'Sekundäres Talent',
		'caltooltip_itemlvl'	=> 'Item-Level',

		// Profile information
		'uc_prof_professions'			=> 'Berufe',
		'skills'						=> 'Talente',
		'corevalues'					=> 'Grundwerte',
		'values'						=> 'Werte',

		// Profile information
		'uc_achievements'				=> 'Erfolge',
		'uc_bosskills'					=> 'Boss Kills',
		'uc_bar_rage'					=> 'Wut',
		'uc_bar_energy'					=> 'Energie',
		'uc_bar_mana'					=> 'Mana',
		'uc_bar_focus'					=> 'Fokus',
		'uc_bar_runic-power'			=> 'Runenmacht',
		'uc_bar_maelstrom'			=> 'Mahlstrom',

		'uc_skill1'						=> 'Talente 1',
		'uc_skill2'						=> 'Talente 2',

		'pv_tab_profiles'				=> 'Externe Profile',
		'pv_tab_talents'				=> 'Skillung',

		'uc_guild'						=> 'Gilde',
		'uc_bar_health'					=> 'Gesundheit',
		'uc_bar_2value'					=> 'Wert der 2. Leiste',
		'uc_bar_2name'					=> 'Name der 2. Leiste',

		'uc_gender'						=> 'Geschlecht',
		'uc_male'						=> 'Männlich',
		'uc_female'						=> 'Weiblich',
		'uc_faction'					=> 'Fraktion',
		'uc_faction_help'				=> 'Die Fraktion im Spiel',
		'uc_fact_horde'					=> 'Horde',
		'uc_fact_alliance'				=> 'Allianz',
		'uc_race'						=> 'Rasse',
		'uc_class'						=> 'Klasse',
		'uc_talent1'					=> 'Primäre Talentspezialisierung',
		'uc_talent2'					=> 'Sekundäre Talentspezialisierung',
		'uc_level'						=> 'Level',

		'uc_prof1_value'				=> 'Level des Hauptberufes',
		'uc_prof1_name'					=> 'Name des Hauptberufes',
		'uc_prof2_value'				=> 'Level des Sekundärberufes',
		'uc_prof2_name'					=> 'Name des Sekundärberufs',

		'uc_achievement_tab_default'	=> 'Ungruppiert',
		'uc_achievement_tab_classic'	=> 'Classic',
		'uc_achievement_tab_bc'			=> 'Burning Crusade',
		'uc_achievement_tab_wotlk'		=> 'Wrath of the Lich King',
		'uc_achievement_tab_cataclysm'	=> 'Cataclysm',
		'uc_achievement_tab_mop'		=> 'Mists of Pandaria',
		'uc_achievement_tab_wod'		=> 'Warlords of Draenor',
		'uc_achievement_tab_leg'		=> 'Legion',
		"uc_achievement_tab_bfa"		=> 'Battle for Azeroth',

		'challenge'						=> 'Herausforderungsmodus',
		'challenge_title'				=> 'Herausforderungsmodus: Ranglisten',
		'off_realm_toon'				=> 'Dieser Charakter scheint nicht in deiner Gilde zu sein. Da die Herausforderungen realmübergreifend sind, können auch fremde Charakter in dieser Liste auftauchen.',

		// Profile Admin area
		'core_sett_fs_gamesettings'		=> 'WoW Einstellungen',
		'importer_head_txt'				=> 'battle.net Importer',
		'servername_help'				=> 'Servername des Spielservers (z.B. Mal\'Ganis)',
		'uc_update_all'					=> 'Von battle.net aktualisieren',
		'uc_update_all_help'			=> 'Alle Profilinformationen mit Profilerdaten von battle.net aktualisieren',
		'uc_importer_cache'				=> 'Leere Cache des Importers',
		'uc_importer_cache_help'		=> 'Löscht alle gecachten Daten aus der importer Class.',
		'uc_import_guild'				=> 'Gilde vom battle.net importieren',
		'uc_import_guild_help'			=> 'Importiere alle Mitglieder einer Gilde vom battle.net',
		'uc_server_loc'					=> 'Server Standort',
		'uc_server_loc_help'			=> 'Der Standort des WoW Game Servers',
		'uc_data_lang'					=> 'Sprache der Daten',
		'uc_data_lang_help'				=> 'In welcher Sprache sollen die Daten vom externen Anbieter geladen werden?',
		'uc_profile_boskills_hide'		=> 'Profilansicht: Bosse folgender alten Erweiterungen ausblenden',
		'uc_profile_boskills_hide_help'	=> 'Hier alle WoW Erweiterungen auswählen, von denen keine Bosskills mehr im Profil der Charaktere angezeigt werden sollen.',
		'uc_error_head'					=> 'FEHLER',
		'uc_error_noserver'				=> 'Es wurde kein Server in den globalen Einstellungen gefunden. Dieser wird für die Nutzung dieses Features jedoch benötigt. Bitte benachrichtige einen Administrator.',
		'uc_error_nodata_bnet'			=> 'Die battle.net API lieferte unvollständige Datensätze. Bitte versuche es später erneut.',
		'game_importer_clientid'		=> 'Client ID',
		'game_importer_clientsecret'	=> 'Client Secret',
		'apikey_title_step1'			=> 'Client Informationen',
		'apikey_content_step1'			=> 'Bei der neuen OAUTH basierten API muss jede App eine eigene Client-ID sowie ein Client-Secret für den Datenabruf erstellen. Ohne diese benutzerbezogenen Daten können keine Charaketere von battle.net aktualisiert oder importiert werden. Diese Anleitung hilft dir beim Registrieren der Client-Credentials.',
		'apikey_title_step2'			=> 'Erhalte kostenlose Client-Credentials',
		'apikey_content_step2'			=> 'Besuche die <a href="https://develop.battle.net/access/clients" target="_blank">API Access</a> Seite und logge dich ein oder erstelle einen neuen Account. Registriere einen <a href="https://develop.battle.net/access/clients" target="_blank">neuen Klienten</a> und benutze dazu den Gildenamen, die Webseite und eine Kurzbeschreibung.',
		'apikey_title_step3'			=> 'Gib die Client-Credentials ein',
		'apikey_content_step3'			=> 'Gib hier den die Client-ID sowie das Client-Secret ein.{APIKEY_FORM}',
		// Armory Import
		"uc_updat_armory" 				=> "Von worldofwarcraft.com aktualisieren",
		'uc_charname'					=> 'Charaktername',
		'servername'					=> 'Realm-Name',
		'uc_charfound'					=> "Der Charakter <b>%1\$s</b> wurde im battle.net gefunden.",
		'uc_charfound2'					=> "Das letzte Update dieses Charakters war am <b>%1\$s</b>.",
		'uc_charfound3'					=> 'ACHTUNG: Beim Import werden bisher gespeicherte Daten überschrieben!',
		'uc_charfound4'					=> 'ACHTUNG: Der Charakter wurde schon im System gefunden. Er wird daher nicht importiert sondern aktualisiert.',
		'uc_armory_imported'			=> 'Charakter erfolgreich importiert',
		'uc_armory_updated'				=> 'Charakter erfolgreich aktualisiert',
		'uc_armory_impfailed'			=> 'Charakter nicht importiert',
		'uc_armory_updfailed'			=> 'Charakter nicht aktualisiert',
		'uc_armory_impfail_reason'		=> 'Grund:',
		"uc_armory_import_unknownerror" => "Unbekannter Fehler beim Import- battle.net hat keine Fehlermeldung zurückgegebn",
		"uc_armory_import_error_code"	=> "Code: %1$\$s, Typ: %2\$s, Details: %3\$s",
		'uc_armory_impduplex'			=> 'Charakter ist bereits vorhanden',

		// guild importer
		'uc_class_filter'				=> 'Klasse',
		'uc_class_nofilter'				=> 'Nicht filtern',
		'uc_guild_name'					=> 'Name der Gilde',
		'uc_filter_name'				=> 'Filter',
		'uc_level_filter'				=> 'Level größer als',
		'uc_rank_filter1a'				=> 'höher als',
		'uc_rank_filter1b'				=> 'gleich',
		'uc_rank_filter'				=> 'Rang',
		'uc_imp_noguildname'			=> 'Es wurde kein Gildenname angegeben',
		'uc_gimp_loading'				=> 'Gildenmitglieder werden geladen, bitte warten...',
		'uc_massupd_loading'			=> 'Charaktere werden aktualisiert, bitte warten...',
		'uc_gimp_header_fnsh'			=> 'Der Import der Gildenmitglieder wurde beendet. Beim Gildenimport werden nur der Charktername, die Rasse, die Klasse und das Level importiert. Um die restlichen Daten zu importieren, einfach den battle.net Updater benutzen.',
		'uc_cupdt_header_fnsh'			=> 'Die Aktualisierung der Charaktere wurde beendet. Das Fenster kann nun geschlossen werden.',
		'uc_importcache_cleared'		=> 'Der Cache des Importers wurde erfolgreich geleert.',
		'uc_startdkp'					=> 'Start-DKP vergeben',
		'uc_startdkp_adjreason'			=> 'Start-DKP',
		'uc_delete_chars_onimport'		=> 'Charaktere im System löschen, die nicht mehr in der Gilde sind',

		'uc_noprofile_found'			=> 'Kein Profil gefunden',
		'uc_profiles_complete'			=> 'Profile erfolgreich aktualisiert',
		'uc_notyetupdated'				=> 'Keine neuen Daten (Inaktiver Charakter)',
		'uc_notactive'					=> 'Das Mitglied ist im EQDKP auf inaktiv gesetzt und wird daher übersprungen',
		'uc_error_with_id'				=> 'Fehler mit der Member ID, Charakter übersprungen',
		'uc_notyourchar'				=> 'ACHTUNG: Du versuchst gerade einen Charakter hinzuzufügen, der bereits in der Datenbank vorhanden ist und dir nicht zugewiesen ist. Aus Sicherheitsgründen ist diese Aktion nicht gestattet. Bitte kontaktiere einen Administrator zum Lösen dieses Problems oder versuche einen anderen Charakternamen einzugeben.',
		'uc_lastupdate'					=> 'Letzte Aktualisierung',

		'uc_prof_import'				=> 'importieren',
		'uc_prof_update'				=> 'aktualisieren',
		'uc_import_forw'				=> 'Start',
		'uc_imp_succ'					=> 'Die Daten wurden erfolgreich importiert',
		'uc_upd_succ'					=> 'Die Daten wurden erfolgreich aktualisiert',
		'uc_imp_failed'					=> 'Beim Import der Daten trat ein Fehler auf. Bitte versuche es erneut.',
		'uc_sync_ranks'					=> 'Ränge synchronisieren',

		'base'							=> 'Attribute',
		'strength'						=> 'Stärke',
		'agility'						=> 'Beweglichkeit',
		'stamina'						=> 'Ausdauer',
		'intellect'						=> 'Intelligenz',
		'spirit'						=> 'Willenskraft',

		'profilers'						=> 'Externe Profilseiten',
		'profiler_askmrrobot'			=> 'AskMrRobot.com',

		'melee'							=> 'Nahkampf',
		'mainHandDamage'				=> 'Schaden',
		'mainHandDps'					=> 'DPS',
		'mainHandSpeed'					=> 'Geschwindigkeit',
		'power'							=> 'Angriffskraft',
		'hasteRating'					=> 'Tempowertung',
		'haste'							=> 'Tempo',
		'mastery'						=> 'Meisterschaftswertung',
		'versatility'					=> 'Vielseitigkeit',

		'range'							=> 'Distanzkampf',
		'damage'						=> 'Schaden',
		'rangedDps'						=> 'DPS',
		'rangedSpeed'					=> 'Geschwindigkeit',

		'spell'							=> 'Zauber',
		'spellpower'					=> 'Zaubermacht',
		'spellHit'						=> 'Trefferchance',
		'spellCrit'						=> 'Kritische Trefferchance',
		'spellPen'						=> 'Zauberdurchschlagskraft',
		'manaRegen'						=> 'Manaegeneration',
		'combatRegen'					=> 'Kampfregeneration',

		'defenses'						=> 'Verteidigung',
		'armor'							=> 'Rüstung',
		'dodge'							=> 'Ausweichen',
		'parry'							=> 'Parieren',
		'block'							=> 'Blocken',
		'all'							=> 'Alle Werte',

		'achievements'					=> 'Erfolge',
		'achievement_points'			=> 'Erfolgspunkte',
		'total'							=> 'Gesamt',
		'health'						=> 'Leben',
		'last5achievements'				=> 'Die letzten 5 Erfolge',

		'charnewsfeed'					=> 'Letzte Aktivitäten',
		'charnf_achievement'			=> 'Erfolg %s für %s Punkte errungen.',
		'charnf_achievement_hero'		=> 'Heldentat %s errungen.',
		'charnf_item'					=> 'Erhalten %s',
		'charnf_bosskill'				=> '%s %s',
		'charnf_criteria'				=> 'Schritt %s des Erfolgs %s abgeschlossen.',
		'avg_itemlevel'					=> 'Durchschnittliche Gegenstandsstufe',
		'avg_itemlevel_equiped'			=> 'ausgerüstet',

		// bossprogress
		'bossprogress_normalruns'		=> '%sx normal',
		'normalrun'						=> 'N',
		'bossprogress_heroicruns'		=> '%sx heroisch',
		'heroicrun'						=> 'H',
		'bossprogress_mythicruns'		=> '%sx mythisch',
		'mythicrun'						=> 'M',

		'wotlk'							=> 'Wrath of the Lich King',
		'cataclysm'						=> 'Cataclysm',
		'burning_crusade'				=> 'Burning Crusade',
		'classic'						=> 'Classic',
		'mop'							=> 'Mists of Pandaria',
		'leg'							=> 'Legion',

		'mop_mogushan_10'				=> 'Mogu\'shangewölbe (10)',
		'mop_mogushan_25'				=> 'Mogu\'shangewölbe (25)',
		'mop_heartoffear_10'			=> 'Das Herz der Angst (10)',
		'mop_heartoffear_25'			=> 'Das Herz der Angst (25)',
		'mop_endlessspring_10'			=> 'Terrasse des Endlosen Frühlings (10)',
		'mop_endlessspring_25'			=> 'Terrasse des Endlosen Frühlings (25)',
		'mop_throneofthunder_10'		=> 'Thron des Donners (10)',
		'mop_throneofthunder_25'		=> 'Thron des Donners (25)',
		'mop_siegeoforgrimmar'			=> 'Schlacht um Orgrimmar',

		'wod_hm_normal'					=> 'Hochfels Normal',
		'wod_hm_heroic'					=> 'Hochfels Heroisch',
		'wod_hm_mythic'					=> 'Hochfels Mystisch (20)',
		'wod_brf_normal'				=> 'Schwarzfelsgießerei Normal',
		'wod_brf_heroic'				=> 'Schwarzfelsgießerei Heroisch',
		'wod_brf_mythic'				=> 'Schwarzfelsgießerei Mythisch (20)',

		'leg_en_normal'					=> 'Smaragdgrüner Albtraum Normal',
		'leg_en_heroic'					=> 'Smaragdgrüner Albtraum Heroisch',
		'leg_en_mythic'					=> 'Smaragdgrüner Albtraum Mythisch (20)',
		'leg_nh_normal'					=> 'Die Nachtfestung Normal',
		'leg_nh_heroic'					=> 'Die Nachtfestung Heroisch',
		'leg_nh_mythic'					=> 'Die Nachtfestung Mythisch (20)',
		'leg_tov_normal'				=> 'Die Prüfung der Tapferkeit Normal',
		'leg_tov_heroic'				=> 'Die Prüfung der Tapferkeit Heroisch',
		'leg_tov_mythic'				=> 'Die Prüfung der Tapferkeit Mythisch (20)',
		'leg_tos_normal'				=> 'Grabmal des Sargeras Normal',
		'leg_tos_heroic'				=> 'Grabmal des Sargeras Heroisch',
		'leg_tos_mythic'				=> 'Grabmal des Sargeras Mythisch (20)',
		'leg_atbt_normal'				=> 'Antorus, der Brennende Thron Normal',
		'leg_atbt_heroic'				=> 'Antorus, der Brennende Thron Heroisch',
		'leg_atbt_mythic'				=> 'Antorus, der Brennende Thron Mythisch (20)',

		'bfa_uldir_normal'				=> 'Uldir Normal',
		'bfa_uldir_heroic'				=> 'Uldir Heroisch',
		'bfa_uldir_mythic'				=> 'Uldir Mythisch (20)',
		'bfa_bod_normal'				=> 'Schlacht um Dazar\'alor Normal',
		'bfa_bod_heroic'				=> 'Schlacht um Dazar\'alor Heroisch',
		'bfa_bod_mythic'				=> 'Schlacht um Dazar\'alor Mythisch (20)',
		'bfa_cos_normal'				=> 'Tiegel der Stürme Normal',
		'bfa_cos_heroic'				=> 'Tiegel der Stürme Heroisch',
		'bfa_cos_mythic'				=> 'Tiegel der Stürme Mythisch (20)',
		'bfa_tep_normal'				=> 'Der Ewige Palast Normal',
		'bfa_tep_heroic'				=> 'Der Ewige Palast Heroisch',
		'bfa_tep_mythic'				=> 'Der Ewige Palast Mythisch (20)',
		'bfa_twc_normal'				=> 'Ny\'alotha, die Erwachte Stadt Normal',
		'bfa_twc_heroic'				=> 'Ny\'alotha, die Erwachte Stadt Heroisch',
		'bfa_twc_mythic'				=> 'Ny\'alotha, die Erwachte Stadt Mythisch (20)',

		'char_news'						=> 'Char News',
		'no_armory'						=> 'Es konnten keine gültigen Daten für diesen Charakter geladen werden. Die battle.net API meldet folgenden Fehler: "%s".',
		'no_realm'						=> 'Um den vollen Funktionsumfang dieser Seite nutzen zu können, muss in den Administrator-Einstellungen ein gültiger World of Warcraft Server hinterlegt werden.',

		'guildachievs_total_completed'	=> 'Vollständig abgeschlossen',
		'latest_guildachievs'			=> 'Kürzlich erhalten',
		'guildnews'						=> 'Gildennews',
		'news_guildCreated'				=> 'Gilde wurde gegründet',
		'news_itemLoot'					=> '%1$s erhielt %2$s',
		'news_itemPurchase'				=> '%1$s erwarb den Gegenstand: %2$s',
		'news_guildLevel'				=> 'Die Gilde hat Level %s erreicht',
		'news_guildAchievement'			=> 'Die Gilde hat den Erfolg %1$s für %2$s Punkte errungen.',
		'news_playerAchievement'		=> '%1$s hat den Erfolg %2$s für %3$s Punkte errungen.',

		'not_assigned'					=> 'Nicht verteilt',
		'empty'							=> 'Leer',

		"core_sett_f_itt_icon_small_loc"=> "URL zu den kleinen Icons.",
		"core_sett_f_help_itt_icon_small_loc"	=> "Falls die URL nicht bekannt ist, einmal den Parser wechseln und wieder zurück, um Standard-Wert zu laden.",
	),

	'realmlist' => array('Eldre\'Thalas','Spirestone','Shadow Council','Scarlet Crusade','Firetree','Frostmane','Gurubashi','Smolderthorn','Skywall','Windrunner','Nathrezim','Terenas','Arathor','Bonechewer','Dragonmaw','Shadowsong','Silvermoon','Crushridge','Stonemaul','Daggerspine','Stormscale','Dunemaul','Boulderfist','Suramar','Dragonblight','Draenor','Uldum','Bronzebeard','Feathermoon','Bloodscalp','Darkspear','Azjol-Nerub','Perenolde','Argent Dawn','Azgalor','Magtheridon','Trollbane','Gallywix','Madoran','Stormrage','Zul\'jin','Medivh','Durotan','Bloodhoof','Elune','Lothar','Arthas','Mannoroth','Warsong','Shattered Hand','Bleeding Hollow','Skullcrusher','Burning Blade','Gorefiend','Eredar','Shadowmoon','Lightning\'s Blade','Eonar','Gilneas','Kargath','Llane','Earthen Ring','Laughing Skull','Burning Legion','Thunderlord','Malygos','Drakkari','Aggramar','Thunderhorn','Ragnaros','Quel\'Thalas','Dreadmaul','Caelestrasz','Kilrogg','Proudmoore','Nagrand','Frostwolf','Ner\'zhul','Kil\'jaeden','Blackrock','Tichondrius','Silver Hand','Aman\'Thul','Barthilas','Thaurissan','Dath\'Remar','Frostmourne','Khaz\'goroth','Vek\'nilash','Sen\'jin','Aegwynn','Akama','Chromaggus','Draka','Drak\'thul','Garithos','Hakkar','Khaz Modan','Jubei\'Thos','Mug\'thol','Korgath','Kul Tiras','Malorne','Gundrak','Eitrigg','Rexxar','Muradin','Saurfang','Thorium Brotherhood','Runetotem','Garona','Alleria','Hellscream','Blackhand','Whisperwind','Cho\'gall','Illidan','Stormreaver','Gul\'dan','Kael\'thas','Alexstrasza','Kirin Tor','Ravencrest','Goldrinn','Nemesis','Balnazzar','Destromath','Gorgonnash','Dethecus','Spinebreaker','Moonrunner','Sargeras','Kalecgos','Ursin','Dark Iron','Greymane','Wildhammer','Detheroc','Staghelm','Emerald Dream','Maelstrom','Twisting Nether','Azshara','Agamaggan','Lightninghoof','Nazjatar','Malfurion','Baelgun','Azralon','Tol Barad','Duskwood','Zuluhed','Steamwheedle Cartel','Mal\'Ganis','Norgannon','Archimonde','Anetheron','Turalyon','Haomarush','Scilla','Ysondre','Thrall','Ysera','Dentarg','Khadgar','Dalaran','Dalvengyr','Black Dragonflight','Andorhal','Executus','Doomhammer','Icecrown','Deathwing','Kel\'Thuzad','Altar of Storms','Uldaman','Aerie Peak','Onyxia','Demon Soul','Gnomeregan','Anvilmar','The Venture Co','Sentinels','Jaedenar','Tanaris','Alterac Mountains','Undermine','Lethon','Blackwing Lair','Arygos','Lightbringer','Cenarius','Uther','Cenarion Circle','Echo Isles','Hyjal','The Forgotten Coast','Fenris','Anub\'arak','Blackwater Raiders','Vashj','Korialstrasz','Misha','Darrowmere','Ravenholdt','Bladefist','Shu\'halo','Winterhoof','Sisters of Elune','Maiev','Rivendare','Nordrassil','Tortheldrin','Cairne','Drak\'Tharon','Antonidas','Shandris','Moon Guard','Nazgrel','Hydraxis','Wyrmrest Accord','Farstriders','Borean Tundra','Quel\'dorei','Garrosh','Mok\'Nathal','Nesingwary','Drenden','Terokkar','Blade\'s Edge','Exodar','Area 52','Velen','Azuremyst','Auchindoun','The Scryers','Coilfang','Zangarmarsh','Shattered Halls','Blood Furnace','The Underbog','Fizzcrank','Ghostlands','Grizzly Hills','Galakrond','Dawnbringer','Aszune','Sunstrider','Twilight\'s Hammer','Zenedar','Aggra (Português)','Al\'Akir','Sinstralis','Madmortem','Nozdormu','Die Silberne Hand','Zirkel des Cenarius','Dun Morogh','Theradras','Genjuros','Wrathbringer','Nera\'thor','Kult der Verdammten','Das Syndikat','Terrordar','Krag\'jin','Der Rat von Dalaran','Neptulon','The Maelstrom','Sylvanas','Bloodfeather','Darksorrow','Frostwhisper','Defias Brotherhood','Drek\'Thar','Rashgarroth','Throk\'Feroth','Conseil des Ombres','Varimathras','Les Sentinelles','Moonglade','Mazrigos','Talnivarr','Emeriss','Ahn\'Qiraj','Nefarian','Blackmoore','Xavius','Die ewige Wacht','Die Todeskrallen','Scarshield Legion','Die Arguswacht','Outland','Grim Batol','Kazzak','Tarren Mill','Chamber of Aspects','Pozzo dell\'Eternità','Vek\'lor','Taerar','Rajaxx','Ulduar','Der abyssische Rat','Lordaeron','Tirion','Ambossar','Krasus','Die Nachtwache','Arathi','Culte de la Rive noire','Dun Modr','C\'Thun','Sanguino','Shen\'dralar','Tyrande','Minahonda','Los Errantes','Darkmoon Faire','Alonsus','Burning Steppes','Bronze Dragonflight','Anachronos','Colinas Pardas','Kor\'gall','Forscherliga','Un\'Goro','Todeswache','Teldrassil','Der Mithrilorden','Vol\'jin','Arak-arahm','La Croisade écarlate','Confrérie du Thorium','Hellfire','Azuregos','Ashenvale','Booty Bay','Eversong','Thermaplugg','The Sha\'tar','Karazhan','Grom','Blackscar','Gordunni','Lich King','Soulflayer','Deathguard','Sporeggar','Nethersturm','Shattrath','Festung der Stürme','Echsenkessel','Blutkessel','Deepholm','Howling Fjord','Razuvious','Deathweaver','Die Aldor','Das Konsortium','Chants éternels','Marécage de Zangar','Temple noir','Fordragon','Naxxramas','Les Clairvoyants'),
);
?>
