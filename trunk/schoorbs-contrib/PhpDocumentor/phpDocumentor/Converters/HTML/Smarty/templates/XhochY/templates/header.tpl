<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de">
<head>
<title>{$title}</title>
<link rel="stylesheet" type="text/css" href="{$subdir}media/style.css" />
</head>
<body>

<table border="0" cellspacing="0" cellpadding="0" style="weight: 100%;">
  <tr>
    <td class="header_top">{$package}</td>
  </tr>
  <!--<tr><td class="header_line"><img src="{$subdir}media/empty.png" width="1" height="1" border="0" alt=""  /></td></tr>-->
  <tr>
    <td class="header_menu">
  		  [ <a href="{$subdir}classtrees_{$package}.html" class="menu">class tree: {$package}</a> ]
		  [ <a href="{$subdir}elementindex_{$package}.html" class="menu">index: {$package}</a> ]
		  [ <a href="{$subdir}elementindex.html" class="menu">all elements</a> ]
    </td>
  </tr>
  <!--<tr><td class="header_line"><img src="{$subdir}media/empty.png" width="1" height="1" border="0" alt=""  /></td></tr>-->
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr valign="top">
    <td width="250" class="menu">
{if count($ric) >= 1}
	<div id="ric">
		{section name=ric loop=$ric}
			<p><a href="{$subdir}{$ric[ric].file}">{$ric[ric].name}</a></p>
		{/section}
	</div>
{/if}
{if $hastodos}
	<div id="todolist">
			<p><a href="{$subdir}{$todolink}">Todo List</a></p>
	</div>
{/if}
      <b>Packages:</b><br />
      {section name=packagelist loop=$packageindex}
        <a href="{$subdir}{$packageindex[packagelist].link}">{$packageindex[packagelist].title}</a><br />
      {/section}
      <br /><br />
{if $tutorials}
		<b>Tutorials/Manuals:</b><br />
		{if $tutorials.pkg}
			<strong>Package-level:</strong>
			{section name=ext loop=$tutorials.pkg}
				{$tutorials.pkg[ext]}
			{/section}
		{/if}
		{if $tutorials.cls}
			<strong>Class-level:</strong>
			{section name=ext loop=$tutorials.cls}
				{$tutorials.cls[ext]}
			{/section}
		{/if}
		{if $tutorials.proc}
			<strong>Procedural-level:</strong>
			{section name=ext loop=$tutorials.proc}
				{$tutorials.proc[ext]}
			{/section}
		{/if}
{/if}
      {if !$noleftindex}{assign var="noleftindex" value=false}{/if}
      {if !$noleftindex}
      {if $compiledfileindex}
      <b>Files:</b><br />
      {eval var=$compiledfileindex}
      {/if}

      {if $compiledinterfaceindex}
      <b>Interfaces:</b><br />
      {eval var=$compiledinterfaceindex}
      {/if}

      {if $compiledclassindex}
      <b>Classes:</b><br />
      {eval var=$compiledclassindex}
      {/if}
      {/if}
    </td>
    <td>
      <table cellpadding="10" cellspacing="0" width="100%" border="0"><tr><td valign="top">

{if !$hasel}{assign var="hasel" value=false}{/if}
{if $hasel}
<h1>{$eltype|capitalize}: {$class_name}</h1>
Source Location: {$source_location}<br /><br />
{/if}