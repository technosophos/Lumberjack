<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title><?php print $title; ?></title>
	<script src="media/js/jquery.js"></script>
	<script src="media/js/jquery.dataTables.js"></script>
	<script>$(document).ready(function () {
	  $('#logdata').dataTable({
	    "bJQueryUI": true
	  });
	});</script>
	<!--
	<link href="media/css/demo_page.css" rel="stylesheet"></link>
	<link href="media/css/demo_table.css" rel="stylesheet"></link>
	<link href="media/css/demo_table_jui.css" rel="stylesheet"></link>
	-->
	<style>
	@import "media/css/demo_table_jui.css";
	@import "media/css/ui-darkness/jquery-ui-1.8.13.custom.css";
	</style>
</head>
<body>
<h1><?php print $title; ?></h2>