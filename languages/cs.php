<?php
/**
 * @file cs.php
 * @brief Language file cs.
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

	/* Plugin bar. */
	$plugin_tx["news"]['menu_main'] = "Administrace záznamů";
	
	/* Titles. */
	$plugin_tx["news"]["title_plugin_text"]="Manage your news entries";
	$plugin_tx["news"]["title_new"]="New Category";
	$plugin_tx["news"]["title_add"]="Přidat záznam";
	$plugin_tx["news"]["title_remove"]="Odstranit kategorii";
	$plugin_tx["news"]["title_edit"]="Editovat záznam";
	$plugin_tx["news"]["title_rss"]="Category RSS";
	$plugin_tx["news"]["title_delete"]="Vymazet záznam";
	$plugin_tx["news"]["title_config"]="Category config";
	
	/* Main plugin. */
	$plugin_tx["news"]["Manage your news entries"]="Správa vašich záznamů";
	$plugin_tx["news"]["Category"]="Kategorie";
	$plugin_tx["news"]["New category"]="Nová kategorie";
	$plugin_tx["news"]["Successfully created new category."]="Nová kategorie úspěšně vytvořena.";
	$plugin_tx["news"]["Fill a valid name."]="Fill a valid name.";
	$plugin_tx["news"]["Name"]="Název";
	$plugin_tx["news"]["Save"]="Uložit";
	$plugin_tx["news"]["Successfully saved changes."]="Změny byly uloženy.";
	$plugin_tx["news"]["Fill a title and select a category."]="Vyplňte název a vyberte kategorii";
	$plugin_tx["news"]["Are you sure you want to delete the entry?"]="Opravdu chcete vymazat záznam?";
	$plugin_tx["news"]["I'm sure."]="Ano, chci.";
	$plugin_tx["news"]["Description"]="Popis";
	$plugin_tx["news"]["Title"]="Titulek";
	$plugin_tx["news"]["Successfully deleted entry."]="Záznam byl odstraněn.";
	$plugin_tx["news"]["Successfully added entry."]="Záznam byl uložen.";
	$plugin_tx["news"]["No categories found. Create a new one."]="Kategorie nenalezena. Nejprve ji vytvořte.";
	$plugin_tx["news"]["Created"]="Vytvořeno";
	$plugin_tx["news"]["State"]="Status";
	$plugin_tx["news"]["Publish immediately."]="Publikovat ihned.";
	$plugin_tx["news"]["Publish."]="Publikováno.";
	$plugin_tx["news"]["Not published"]="Nezveřejněné";
	$plugin_tx["news"]["Published"]="Publikované";
	$plugin_tx["news"]["More..."]="Více...";
	$plugin_tx["news"]["See all categories"]="Zobrazit všechny kategorie";
	$plugin_tx["news"]["Are you sure you want to delete the category with all its entries?"] = "Jste si jisti že chcete smazat kategorii se všemi záznamy?";
	$plugin_tx["news"]["Successfully deleted category."] = "Kategorie byla vymazána.";
	$plugin_tx["news"]["News category not found."] = "Kategorie nebyla nalezena.";
	$plugin_tx["news"]["Fill a title."] = "Zadejte titulek.";
	$plugin_tx["news"]["Entry successfully published."]="Entry successfully published.";
	$plugin_tx["news"]["Entry successfully taken out."]="Entry successfully taken out.";
	$plugin_tx["news"]["Remove category"]="Remove category";
	$plugin_tx["news"]["Delete the selected category."]="Delete the selected category.";
	$plugin_tx["news"]["See the RSS link of the category."]="See the RSS link of the category.";
	$plugin_tx["news"]["Add a new category."]="Add a new category.";
	$plugin_tx["news"]["Add a new entry to this category."]="Add a new entry to this category.";
	$plugin_tx["news"]["Edit the selected entry."]="Edit the selected entry.";
	$plugin_tx["news"]["Publish the selected entry."]="Publish the selected entry.";
	$plugin_tx["news"]["Take out the selected entry."]="Take out the selected entry.";
	$plugin_tx["news"]["Delete the selected entry."]="Delete the selected entry.";
	$plugin_tx["news"]["No category specified."]="No category specified.";
	$plugin_tx["news"]["No entries found."]="No entries found.";
	$plugin_tx["news"]["Category not found."]="Category not found.";
	$plugin_tx["news"]["The category's name should not contain any whitespace or special characters."]="The category's name should not contain any whitespace or special characters.";
	$plugin_tx["news"]["Edit the settings of the selected gallery."]="Edit the settings of the selected gallery.";
	$plugin_tx["news"]["Next page:"]="Next page:";
	$plugin_tx["news"]["Previous page:"]="Previous page:";
	$plugin_tx["news"]["Short"]="Short";
	$plugin_tx["news"]["Edit the selected entry:"]="Edit the selected entry:";
	$plugin_tx["news"]["Publish the selected entry:"]="Publish the selected entry:";
	$plugin_tx["news"]["Take out the selected entry:"]="Take out the selected entry:";
	$plugin_tx["news"]["Delete the selected entry:"]="Delete the selected entry:";
	
	/* Configuration help. */
	$plugin_tx["news"]["cf_filepath"]="Filepath where to store CSV files, relative to root. Note: Without '/' at the end. String.";
	$plugin_tx["news"]["cf_delimiter"]="Delimiter between cells in all CSV files. Single ASCII character.";
	$plugin_tx["news"]["cf_date_format"]="Date format used in frontend. See http://php.net/manual/de/function.date.php.";
	$plugin_tx["news"]["cf_date_format_backend"]="Date format used in backend. See http://php.net/manual/de/function.date.php.";
	$plugin_tx["news"]["cf_speed"]="Newsticker speed. Unsigned integer.";
	$plugin_tx["news"]["cf_controls"]="Display controls (Stop, Resume, Next, Previous). 'true' or 'false'.";
	$plugin_tx["news"]["cf_title_text"]="Title text for newsticker. String.";
	$plugin_tx["news"]["cf_effect"]="Display effect. 'reveal' or 'fade'.";
	$plugin_tx["news"]["cf_delay"]="Time between news. Unsigned integer.";
	$plugin_tx["news"]["cf_fade_in_speed"]="Fade-in speed of newsticker. Unsigned integer.";
	$plugin_tx["news"]["cf_fade_out_speed"]="Fade-out speed of newsticker. Unsigned integer.";
	$plugin_tx['news']['cf_template']="'true' if newsticker will be used within your templates, 'false' otherwise.";
	$plugin_tx['news']['cf_enclosure']="The enclosure used in the CSV files. Single ASCII character.";
	$plugin_tx["news"]["cf_rss_title"]="The title of the category RSS feed.";
	$plugin_tx["news"]["cf_rss_description"]="The description of the category RSS feed.";
	$plugin_tx["news"]["cf_rss_link"]="Link to the news page or blog page of this category, see documentation for details.";
	$plugin_tx["news"]["cf_rss_editor"]="The editor of the category RSS feed.";
	$plugin_tx["news"]["cf_newsslider_speed"]="The speed of the slider. Unsigned Integer between 1 and 5000.";
	$plugin_tx["news"]["cf_newsslider_pause_hover"]="Pause the slider on mouse over. 'true' or 'false'.";
	$plugin_tx["news"]["cf_blog"]="The system can either be used as classical news system or as more 'blog'-like systems. 'true' or 'false'.";
	$plugin_tx["news"]["cf_datepicker_format"]="Format used in datepicker. See help for possible formatting.";
	$plugin_tx["news"]["cf_pagination_backend"]="The number of entries per page in backend. Unsigned integer (not zero).";
	$plugin_tx["news"]["cf_pagination_archive"]="The number of entries per page in archive. Unsigned integer (not zero).";
	$plugin_tx["news"]["cf_rss_file"]="The path of the RSS file including the file itself, relative to CMSimple root.";
	$plugin_tx["news"]["cf_template_newsticker"]="If you are using the newsticker functionality in your templates this option must be set to 'true' (case-sensitice). 'true' or 'false'.";
	$plugin_tx["news"]["cf_template_newsslider"]="If you are using the newsslider functionality in your templates this option must be set to 'true' (case-sensitice). 'true' or 'false'.";
	$plugin_tx["news"]["cf_jquery_core"]="jQUery core file name. The jQuery (UI) javascript files are stored in news/jquery/js/. String.";
	$plugin_tx["news"]["cf_jquery_ui"]="jQUery UI javascript file name. The jQuery (UI) javascript files are stored in news/jquery/js/. String.";
	$plugin_tx["news"]["cf_jquery_ui_css"]="jQUery UI CSS file name. The jQuery (UI) CSS files are stored in news/jquery/css/. String.";
	$plugin_tx["news"]["cf_categories_sort_function"]="The function to use for sorting the categories. Currently 'news_categories_sort_asc' and 'news_categories_sort_desc' supported.";

?>
