<?php
/* utf8-marker = äöüß */
/**
 * @file admin.php
 * @brief Plugin's backend.
 * 
 * @author David Stutz
 * @license GPLv3
 * @package news
 * @see http://sourceforge.net/projects/cmsimplenews/
 * 
 *  Copyright 2012 - 2014 David Stutz
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

if (!defined('CMSIMPLE_XH_VERSION')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}
 
if (!class_exists('News')) require dirname(__FILE__) . '/news.php';

if (isset($news))
{
	global $sn;

	$f = "news";
	
	/* initvar() for POST AND GET support. */
	initvar('admin');
	
	$o .= print_plugin_admin('ON');
	
	/**
	 * Displays plugin info:
	 * Plugin name, description, link, author etc.
	 */
	if ($admin === '') 
	{
		$o .= '<p class="news-head"><b>' . News::name() . '</b></p>'
				. '<div class="news-notice">'
				. 'Version: <b>' . News::VERSION . '</b><br />'
				. '</div>'
				. '<div class="news-help">'
				. 'Released: ' . News::release_date() . '<br />'
				. 'Author: ' . News::author() . '<br />'
				. 'Website: ' . News::website() . '<br />'
                                . 'GitHub Repository/Releases: ' . News::github() . '<br />'
				. News::description() . '<br />'
				. News::legal() . '<br />'
				. '</div>';
	}
	
	if ($admin == 'plugin_main')
	{
		News::check_dir();
		
		$action = isset($_GET['action']) ? $_GET['action'] : 'plugin_text';

		$o .= '<p class="news-head"><b>' . News::$tx["title_" . $action] . '</b><span style="float: right;"><a href="' . page_url() . '&help"><img src="' . News::$images . '/help.png" /></a></span></p>'
			. '<p>';
		
		/**
		 * Will get current category.
		 * If no category is given the first one is taken.
		 * If no category exists the user is redirected to add a new category.
		 */
		$category = FALSE;
		$categories = News_Category::categories();
		if (isset($_GET['category'])
			AND News_Category::category_exists($_GET['category']))
		{
			$category = new News_Category($_GET['category']);
		}
		elseif (!empty($categories))
		{
			$category = $categories[0];
		}
		else
		{
			$action = 'new';
			$o .= '<div class="news-error">'.  News::$tx["No categories found. Create a new one."] . '</div>';
		}

		/**
		 * Get entry.
		 * If entry is given it will be saved in $entry.
		 */
		$entry = FALSE;
		if (isset($_GET['entry'])
			AND is_object($category)
			AND $category->entry_exists($_GET['entry']))
		{
			$entry = new News_Entry($_GET['entry'], $category);
		}
		
		/**
		 * Get current apge.
		 * Get help request.
		 */
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$help = isset($_GET['help']) ? TRUE : FALSE;
		
		/**
		 * Publish the given entry.
		 */
		if (is_object($entry)
			AND isset($_GET['publish']))
		{
			$entry->state(time());
			$entry->save();
			
			$o .= '<div class="news-success">' . News::$tx["Entry successfully published."] . '</div>';
		}
		
		/**
		 * Take out the given entry.
		 */
		if (is_object($entry)
			AND isset($_GET['takeout']))
		{
			$entry->state(0);
			$entry->save();
			
			$o .= '<div class="news-success">' . News::$tx["Entry successfully taken out."] . '</div>';
		}
		
		/**
		 * Add a entry.
		 * Because there is no validation needed it can be done here.
		 */
		if (isset($_POST['add']))
		{
			$entry = new News_Entry(time(), $category);
			$entry->title(stsl($_POST['title']));
			$entry->description(News::blog() ? stsl($_POST['description']) : '');
			$entry->short(stsl($_POST['short']));
			$entry->state(isset($_POST['publish']) ? strtotime($_POST['publish-date']) : 0);
			
			$entry->save();
			
			$o .= '<div class="news-success">' . News::$tx["Successfully added entry."] . '</div>';
		}
		
		/**
		 * Delete entry.
		 */
		if (isset($_POST['delete']))
		{
			$entry->delete();
			unset($entry);
			
			$o .= '<div class="news-success">' . News::$tx["Successfully deleted entry."] . '</div>';
		}
		
		/**
		 * Main page.
		 * WIll display all news of the selected category.
		 * The category can be changed.
		 * Additionally the menu and pagination is shown.
		 */
		if ($action == 'plugin_text')
		{
			if ($help)
			{
				$o .= '<table class="news-help" width="100%">'
					. '<tr>'
					. '<td width="5%"><img src="' . News::$images . 'category/delete.png" /></td>'
					. '<td>' . News::$tx["Delete the selected category."] . '</td>'
					. '</tr>';
					
				/**
				 * Check for RSS to add RSS menu icon.
				 */
				if (News::rss())
				{
					$o .= '<tr>'
						. '<td width="5%"><img src="' . News::$images . 'category/rss.png" /></td>'
						. '<td>' . News::$tx["See the RSS link of the category."] . '</td>'
						. '</tr>';
				}
					
				$o .= '<tr>'
					. '<td width="10%"><img src="' . News::$images . 'category/settings.png" /></td>'
					. '<td width="90%">' . News::$tx["Edit the settings of the selected gallery."] . '</td>'
					. '</tr>'
					. '<tr>'
					. '<td width="10%"><img src="' . News::$images . 'category/add.png" /></td>'
					. '<td>' . News::$tx["Add a new category."] . '</td>'
					. '</tr>'
					. '<tr>'
					. '<td><img src="' . News::$images . 'entry/add.png" /></td>'
					. '<td>' . News::$tx["Add a new entry to this category."] . '</td>'
					. '</tr>'
					. '<tr>'
					. '<td><img src="' . News::$images . 'entry/edit.png" /></td>'
					. '<td>' . News::$tx["Edit the selected entry."] . '</td>'
					. '</tr>'
					. '<tr>'
					. '<td><img src="' . News::$images . 'entry/publish.png" /></td>'
					. '<td>' . News::$tx["Publish the selected entry."] . '</td>'
					. '</tr>'
					. '<tr>'
					. '<td><img src="' . News::$images . 'entry/take-out.png" /></td>'
					. '<td>' . News::$tx["Take out the selected entry."] . '</td>'
					. '</tr>'
					. '<tr>'
					. '<td><img src="' . News::$images . 'entry/delete.png" /></td>'
					. '<td>' . News::$tx["Delete the selected entry."] . '</td>'
					. '</tr>'
					. '</table>';
			}

			/**
			 * Build menu table.
			 * So build up select input for all categories.
			 */
			$o .= '<table class="edit news-table">'
				. '<tr>'
				. '<td><b>' . News::$tx["Category"] . ': </b>'
				. '<select class="news-select" onChange="location.href=this.options[this.selectedIndex].value">'
				. '<option value="' . $sn . '?&news&admin=plugin_main&action=plugin_text&category=' . $category->name() .' ">' . $category->name() . '</option>';
			
			foreach ($categories as $cat)
			{
				if ($cat == $category)
					continue;
				
				$o .= '<option value="' . $sn . '?&news&admin=plugin_main&action=plugin_text&category=' . $cat->name() . '">' . $cat->name() . '</option>';
			}

			$o .= '</select></td>'
				. '<td width="5%"><a class="pl_tooltip" href="' .$sn . '?&news&admin=plugin_main&category=' . $category->name() . '&action=remove">'
					. '<img src="' . News::$images . 'category/delete.png" />'
					. '<span>' . News::$tx["Delete the selected category."] . '</span>'
				. '</a></td>'
				. '<td width="5%"><a class="pl_tooltip" href="' . $sn . '?&news&admin=plugin_main&category=' . $category->name() . '&action=config">'
					. '<img src="' . News::$images . 'category/settings.png" />'
					. '<span>' . News::$tx["Edit the settings of the selected gallery."] . '</span>'
				. '</a></td>';

			if (News::rss())
			{
				$o .= '<td width="5%"><a class="pl_tooltip" href="' . $sn . '?&news&admin=plugin_main&category=' . $category->name() . '&action=rss">'
						. '<img src="' . News::$images . 'category/rss.png" />'
						. '<span>' . News::$tx["See the RSS link of the category."] . '</span>'
					. '</a></td>';
			}
				
			$o .= '<td width="5%"><a class="pl_tooltip" href="' . $sn . '?&news&admin=plugin_main&action=new">'
					. '<img src="' . News::$images . 'category/add.png" />'
					. '<span>' . News::$tx["Add a new category."] . '</span>'
				. '</a></td>'
				. '</tr>'
				. '</table>';
			
			/**
			 * Create table header and "new entry" icon.
			 */
			$o .= '<table class="edit" width="100%">'
				.'<thead>'
				.'<td width="5%"><a class="pl_tooltip" href="' . $sn . '?&news&admin=plugin_main&category=' . $category->name() . '&action=add">'
					. '<img src="' . News::$images . 'entry/add.png" />'
					. '<span>' . News::$tx["Add a new entry to this category."] . '</span>'
				. '</a></td>'
				.'<td width="5%"></td>'
				.'<td width="5%"></td>'
				.'<td>'.News::$tx["State"].'</td>'
				.'<td>'.News::$tx["Title"].'</td>'
				.'<td>'.News::$tx["Created"].'</td>'
				.'</thead>';
			
			$entries = $category->entries();
			usort($entries, 'news_sort_desc_created');
			$offset = News::$cf['pagination_backend']*($page-1);
			if ($offset < sizeof($entries))
			{
				$entries = array_slice($entries, $offset, News::$cf['pagination_backend']);
			}
			else
			{
				$entries = array_slice($entries, sizeof($entries)-(News::$cf['pagination_backend']+1), News::$cf['pagination_backend']);
			}
			
			/**
			 * Foreach entry the options are displayed.
			 */
			foreach ($entries as $entry)
			{
				$o .= '<tr>'
					.'<td><a class="pl_tooltip" href="' . $sn . '?&news&admin=plugin_main&category='.$entry->category()->name().'&action=edit&entry='.$entry->id().'">'
						. '<img src="'.News::$images.'entry/edit.png" />'
						. '<span>' . News::$tx["Edit the selected entry:"] . ' ' . $entry->title() . '</span>'
					. '</a></td>'
					.'<td><a class="pl_tooltip" href="' . $sn . '?&news&admin=plugin_main&category='.$entry->category()->name().'&action=delete&entry='.$entry->id().'">'
						. '<img src="'.News::$images.'entry/delete.png" />'
						. '<span>' . News::$tx["Delete the selected entry:"] . ' ' . $entry->title() . '</span>'
					. '</a></td>';
				
				if ($entry->published())
				{	
					$o .= '<td><a class="pl_tooltip" href="' . $sn . '?&news&admin=plugin_main&category='.$entry->category()->name().'&action=plugin_text&takeout&entry='.$entry->id().'">'
							. '<img src="'.News::$images.'entry/take-out.png" />'
							. '<span>' . News::$tx["Take out the selected entry:"] . ' ' . $entry->title() . '</span>'
						. '</a></td>'
						. '<td>'.date(News::$cf['date_format_backend'], $entry->state()) . '</td>';
				}
				else 
				{
					$o .= '<td><a class="pl_tooltip" href="' . $sn . '?&news&admin=plugin_main&category='.$entry->category()->name().'&action=plugin_text&publish&entry='.$entry->id().'">'
							. '<img src="' . News::$images.'entry/publish.png" />'
							. '<span>' . News::$tx["Publish the selected entry:"] . ' ' . $entry->title() . '</span>'
						. '</a></td>'
						. '<td>' . News::$tx["Not published"] . ($entry->state() > 0? ' (' . date(News::$cf['date_format_backend'], $entry->state()) . ')' : '' ) . '</td>';
				}
				
				$o .= '<td>' . $entry->title() . '</td>'
					. '<td>' . date(News::$cf['date_format_backend'], $entry->id()) . '</td>'
					. '</tr>';
			}

			$o .= '</table>';
			
			$o .= '<table class="edit" width="100%">'
					. '<tr>'
						. '<td align="left" width="10%"><a class="pl_tooltip" href="' . $sn . '?&news&admin=plugin_main&category='.$category->name().'&page='.($page-1).'">'
							. '<img src="' . News::$images . '/previous.png" alt="' . News::$tx['Previous page:'] . '" />'
						. '</a></td>'
						. '<td align="center" width="80%">' . $page . '</td>'
						. '<td align="right" width="10%"><a href="' . $sn . '?&news&admin=plugin_main&category='.$category->name().'&page='.($page+1).'">'
							. '<img src="'.News::$images.'/next.png" alt="' . News::$tx['Next page:'] . ' ' . ($page-1) . '" />'
						. '</a></td>'
					. '</tr>'
				. '</table>';
		}
		
		/**
		 * Create new category.
		 */
		if ($action == 'new')
		{
			if (isset($_POST['new']))
			{
				/**
				 * Add the category if there are no special signes.
				 */
				if (!empty($_POST['name'])
					AND !preg_match('#[/\.<>?%*:"]#', $_POST['name']))
				{
					$category = new News_Category($_POST['name']);
					$o .= '<div class="news-success">' . News::$tx["Successfully created new category."] . '</div>';
				}
				else
				{
					$o .= '<div class="news-error">' . News::$tx["Fill a valid name."] . '</div>';
				}
			}
		
			$o .= '<div class="news-help">' . News::$tx["The category's name should not contain any whitespace or special characters."] . '</div>';	

			$o .= '<form action="' . $sn . '?&news&admin=plugin_main&action=new" method="POST">'
				. '<table class="edit" width="100%">'
				. '<tr>'
				. '<td>' . News::$tx["Name"] . '</td>'
				. '<td><input type="text" name="name" /></td>'
				. '</tr>'
				. '<tr>'
				. '<td colspan="2"><button type="submit" name="new" class="news-submit submit">' . News::$tx["Save"] . '</button></td>'
				. '</tr>'
				. '</table>'
				. '</form>';
		}
		
		/**
		 * Shows the RSS link to the category.
		 */
		if ($action == 'rss'
			AND $category !== FALSE)
		{
			$o .= '<table class="edit" width="100%">'
				. '<tr>'
				. '<td>' . News::$tx["Link"] . '</td>'
				. '</tr>'
				. '<tr>'
				. '<td><a target="_blank" href="' . $sn . News::$rss . '?category='.$category->name() . '">' . $sn . News::$rss.'?category='.$category->name().'</a></td>'
				. '</tr>'
				. '</table>';
		}
		
		/**
		 * Category configuration.
		 * The user can edit and save the configuration.
		 */
		if ($action == 'config'
			AND $category !== FALSE)
		{
			if (isset($_POST['config']))
			{
				$category->edit(array(
					'rss_title' => HTML::chars($_POST['rss_title'], ENT_QUOTES),
					'rss_description' => HTML::chars($_POST['rss_description'], ENT_QUOTES),
					'rss_editor' => HTML::chars($_POST['rss_editor'], ENT_QUOTES),
                    'rss_link' => $_POST['rss_link'],
                    'news_link' => $_POST['news_link'],
				));
				
				$o .= '<div class="news-success">' . News::$tx["Successfully saved changes."] . '</div>';
			}
			
			$config = $category->config();
			
			if ($help)
			{
				$o .= '<table class="news-help" width="100%">';
				
				foreach ($config as $key => $value)
				{
					$o .= '<tr>'
						. '<td valign="top">' . $key . '</td>'
						. '<td>' . News::$tx['cf_'.$key] . '</td>'
						. '</tr>';
				}
				
				$o .= '</table>';
			}
			
			$o .= '<form action="' . $sn . '?&news&admin=plugin_main&category=' . $category->name() . '&action=config" method="POST">'
				. '<table class="edit" width="100%">';
			
			foreach ($config as $key => $value)
			{
				$o .= '<tr>'
					. '<td valign="top">' . $key . '</td>'
					. '<td><textarea name="' . $key . '">' . $value . '</textarea></td>'
					. '</tr>';
			}
			
			$o .= '<tr>'
				. '<td colspan="2"><button type="submit" name="config" class="submit news-submit">' . News::$tx["Save"] . '</button></td>'
				. '</tr>'
				. '</table>';
		}
		
		/**
		 * Remove selected category.
		 */
		if ($action == 'remove'
			AND $category !== FALSE)
		{
			if (isset($_POST['remove']))
			{
				$category->remove();
				unset($category);
				$o .= '<div class="news-success">' . News::$tx["Successfully deleted category."] . '</div>';
			}
			else
			{
				$o .= '<form action="' . $sn . '?&news&admin=plugin_main&category=' . $category->name() . '&action=remove" method="POST">'
					. '<div class="news-notice">' . News::$tx["Are you sure you want to delete the category with all its entries?"] . '</div><button name="remove" type="submit" class="pictures-submit submit">' . News::$tx["I'm sure."] . '</button>'
					. '</form>';
			}
		}
		
		/**
		 * Add a new entry.
		 * The entry can be published directly.
		 * Short and description can be collapsed through jquery
		 * The editor is epplied on short and description.
		 */
		if ($action == 'add'
			AND $category !== FALSE)
		{
			$o .= '<form action="' . $sn . '?&news&admin=plugin_main&category=' . $category->name() . '&action=plugin_text" method="POST">'
				. '<table class="edit" width="100%">'
				. '<tr>'
				. '<td>' . News::$tx["Title"] . '</td>'
				. '<td><input class="news-input-title" type="text" name="title" /></td>'
				. '</tr>'
				. '<tr>'
				. '<td>' . News::$tx["Category"].'</td>'
				. '<td>'.$category->name() . '</td>'
				. '</tr>'
				. '<tr>'
				. '<td colspan="2"><a href="#" class="news-collapsed" id="news-toggle-short">' . News::$tx["Short"] . '</a></td>'
				. '</tr>'
				. '<tr>'
				. '<td colspan="2" id="news-textarea-short"><textarea class="news-editor-short" name="short"></textarea></td>'
				. '</tr>';
				
				if (News::blog())
				{
				$o .= '<tr>'
					. '<td colspan="2"><a href="#" class="news-collapsed" id="news-toggle-description">' . News::$tx["Description"] . '</a></td>'
					. '</tr>'
					. '<tr>'
					. '<td colspan="2" id="news-textarea-description"><textarea class="news-editor" name="description"></textarea></td>'
					. '</tr>';
				}
				
				$o .= '<tr>'
				. '<td><input type="checkbox" name="publish" /> ' . News::$tx["Publish on"].'</td>'
				. '<td><input type="text" class="news-datepicker" name="publish-date" value="' . date(News::$cf['date_format_backend'], time()) . '" /></td>'
				. '</tr>'
				. '<tr>'
				. '<td colspan="2"><button type="submit" name="add" class="news-submit submit">' . News::$tx["Save"] . '</button></td>'
				. '</tr>'
				. '</table>'
				. '</form>';
			
			/**
			 * First init editors before hiding them!
			 */
			if (function_exists('init_editor'))
			{
				init_editor(array('news-editor', 'news-editor-short'));
			}
			
			if(file_exists($pth['folder']['plugins'] . 'jquery/jquery.inc.php'))
			{
				include_once($pth['folder']['plugins'] . 'jquery/jquery.inc.php'); 
				
				include_jQuery();
				include_JQueryUI();
			}
			else
			{
				News::include_script('jquery', 'jquery/js/' . News::$cf['jquery_core']);
				News::include_script('jquery-ui', 'jquery/js/' . News::$cf['jquery_ui']);
				News::include_style('jquery-ui', 'jquery/css/' . News::$cf['jquery_ui_css']);
			}
			
			$hjs .= '<script type="text/javascript">
						$(document).ready(function() {
							$(".news-datepicker").datepicker({
								dateFormat: "' . News::$cf['datepicker_format'] . '",
							});
							$("#news-textarea-short").hide()
							$("#news-toggle-short").on("click", function(event) {
								
								event.preventDefault();
								
								if($(this).hasClass("news-collapsed"))
								{
									$(this).removeClass("news-collapsed")
										.addClass("news-shown");
								}
								else
								{
									$(this).removeClass("news-shown")
										.addClass("news-collapsed");
								}
								
								$("#news-textarea-short").toggle();
							});
							
							$("#news-textarea-description").hide()
							$("#news-toggle-description").on("click", function(event) {
								
								event.preventDefault();
								
								if($(this).hasClass("news-collapsed"))
								{
									$(this).removeClass("news-collapsed")
										.addClass("news-shown");
								}
								else
								{
									$(this).removeClass("news-shown")
										.addClass("news-collapsed");
								}
								
								$("#news-textarea-description").toggle();
							});
						});
					</script>';
		}
		
		/**
		 * Edit given entry entry.
		 * Short and description can be collapsed through jquery
		 * The editor is epplied on short and description.
		 */
		if ($action == 'edit'
			AND $entry !== FALSE)
		{
			if (isset($_POST['edit']))
			{
				$entry->title(stsl($_POST['title']));
				$entry->description(News::blog() ? stsl($_POST['description']) : '');
				$entry->short(stsl($_POST['short']));
				$entry->state(isset($_POST['publish']) ? strtotime($_POST['publish-date']) : 0);
				
				$entry->save();
				
				$o .= '<div class="news-success">' . News::$tx["Successfully saved changes."] . '</div>';
			}
			
			$o .= '<form action="' . $sn . '?&news&admin=plugin_main&category=' . $category->name() . '&action=edit&entry=' . $entry->id() . '" method="POST">'
				. '<table class="edit" width="100%">'
				. '<tr>'
				. '<td>' . News::$tx["Title"] . '</td>'
				. '<td><input class="news-input-title" type="text" name="title" value="'.$entry->title().'" /></td>'
				. '</tr>'
				. '<tr>'
				. '<td colspan="2"><a href="#" class="news-collapsed" id="news-toggle-short">' . News::$tx["Short"] . '</a></td>'
				. '</tr>'
				. '<tr>'
				. '<td colspan="2" id="news-textarea-short"><textarea class="news-editor-short" name="short">' . $entry->short() . '</textarea></td>'
				. '</tr>'
				. '<tr>';
				
				if (News::blog())
				{
					$o .= '<td colspan="2"><a href="#" class="news-collapsed" id="news-toggle-description">' . News::$tx["Description"] . '</a></td>'
						. '</tr>'
						. '<tr>'
						. '<td colspan="2" id="news-textarea-description"><textarea class="news-editor" name="description">' . $entry->description() . '</textarea></td>'
						. '</tr>'
						. '<tr>';
				}
				
				$o .= '<td><input type="checkbox" name="publish" ' . ($entry->state() > 0 ? 'checked' : '') . ' /> ' . News::$tx["Publish on"].'</td>'
				. '<td colspan="2"><input type="text" class="news-datepicker" name="publish-date" value="' . date(News::$cf['date_format_backend'], ($entry->state() > 0 ? $entry->state() : time())) . '" /></td>'
				. '</tr>'
				. '<tr>'
				. '<td colspan="2"><button type="submit" name="edit" class="news-submit submit">' . News::$tx["Save"] . '</button></td>'
				. '</tr>'
				. '</table>'
				. '</form>';
			
			/**
			 * First init editors before hiding them!
			 */
			if (function_exists('init_editor'))
			{
				init_editor(array('news-editor', 'news-editor-short'));
			}
			
			if(file_exists($pth['folder']['plugins'] . 'jquery/jquery.inc.php'))
			{
				include_once($pth['folder']['plugins'] . 'jquery/jquery.inc.php'); 
				
				include_jQuery();
				include_JQueryUI();
			}
			else
			{
				News::include_script('jquery', 'jquery/js/' . News::$cf['jquery_core']);
				News::include_script('jquery-ui', 'jquery/js/' . News::$cf['jquery_ui']);
				News::include_style('jquery-ui', 'jquery/css/' . News::$cf['jquery_ui_css']);
			}
			
			$hjs .= '<script type="text/javascript">
						$(document).ready(function() {
							$(".news-datepicker").datepicker({
								dateFormat: "' . News::$cf['datepicker_format'] . '",
							});
							$("#news-textarea-short").hide()
							$("#news-toggle-short").on("click", function(event) {
								
								event.preventDefault();
								
								if($(this).hasClass("news-collapsed"))
								{
									$(this).removeClass("news-collapsed")
										.addClass("news-shown");
								}
								else
								{
									$(this).removeClass("news-shown")
										.addClass("news-collapsed");
								}
								
								$("#news-textarea-short").toggle();
							});
							
							$("#news-textarea-description").hide()
							$("#news-toggle-description").on("click", function(event) {
								
								event.preventDefault();
								
								if($(this).hasClass("news-collapsed"))
								{
									$(this).removeClass("news-collapsed")
										.addClass("news-shown");
								}
								else
								{
									$(this).removeClass("news-shown")
										.addClass("news-collapsed");
								}
								
								$("#news-textarea-description").toggle();
							});
						});
					</script>';
		}
		
		/**
		 * Delete given entry.
		 * The deleteion si done on the mane page.
		 */
		if ($action == 'delete'
			AND $entry !== FALSE)
		{
			$o .= '<form action="' . $sn . '?&news&admin=plugin_main&category=' . $category->name() . '&action=plugin_text&entry=' . $entry->id() . '" method="POST">'
				. '<div class="news-notice">' . News::$tx["Are you sure you want to delete the entry?"] . '</div><button name="delete" type="submit" class="news-submit submit">' . News::$tx["I'm sure."] . '</button>'
				. '</form>';
		}

		$o .= '</p>';
	}
	
	if ($admin != 'plugin_main')
	{
		$hint = array(
			'mode_donotshowvarnames' => FALSE,
		);

		$o .= plugin_admin_common($action, $admin, $plugin, $hint);
	}
	
}

?>