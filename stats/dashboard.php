<?php
	//$start_date = 1335830400;
	//$end_date = 1338508799;
	
	$start_date = strtotime($_GET['sd'] . " 00:00:00");
	$end_date = strtotime($_GET['ed'] . " 23:59:59");
	$human_date = date('F Y', $start_date);
	
	mysql_connect('148.96.125.127','theblock','ie1up)sh]y!YtG]tHQ3g')
		or die("Unable to connect to MySQL!");
	@mysql_select_db("theblock")
		or die("Unable to select database!");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html>
	<head>
		<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700|Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<link type="text/css" href="css/smoothness/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
		<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.21.custom.min.js"></script>
		<link rel="stylesheet" type="text/css" href="stats.css">
		<title>Performance Dashboard</title>
		<script>
			$(function() {
				$( "#startdatepicker" ).datepicker({
					defaultDate: "+1w",
					changeMonth: true,
					numberOfMonths: 2,
					onSelect: function( selectedDate ) {
						$( "#enddatepicker" ).datepicker( "option", "minDate", selectedDate );
					}
				});
				$( "#enddatepicker" ).datepicker({
					defaultDate: "+1w",
					changeMonth: true,
					numberOfMonths: 2,
					onSelect: function( selectedDate ) {
						$( "#startdatepicker" ).datepicker( "option", "maxDate", selectedDate );
					}
				});
			});
		</script>
	</head>
	<body>
		<form action="dashboard.php" method="GET">
		<header>The BLOCK Performance Dashboard <?php print $human_date; ?></header>
		<footer>Metrics data collected from <input type="text" name="sd" value="<?php print $_GET['sd']; ?>" id="startdatepicker" size="10" /> through <input type="text" name="ed" value="<?php print $_GET['ed']; ?>" id="enddatepicker" size="10" /><input type="submit" value="Update" /></footer>
		<article>
			<h2 >General Statistics</h2>
<?php
$query="SELECT name FROM users WHERE created >= '" . $start_date . "' AND created <= '" . $end_date . "'";
$result = mysql_query($query) or die(mysql_error());
$user_count = mysql_num_rows($result);
?>
			<p>New user registrations: <b><?php print $user_count; ?></b></p>
<?php
$result = mysql_query("SELECT name FROM users WHERE created <= '" . $start_date . "'")
		or die(mysql_error());
$total_user_count = mysql_num_rows($result);
?>
			<p>Total registered users: <b><?php print $total_user_count+$user_count; ?></b></p>
<?php
$result = mysql_query("SELECT title FROM node WHERE created >= '" . $start_date . "' AND created <= '" . $end_date . "'")
		or die(mysql_error());
$node_count = mysql_num_rows($result);
?>
			<p>New content posted: <b><?php print $node_count; ?></b></p>
<?php
$result = mysql_query("SELECT title FROM node WHERE created <= '" . $start_date . "'")
		or die(mysql_error());
$node_total_count = mysql_num_rows($result);
?>
			<p>Total content: <b><?php print $node_total_count+$node_count; ?></b></p>
<?php
$query="SELECT subject FROM comment WHERE created >= '" . $start_date . "' AND created <= '" . $end_date . "'";
$result = mysql_query($query) or die(mysql_error());
$comment_count = mysql_num_rows($result);
?>
			<p>New comments posted: <b><?php print $comment_count; ?></b></p>
			<p>Total new posts: <b><?php print $comment_count+$node_count; ?></b></p>
<?php
$result = mysql_query("SELECT title FROM node WHERE created >= '" . $start_date . "' AND created <= '" . $end_date . "' AND type = 'forum'")
		or die(mysql_error());
$result2 = mysql_query("SELECT subject FROM comment,node WHERE comment.created >= '" . $start_date . "' AND comment.created <= '" . $end_date . "' AND comment.nid = node.nid AND node.type = 'forum'")
		or die(mysql_error());
$forum_post_count = mysql_num_rows($result)+mysql_num_rows($result2);
?>
			<p>New forum posts: <b><?php print $forum_post_count; ?></b></p>
