<?php // utf8-marker = äöü
if(!defined('CMSIMPLE_VERSION') || preg_match('/content.php/i', $_SERVER['SCRIPT_NAME']))
{
	die('No direct access');
}
?>
<h1>Overview</h1>
<p>This installation of CMSimple demonstrates a couple of CMSimple plugins using the standard template:</p>
<ul>
<li><a href="" target="_blank">CMSimple News</a>, <a href="" target="_blank">GitHub</a>, <a href="" target="_blank">Documentation</a></li>
<li><a href="" target="_blank">CMSimple Pictures</a>, <a href="" target="_blank">GitHub</a>, <a href="" target="_blank">Documentation</a></li>
<li><a href="" target="_blank">CMSimple Youtube</a>, <a href="" target="_blank">GitHub</a>, <a href="" target="_blank">Documentation</a></li>
<li><a href="" target="_blank">CMSimple BBClone</a>, <a href="" target="_blank">GitHub</a>, <a href="" target="_blank">Documentation</a></li>
</ul>
<p>See the following subpages:</p>
<h1>News</h1>
<p class="cmsimplecore_warning">All plugin calls are wrapped in three opening and closing braces!</p>
<p>The basic plugin call <code>{plugin:news('news', 5);}</code> (with three opening and closing braces, as detailed in the <a href="" target="_blank">documentation</a>) generates the following list of (up to five) news entries:</p>
{{{plugin:news('news', 5);}}}
<h2>Newscase</h2>
<p>A newscase can be called using <code>{plugin:newscase('News', 'news', '-5 years');}</code>. It only shows news entries from the last five years:</p>
{{{plugin:newscase('News', 'news', '-5 years');}}}
<h2>Newsticker</h2>
<p>The newsticker can be called using <code>{plugin:newsticker('news', 5);}</code>:</p>
{{{plugin:newsticker('news', 5);}}}
<h2>Newsscroller</h2>
<p>The newsscroller can be called using <code>{plugin:newsscroller('news', 5);}</code>:</p>
{{{plugin:newsscroller('news', 5);}}}
<h2>Newsslider</h2>
<p>The newsslider can be called using <code>{plugin:newsslider('news', 5, TRUE);}</code>:</p>
{{{plugin:newsslider('news', 5, TRUE);}}}
<h1>Pictures</h1>
<h1>Youtube</h1>
