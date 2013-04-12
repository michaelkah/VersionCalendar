<?php

# Includes

require_once('version_api.php');
require_once('version_calendar_api.php');

# Project selection required

project_selection();

# Page header

html_page_top(plugin_lang_get('main_menu'));
print_submenu();

# Computations

$t_project_id = helper_get_current_project();
$parsedVersions = parse_project_versions($t_project_id);

$today = getdate();

if (isset($parsedVersions)) {
	$years = array_keys($parsedVersions);
	$year_min = min($years);
	$year_max = max($years);
} else {
	$year_min = $today["year"];
	$year_max = $today["year"];
}

$holidays = calculate_holidays_munich($year_min, $year_max);

# Calendar table

for ($year = $year_max; $year >= $year_min; $year--) {
	echo "<h1 class='yearname'>$year</h1>";
	echo "<table class='yeartable'>";
	
	for ($day = 0; $day <= 31; $day++) {
		echo "<tr>";
		for ($month = 1; $month <= 12; $month++) {
			$last_day = date_create()->setDate($year, $month, 1)->format("t");

			if ($day == 0) {
				echo "<th class='month'>".plugin_lang_get('month_'.$month)."</th>";
			} else if ($day <= $last_day) {
				$is_today = (($year == ($today["year"])) && ($month == ($today["mon"])) && ($day == ($today["mday"])));
				$is_holiday = is_weekend($year, $month, $day) || $holidays[$year][$month][$day];
				$parsedVersionEntries = $parsedVersions[$year][$month][$day];

				echo "<td class='day ".($is_today ? "today" : ($is_holiday ? "holiday" : ""))."'>";
				echo "<div class='date ".($is_today ? "today" : ($is_holiday ? "holiday" : ""))."'>$day</div>";
			
				if (isset($parsedVersionEntries)) {
					echo "<div class='versions'>";

					foreach ($parsedVersionEntries as $parsedVersionEntry) {
						echo "<div class='version'>";
						echo "<a class='mylink' href='search.php?sticky_issues=on&sortby=status&dir=ASC&per_page=999999&hide_status_id=-2&target_version=".$parsedVersionEntry["version"]."'>";
						echo "<span class='versiontext version".$parsedVersionEntry["versionIndex"]."'>".$parsedVersionEntry["shortText"]." ".$parsedVersionEntry["version"]."</span>";
						echo "<span class='tooltip version".$parsedVersionEntry["versionIndex"]."'>".$parsedVersionEntry["description"]."</span>";
						echo "</a>";
						echo "</div>";
					}
					echo "</div>";
				}
				echo "</td>";
			} else {
				echo "<td/>";
			}
		}
		echo "</tr>";
	}
	echo "</table>";
}

# Page footer

html_page_bottom();