<?php
$result = mysql_query("SELECT title FROM node WHERE created <= '" . $start_date . "' AND type = 'forum'")
		or die(mysql_error());
$result2 = mysql_query("SELECT subject FROM comment,node WHERE comment.created <= '" . $start_date . "' AND comment.nid = node.nid AND node.type = 'forum'")
		or die(mysql_error());
$forum_post_total_count = mysql_num_rows($result)+mysql_num_rows($result2);
?>
			<p>Total forum posts: <b><?php print $forum_post_total_count+$forum_post_count; ?></b></p>
			<?php
$result = mysql_query("SELECT title FROM node WHERE created >= '" . $start_date . "' AND created <= '" . $end_date . "' AND type = 'forum'")
		or die(mysql_error());
$forum_count = mysql_num_rows($result);
?>
			<p>New forum topics: <b><?php print $forum_count; ?></b></p>
<?php
$result = mysql_query("SELECT title FROM node WHERE created <= '" . $start_date . "' AND type = 'forum'")
		or die(mysql_error());
$forum_total_count = mysql_num_rows($result);
?>
			<p>Total forum topics: <b><?php print $forum_total_count+$forum_count; ?></b></p>
		</article>
		<article>
			<h2 style="margin-bottom: -30px;">New Content Posted by Content Type</h2>
<?php
$dayofmonth = 1;
$csvdata = "Type,Quantity<br />";
$chartdata = "['Type', 'Quantity'],\r\n";

$nodetypes = mysql_query("SELECT type FROM node_type")
		or die(mysql_error());
		
while($row = mysql_fetch_array($nodetypes)) {
	$csvdata .= $row['type'] . ",";
	$chartdata .= "['" . $row['type'] . "', ";
	
	$result = mysql_query("SELECT title FROM node WHERE created >= '" . $start_date . "' AND created <= '" . $end_date . "' AND type = '" . $row['type'] . "'")
		or die(mysql_error());
	
	$csvdata .= mysql_num_rows($result) . "<br />";
	$chartdata .= mysql_num_rows($result) . "],\r\n";
}

$csvdata .= "comments,";
$chartdata .= "['comments', ";

$result = mysql_query("SELECT subject FROM comment WHERE created >= '" . $start_date . "' AND created <= '" . $end_date . "'")
		or die(mysql_error());
		
$csvdata .= mysql_num_rows($result) . "<br />";
$chartdata .= mysql_num_rows($result) . "],\r\n";
?>
			<script type="text/javascript">
				google.load("visualization", "1", {packages:["corechart"]});
				google.setOnLoadCallback(drawContentTypeChart);
				function drawContentTypeChart() {
					var data = google.visualization.arrayToDataTable([
						<?php print $chartdata; ?>
					]);
					
					var chart = new google.visualization.PieChart(document.getElementById('content_type_chart'));
					chart.draw(data);
				}
		    </script>
	    	<blockquote>
			    <?php print $csvdata; ?>
		    </blockquote>
	    	<div id="content_type_chart" style="width: 900px; height: 500px;"></div>
	    </article>
		<article>
			<h2 style="margin-bottom: -30px;">New Content Posted by Date</h2>
