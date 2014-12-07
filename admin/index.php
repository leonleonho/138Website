<?php
session_start();
if(!isset($_SESSION['id'])) {
    header('Location: login.html');
    die();
}
date_default_timezone_set('America/Vancouver');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Control Panel</title>
	<link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="../css/froala_editor.min.css" rel="stylesheet" type="text/css">
	<link href="../css/froala_reset.min.css" rel="stylesheet" type="text/css">

	<link href="../css/lightview.css" rel="stylesheet" type="text/css">

	<link href="../css/jquery.jgrowl.min.css" rel="stylesheet" type="text/css">

	<link rel="stylesheet" href="style.css"/>

	<script src="//connect.facebook.net/en_US/sdk.js"></script>
	<script src="../js/jquery.min.js"></script>
	<script src="../js/beautify/beautify-html.js"></script>
	<script src="../js/froala_editor.min.js"></script>
	<script src="../js/plugins/block_styles.min.js"></script>

	<script type="text/javascript" src="../js/lightview/lightview.js"></script>
	<script type="text/javascript" src="../js/spinners/spinners.min.js"></script>
	<script type="text/javascript" src="../js/lightview/lightview.js"></script>

	<script type="text/javascript" src="../js/jquery.jgrowl.min.js"></script>

	<script type="text/javascript" src="../js/isotope.pkgd.min.js"></script>

    <script type="text/javascript" src="//www.datejs.com/build/date.js"></script>


	<!--[if lt IE 9]>
	<script src="../js/froala_editor_ie8.min.js"></script>
	<script type="text/javascript" src="../js/excanvas/excanvas.js"></script>
	<![endif]-->

	<script src="js/main.js"></script>
</head>
<script>
	var ACCESS_TOKEN = "524905380971501|vwvIt1ualZz_1V5DX17el6NJPe0";
	FB.init({
	    version: 'v2.0',
	    appId: 524905380971501
	});
