<?php
/**
 * @file config.php
 * @brief Containing configuration of the plugin.
 *
 * @author David Stutz
 * @license GPLv3
 * @package news
 * @see http://sourceforge.net/projects/cmsimplenews/
 * 
 *  Copyright 2012 - 2018 David Stutz
 * 
 * 	This file is part of the news plugin for CMSimple.
 *
 *  The plugin is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The plugin is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU General Public License for more details.
 *
 *  @see <http://www.gnu.org/licenses/>.
 */

	/** 
	 * Filepath storing CSV containing news.
	 * @warning NOTE: Without '/' at the end! 
	 * @var
	 */
	$plugin_cf['news']['csv_filepath']="content/plugins/news";
	/** 
	 * Delimiter for CSV. 
	 * @var
	 */
	$plugin_cf['news']['csv_delimiter']="#";
	/**
	 * Enclosure for CSV.
	 * @var
	 */
	$plugin_cf['news']['csv_enclosure']="\"";
    /**
     * Which function to use for category sorting (asc or desc).
     * @var
     */
    $plugin_cf['news']['categories_sort_function']="news_categories_sort_asc";
	/**
	 * Using the news module as blog system.
	 * @var
	 */
	$plugin_cf['news']['blog']="false";
	/**
	 * Date format used in frontend.
	 * @var
	 */
	$plugin_cf['news']['date_format']="d.m.Y";
	/**
	 * Date format used in backend.
	 * Note that this format should be compatible with the datepicker format. Good choices are d.m.Y for date_format_backend and dd.mm.yyyy for datepicker_format.
	 * @var
	 */
	$plugin_cf['news']['date_format_backend']="d.m.Y";
	/**
	 * Include newsticker for use in templates.
	 * @var
	 */
	$plugin_cf['news']['template_newsticker']="false";
	/**
	 * Include bxslider for use in template.
	 * @var
	 */
	$plugin_cf['news']['template_newsslider']="false";
    /**
	 * Include newsscroller (liscroll) for use in template.
	 * @var
	 */
	$plugin_cf['news']['template_newsscroller']="true";
	/**
	 * RSS file with full path relative to CMSimple root.
	 * @var
	 */
	$plugin_cf['news']['rss_file'] = 'rss.php';
	/**
	 * Archive entries per page.
	 * @var
	 */
	$plugin_cf['news']['pagination_backend'] = '10';
	/**
	 * Backend entries per page.
	 * @var
	 */
	$plugin_cf['news']['pagination_archive'] = '10';

	/**
	 * Speed of newsticker reveal.
	 * @var
	 */
	$plugin_cf['news']['newsticker_speed']="0.1";
	/**
	 * Display newsticker controls (Stop, Resume, Next, Previous).
	 * @var
	 */
	$plugin_cf['news']['newsticker_controls']="false";
	/**
	 * Title text for newsticker.
	 * @var
	 */
	$plugin_cf['news']['newsticker_title_text']="Latest";
	/**
	 * Display (effect) type (reveal or fade).
	 * @var
	 */
	$plugin_cf['news']['newsticker_effect']="reveal";
	/**
	 * Time between news.
	 * @var
	 */
	$plugin_cf['news']['newsticker_delay']="2000";
	/**
	 * Fade in speed.
	 * @var
	 */
	$plugin_cf['news']['newsticker_fade_in_speed']="600";
	/**
	 * Fade out speed.
	 * @var
	 */
	$plugin_cf['news']['newsticker_fade_out_speed']="300";
	
	/**
	 * Bxslider speed. Value between 1 and 5000. Small value => fast ticker.
	 * @var
	 */
	$plugin_cf['news']['newsslider_speed']="2000";
	/**
	 * Pause ticker on hover.
	 * @var
	 */
	$plugin_cf['news']['newsslider_pause_hover']="true";
    /**
     * Speed for newsscroller.
     * @var
     */
    $plugin_cf['news']['newsscroller_speed']="0.07";
	/**
	 * Date format for datepicker.
	 * @var
	 * 
	 * Following conventions:
	 *      d - day of month (no leading zero)
	 *	    dd - day of month (two digit)
	 *	    o - day of the year (no leading zeros)
	 *	    oo - day of the year (three digit)
	 *	    D - day name short
	 *	    DD - day name long
	 *	    m - month of year (no leading zero)
	 *	    mm - month of year (two digit)
	 *	    M - month name short
	 *	    MM - month name long
	 *	    y - year (two digit)
	 *	    yy - year (four digit)
	 *	    @ - Unix timestamp (ms since 01/01/1970)
	 *	     ! - Windows ticks (100ns since 01/01/0001)
	 *	    '...' - literal text
	 *	    '' - single quote
	 *	    anything else - literal text 
	 * 
	 */
	$plugin_cf['news']['datepicker_format']="dd.mm.yy";
	/**
	 * jQuery core file. Found in news/jquery/js/.
	 * @var
	 */
	$plugin_cf['news']['jquery_core']="jquery-1.9.1.min.js";
	/**
	 * jQuery UI file. Found in news/jquery/js/.
	 * @var
	 */
	$plugin_cf['news']['jquery_ui']="jquery-ui-1.8.21.custom.min.js";
	/**
	 * jQuery UI CSS file. Found in news/jquery/css/.
	 * @var
	 */
	$plugin_cf['news']['jquery_ui_css']="jquery-ui-1.8.21.custom.css";
?>