<?php
$dayofmonth = 1;
$csvdata = "Date,Nodes,Articles,Forum Posts,Comments<br />";
$chartdata = "['Date', 'Nodes', 'Articles', 'Forum Posts', 'Comments'],\r\n";
for ($current = $start_date; $current < $end_date; $current += 86400) {
	$csvdata .= date('M', $current) . "-" . date('d', $current) . ",";
	$chartdata .= "['" . date('M', $current) . "-" . date('d', $current) . "', ";
	
	$timeend=$current+86400;
	$result = mysql_query("SELECT title FROM node WHERE created >= '" . $current . "' AND created <= '" . $timeend . "'")
		or die(mysql_error());
	$csvdata .= mysql_num_rows($result) . ",";
	$chartdata .= mysql_num_rows($result) . ", ";
	
	$result = mysql_query("SELECT title FROM node WHERE created >= '" . $current . "' AND created <= '" . $timeend . "' AND type = 'article'")
		or die(mysql_error());
	$csvdata .= mysql_num_rows($result) . ",";
	$chartdata .= mysql_num_rows($result) . ", ";
	
	$result = mysql_query("SELECT title FROM node WHERE created >= '" . $current . "' AND created <= '" . $timeend . "' AND type = 'forum'")
		or die(mysql_error());
	$result2 = mysql_query("SELECT subject FROM comment,node WHERE comment.created >= '" . $current . "' AND comment.created <= '" . $timeend . "' AND comment.nid = node.nid AND node.type = 'forum'")
		or die(mysql_error());
	$csvdata .= mysql_num_rows($result)+mysql_num_rows($result2) . ",";
	$chartdata .= mysql_num_rows($result)+mysql_num_rows($result2) . ", ";
	
	$result = mysql_query("SELECT subject FROM comment WHERE created >= '" . $current . "' AND created <= '" . $timeend . "'")
		or die(mysql_error());
	$csvdata .= mysql_num_rows($result) . "<br />";
	$chartdata .= mysql_num_rows($result) . "],\r\n";
	
	$dayofmonth++;
}
?>
			<script type="text/javascript">
				google.load("visualization", "1", {packages:["corechart"]});
				google.setOnLoadCallback(drawChart);
				function drawChart() {
					var data = google.visualization.arrayToDataTable([
						<?php print $chartdata; ?>
					]);
					
					var chart = new google.visualization.AreaChart(document.getElementById('content_stats_chart'));
					chart.draw(data);
				}
		    </script>
	    	<blockquote>
			    <?php print $csvdata; ?>
		    </blockquote>
	    	<div id="content_stats_chart" style="width: 900px; height: 500px;"></div>
	    </article>
	    <article>
			<h2 style="margin-bottom: -30px;">New User Registrations by Date</h2>
<?php
$dayofmonth = 1;
$csvdata = "Date,Registrations<br />";
$chartdata = "['Date', 'Registrations'],\r\n";
for ($current = $start_date; $current < $end_date; $current += 86400) {
	$csvdata .= date('M', $current) . "-" . date('d', $current) . ",";
	$chartdata .= "['" . date('M', $current) . "-" . date('d', $current) . "', ";
	
	$timeend=$current+86400;
	$result = mysql_query("SELECT name FROM users WHERE created >= '" . $current . "' AND created <= '" . $timeend . "'")
		or die(mysql_error());
	$csvdata .= mysql_num_rows($result) . "<br />";
	$chartdata .= mysql_num_rows($result) . "],\r\n";
	
	$dayofmonth++;
}
?>
			<script type="text/javascript">
				google.setOnLoadCallback(drawChart2);
				function drawChart2() {
					var data = google.visualization.arrayToDataTable([
						<?php print $chartdata; ?>
					]);
					
					var chart = new google.visualization.AreaChart(document.getElementById('registration_stats_chart'));
					chart.draw(data);
				}
		    </script>
	    	<blockquote>
			    <?php print $csvdata; ?>
		    </blockquote>
	    	<div id="registration_stats_chart" style="width: 900px; height: 500px;"></div>
	    </article>
	    <article>
			<h2 style="margin-bottom: -30px;">Total Registrations and Content Posts by Date</h2>
<?php
$dayofmonth = 1;
$csvdata = "Date,Users,Content,Comments,Thumbs Up<br />";
$chartdata = "['Date', 'Users', 'Content', 'Comments', 'Thumbs Up'],\r\n";

$result = mysql_query("SELECT name FROM users WHERE created <= '" . $start_date . "'")
		or die(mysql_error());
$currently_registered = mysql_num_rows($result);

$result = mysql_query("SELECT title FROM node WHERE created <= '" . $start_date . "'")
		or die(mysql_error());
$current_content = mysql_num_rows($result);

$result = mysql_query("SELECT subject FROM comment WHERE comment.created <= '" . $start_date . "'")
		or die(mysql_error());
