<?php
/* utf8-marker = äöüß */
/**
 * @file html.php
 * @brief HTML helper.
 * 
 * @author David Stutz
 * @version 1.1.0
 * @license GPLv3
 * @package news
 * @see http://sourceforge.net/projects/cmsimplenews/
 * 
 *  This file is part of the news plugin for CMSimple.
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
 * @class HTML
 * HTML helper.
 * 
 * @author David Stutz
 * @since 1.1.0
 * @package news
 */
class HTML {
	
	/**
	 * @public
	 * Defines used charset for the operations.
	 */
	public $charset = 'utf-8';
	
	/**
	 * @public
	 * @static
	 * Convert special characters to HTML entities.
	 * All untrusted content should be passed through this method to prevent XSS injections.
	 *
	 * @param <string> string to convert
	 * @return <string> output
	 */
	public static function chars($string)
	{
		return htmlspecialchars((string)$string, ENT_QUOTES, 'utf-8');
	}
	
	/**
	 * @public
	 * @static
	 * Convert all applicable characters to HTML entities.
	 * All characters that cannot be represented in HTML with the current character set will be converted to entities.
	 *
	 * @param <string> string to convert
	 * @return <string> output
	 */
	public static function entities($string)
	{
		return htmlentities((string)$string, ENT_QUOTES, 'utf-8');
	}
	
	/**
	 * @public
	 * @static
	 * Converts all HTML entities to their original characters.
	 * 
	 * @param <string> string to convert
	 * @return <string> ouput
	 */
	public static function decode_entities($string)
	{
		return html_entity_decode($string, ENT_QUOTES);
	}
}
?>