</script>
<body>
    <div id="header">
        <div id="title"></div>
    </div>
    <div id="menu" valign="top">
        <ul>
            <li id="nav-create" class="selected"><a href="#" onclick="showHide('#create');return false;">Create Post</a></li>
            <li id="nav-edit"><a href="#" onclick="showHide('#list');return false;">Past Posts</a></li>
            <li id="nav-calendar"><a href="#" onclick="showHide('#calendar');return false;">Calendar</a></li>
            <li id="nav-logout"><a href="php/logoff.php" onclick="if(!confirm('Log off?')) return false;">Log Off</a></li>
        </ul>
    </div>

    <div id="content" valign="top">
        <div class="scroll-wrap">
            <div id="create" class="page"><h1>Create Post</h1>
                <form id="new-post" mode="new" onsubmit="submitPost(this); return false;">
                    <div style="float: left; margin-right: 50px;">
                        <label for="new-title">Title:</label><br />
                        <input type="text" name="title" id="new-title" /><br /><br />

                        Sections Involved:<br />
                        <input type="checkbox" name="sections[]" value="beavers" id="new-section-beavers" /><label for="new-section-beavers">Beavers</label><br />
                        <input type="checkbox" name="sections[]" value="cubs" id="new-section-cubs" /><label for="new-section-cubs">Cubs</label><br />
                        <input type="checkbox" name="sections[]" value="scouts" id="new-section-scouts" /><label for="new-section-scouts">Scouts</label><br />
                        <input type="checkbox" name="sections[]" value="venturers" id="new-section-venturers" /><label for="new-section-venturers">Venturers</label><br />
                    </div>

                    <div style="float: left;">
                        <label for="new-album">Facebook Photo Album:</label><br />
                        <input type="number" name="album" id="new-album" /> <a href="#" onclick="fbAlbumPicker();return false;">pick</a><br /><br />

                        <label for="new-date">Date: (YYYY.MM.DD)</label><br />
                        <input type="text" name="date" id="new-date" value="<?= date("Y.m.d"); ?>"/><br />
                    </div>

                    <div style="clear: both;"></div><br/>

                    <label for="new-description">Facebook Photo Album:</label><br />
                    <textarea rows="50" cols="100" name="description" id="new-description"></textarea><br />

                    <input type="submit" value="Create Post" />
                    <button value = "Preview Post" onclick = "preview($('#new-title').val(),$('#new-album').val(),$('#new-description').editable('getHTML')[0])">Preview Post</button>
                </form>
            </div>

            <div id="list" class="multi page" style="display: none;">
                <div class="slide" id="rlist" style="display: block;">
                    <h1 style="display:inline-block;margin-right: 15px;">Past Posts</h1><a href="javascript: grabPosts()">refresh</a>
                    <div id="post-list">
                    </div>
                </div>
                <div class="slide" id="edit">
                    <h1>Edit Post</h1>
                    <form id="edit-post" mode="edit" onsubmit="submitPost(this); return false;">
                        <input type="hidden" name="post-id" id="edit-id" />

                        <div style="float: left; margin-right: 50px;">
                            <label for="edit-title">Title:</label><br />
                            <input type="text" name="title" id="edit-title" /><br /><br />

                            Sections Involved:<br />
                            <input type="checkbox" name="sections[]" value="beavers" id="edit-section-beavers" /><label for="edit-section-beavers">Beavers</label><br />
                            <input type="checkbox" name="sections[]" value="cubs" id="edit-section-cubs" /><label for="edit-section-cubs">Cubs</label><br />
                            <input type="checkbox" name="sections[]" value="scouts" id="edit-section-scouts" /><label for="edit-section-scouts">Scouts</label><br />
                            <input type="checkbox" name="sections[]" value="venturers" id="edit-section-venturers" /><label for="edit-section-venturers">Venturers</label><br />
                        </div>

                        <div style="float: left;">
                            <label for="edit-album">Facebook Photo Album:</label><br />
                            <input type="number" name="album" id="edit-album" /> <a href="#" onclick="fbAlbumPicker();return false;">pick</a><br /><br />

                            <label for="edit-date">Date: (YYYY.MM.DD)</label><br />
                            <input type="text" name="date" id="edit-date" /><br />
                        </div>

                        <div style="clear: both;"></div><br/>

                        <label for="edit-description">Facebook Photo Album:</label><br />
                        <textarea rows="50" cols="100" name="description" id="edit-description"></textarea><br />

                        <input type="submit" value="Save" />
                        <input type="button" value="Preview" onclick="preview($('#edit-title').val(),$('#edit-album').val(),$('#edit-description').editable('getHTML')[0])" />
                        <input type="button" value="Cancel" onclick="backToSlideFrom('#rlist', '#edit');" />
                    </form>
                </div>
            </div>

            <div id="calendar" class="page" style="display: none;"><h1>Calendar</h1>
                <iframe src="https://www.google.com/calendar/embed?src=pccrovers.com_pojeic2sd1ojijt7ohop7gt338%40group.calendar.google.com&ctz=America/Vancouver" style="border: 0" width="100%" height="600" frameborder="0" scrolling="no"></iframe>
            </div>
        </div>
    </div>

<div id="preview" style="display: none;">
		<div style="font-size: 2em; margin-bottom: 10px;" id="pre-title"></div>
		<div style="width: 590px;height: 332px;background-repeat: no-repeat;background-size: cover;background-position: center;" id="pre-image"></div>
		<div style="margin-top: 10px;" id="pre-desc"></div>
</div>

<div id="album-selector" style="display:none;">
	<h1>Please pick an album</h1>
	<div id="masonr"></div>
</div>

<div id="spinner" style="display: none;"></div>

<script>
	$(function(){
		grabPosts();

		$("#new-post > textarea").editable({
			inlineMode: false
		});

		$("#edit-post > textarea").editable({
			inlineMode: false
		});
	});

	var spinners = Spinners.create('#spinner', {
		radius: 16,
		dashes: 25,
		width: 1.5,
		height: 25,
		opacity: 1,
		padding: 3,
	});

	var iso = $("#masonr").isotope({
		itemSelector: '.item',
		masonry: {
			columnWidth: 150
		}
	});
	iso.isotope("on", "layoutComplete", function() {
		Lightview.refresh();
	});
</script>

</body>
</html>