$current_comments = mysql_num_rows($result);

$result = mysql_query("SELECT fcid FROM flag_content WHERE timestamp <= '" . $start_date . "'")
		or die(mysql_error());
$current_likes = mysql_num_rows($result);

for ($current = $start_date; $current < $end_date; $current += 86400) {
	$csvdata .= date('M', $current) . "-" . date('d', $current) . ",";
	$chartdata .= "['" . date('M', $current) . "-" . date('d', $current) . "', ";
	
	$timeend=$current+86400;
	$result = mysql_query("SELECT name FROM users WHERE created >= '" . $current . "' AND created <= '" . $timeend . "'")
		or die(mysql_error());
	$currently_registered += mysql_num_rows($result);
	$csvdata .= $currently_registered . ",";
	$chartdata .= $currently_registered . ", ";
	
	$result = mysql_query("SELECT title FROM node WHERE created >= '" . $current . "' AND created <= '" . $timeend . "'")
		or die(mysql_error());
	$current_content += mysql_num_rows($result);
	$csvdata .= $current_content . ",";
	$chartdata .= $current_content . ", ";
		
	$result = mysql_query("SELECT subject FROM comment WHERE comment.created >= '" . $current . "' AND comment.created <= '" . $timeend . "'")
		or die(mysql_error());
	$current_comments += mysql_num_rows($result);
	$csvdata .= $current_comments . ",";
	$chartdata .= $current_comments . ", ";
	
	$result = mysql_query("SELECT fcid FROM flag_content WHERE timestamp >= '" . $current . "' AND timestamp <= '" . $timeend . "'")
		or die(mysql_error());
	$current_likes += mysql_num_rows($result);
	$csvdata .= $current_likes . "<br />";
	$chartdata .= $current_likes . "],\r\n";
	
	$dayofmonth++;
}
?>
			<script type="text/javascript">
				google.setOnLoadCallback(drawTotalNewChart);
				function drawTotalNewChart() {
					var data = google.visualization.arrayToDataTable([
						<?php print $chartdata; ?>
					]);
					
					var chart = new google.visualization.LineChart(document.getElementById('total_new_chart'));
					chart.draw(data);
				}
		    </script>
	    	<blockquote>
			    <?php print $csvdata; ?>
		    </blockquote>
	    	<div id="total_new_chart" style="width: 900px; height: 500px;"></div>
	    </article>
	    <article>
			<h2 style="margin-bottom: -30px;">Content Thumbs Up by Date</h2>
<?php
$dayofmonth = 1;
$csvdata = "Date,Thumbs Up<br />";
$chartdata = "['Date', 'Thumbs Up'],\r\n";
for ($current = $start_date; $current < $end_date; $current += 86400) {
	$csvdata .= date('M', $current) . "-" . date('d', $current) . ",";
	$chartdata .= "['" . date('M', $current) . "-" . date('d', $current) . "', ";
	
	$timeend=$current+86400;
	$result = mysql_query("SELECT fcid FROM flag_content WHERE timestamp >= '" . $current . "' AND timestamp <= '" . $timeend . "'")
		or die(mysql_error());
	$csvdata .= mysql_num_rows($result) . "<br />";
	$chartdata .= mysql_num_rows($result) . "],\r\n";
	
	$dayofmonth++;
}
?>
			<script type="text/javascript">
				google.setOnLoadCallback(drawLikeChart);
				function drawLikeChart() {
					var data = google.visualization.arrayToDataTable([
						<?php print $chartdata; ?>
					]);
					
					var chart = new google.visualization.AreaChart(document.getElementById('likes_stats_chart'));
					chart.draw(data);
				}
		    </script>
	    	<blockquote>
			    <?php print $csvdata; ?>
		    </blockquote>
	    	<div id="likes_stats_chart" style="width: 900px; height: 500px;"></div>
	    </article>
		<footer>Data compiled by Theory Communication &amp; Design.</footer>
		</form>
	</body>
</html>