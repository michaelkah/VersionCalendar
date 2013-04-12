<?php

class VersionCalendarPlugin extends MantisPlugin {
    function register() {
        $this->name = 'Version Calendar';    # Proper name of plugin
        $this->description = 'Displays a year view calendar in the main menu to provide a quick and easy overview of all release-relevant dates to all project participants.';    # Short description of the plugin
        $this->page = '';           # Default plugin page

        $this->version = '1.2';     # Plugin version string
        $this->requires = array(    # Plugin dependencies, array of basename => version pairs
            'MantisCore' => '1.2.0',  #   Should always depend on an appropriate version of MantisBT
            );

        $this->author = 'Michael Kraus';         # Author/team name
        $this->contact = '';        # Author/team e-mail address
        $this->url = '';            # Support webpage
    }

	function init() {
		$t_path = config_get_global('plugin_path').plugin_get_current().DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR;
		set_include_path(get_include_path().PATH_SEPARATOR.$t_path);
	}

	function hooks( ) {
		$hooks = array(
			'EVENT_MENU_MAIN' => 'main_menu',
			'EVENT_LAYOUT_RESOURCES' => 'style_sheet',
		);
		return $hooks;
	}

	function main_menu( ) {
		return '<a href="' . plugin_page( 'version_calendar' ) . '">' . plugin_lang_get( 'main_menu' ) . '</a>';
	}	

	function style_sheet( ) {
		return '<link rel="stylesheet" type="text/css" href="' . plugin_file( 'version_calendar.css' ) . '"/>';
	}	
}
