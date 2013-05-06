<?php

function project_selection() {
	$t_this_page = plugin_page('version_calendar');
	$t_project_id = helper_get_current_project();
	if(ALL_PROJECTS == $t_project_id) {
		print_header_redirect("login_select_proj_page.php?ref=$t_this_page");
	}
}

function print_submenu() {
	echo '<div align="center"><p>';
	print_bracket_link(plugin_page('version_calendar'), plugin_lang_get('sub_menu_calendar'));
	print_bracket_link('search.php?sticky_issues=on&sortby=target_version%2Cstatus&dir=ASC%2CASC&per_page=999999&hide_status_id=-2', plugin_lang_get('sub_menu_all_issues'));
	print_bracket_link('search.php?sticky_issues=on&sortby=status&dir=ASC&per_page=999999&hide_status_id=-2&target_version=-2', plugin_lang_get('sub_menu_unplanned_issues'));
	print_bracket_link(plugin_page('version_calendar_help'), plugin_lang_get('sub_menu_help'));
	
	echo '</p></div>';
}

function get_text_map() {
	global $version_calendar_text_map;

	if (isset($version_calendar_text_map)) {
		$text_map = $version_calendar_text_map;
	} else {
		$text_map = array(
			"Alpha" => "α",
			"Feature Freeze" => "ff",
			"Beta" => "β",
			"Release" => "r",
		);
	}
	
	return $text_map;
}

function parse_project_versions($project_id) {
	$maxVersionIndex = 6; # This must match the versionX rules in CSS
	$text_map = array_change_key_case(get_text_map());
	
	$versions = version_get_all_rows($project_id);
	
	foreach ($versions as $versionIndex => $version) {
		$versionName = $version["version"];
		$versionDescription = $version["description"];
	
		preg_match_all('/^\s*\*\s*(\d\d)\.\s*(\d\d)\.\s*(\d\d\d\d)\s*\:(.*?)(?:<--.*)?$/m', $versionDescription, $versionDescriptionEntries);
	
		$count = count($versionDescriptionEntries[0]);
	
		for ($n = 0; $n < $count; $n++) {
			$text = trim($versionDescriptionEntries[4][$n]);
			$short_text = $text_map[strtolower($text)];
		
			if (isset($short_text)) {
				$day = intval($versionDescriptionEntries[1][$n]);
				$month = intval($versionDescriptionEntries[2][$n]);
				$year = intval($versionDescriptionEntries[3][$n]);

				$parsedVersion = array();
				$parsedVersion["text"] = $text;
				$parsedVersion["shortText"] = $short_text;
				$parsedVersion["version"] = $versionName;
				$parsedVersion["description"] = $versionDescription;
				$parsedVersion["versionIndex"] = $versionIndex % $maxVersionIndex;
		
				$parsedVersions[$year][$month][$day][] = $parsedVersion;
			}
		}
	}
	return $parsedVersions;
}

function calculate_holidays_munich($year_min, $year_max) {
	for ($year = $year_min; $year <= $year_max; $year++) {
		$holidays[$year][1][1] = true;	# Neujahr
		$holidays[$year][1][6] = true;	# Heilige Drei Könige
		$holidays[$year][5][1] = true;	# Tag der Arbeit
		$holidays[$year][8][15] = true;	# Mariä Himmelfahrt
		$holidays[$year][10][3] = true;	# Tag der Deutschen Einheit
		$holidays[$year][11][1] = true;	# Allerheiligen
		$holidays[$year][12][24] = true;	# Heiligabend
		$holidays[$year][12][25] = true;	# 1. Weihnachtsfeiertag
		$holidays[$year][12][26] = true;	# 2. Weihnachtsfeiertag
		$holidays[$year][12][31] = true;	# Silvester

		$ostersonntag = date_create()->setDate($year, 3, 21)->modify("+".easter_days($year)." day");

		$karfreitag = clone $ostersonntag; $karfreitag->modify("-2 day");
		$ostermontag = clone $ostersonntag; $ostermontag->modify("+1 day");
		$christi_himmelfahrt = clone $ostersonntag; $christi_himmelfahrt->modify("+39 day");
		$pfingstmontag = clone $ostersonntag; $pfingstmontag->modify("+50 day");
		$fronleichnam = clone $ostersonntag; $fronleichnam->modify("+60 day");

		$holidays[$year][$karfreitag->format("n")][$karfreitag->format("j")] = true;
		$holidays[$year][$ostermontag->format("n")][$ostermontag->format("j")] = true;
		$holidays[$year][$christi_himmelfahrt->format("n")][$christi_himmelfahrt->format("j")] = true;
		$holidays[$year][$pfingstmontag->format("n")][$pfingstmontag->format("j")] = true;
		$holidays[$year][$fronleichnam->format("n")][$fronleichnam->format("j")] = true;
	}
	return $holidays;
}

function is_weekend($year, $month, $day) {
	$weekday = date_create()->setDate($year, $month, $day)->format("N");
	$is_weekend = (($weekday == 6) || ($weekday == 7));
	return $is_weekend;
}
