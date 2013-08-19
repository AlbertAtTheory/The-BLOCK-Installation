<?php
$usercanedit = FALSE;
$isusersaccount = FALSE;

// Show block only if user has edit privileges for the node page
// first check whether it is a node page
if(arg(0) == 'node' && is_numeric(arg(1))){
    //load $node object
    $node = node_load(arg(1));
    //check for node update access
    if (node_access("update", $node)){
        $usercanedit = TRUE;
    }
}

global $user;
if ($user->uid && arg(0) == 'user' && $user->uid == arg(1)) {
	$isusersaccount = TRUE;
}

function selfURL() {
	if(!isset($_SERVER['REQUEST_URI'])) {
		$serverrequri = $_SERVER['PHP_SELF'];
	} else {
		$serverrequri =    $_SERVER['REQUEST_URI'];
	}
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
	return $protocol."://".$_SERVER['SERVER_NAME'].$port.$serverrequri;   
}

function strleft($s1, $s2) {
	return substr($s1, 0, strpos($s1, $s2));
}
?>
<div class="<?php print $classes; ?>">
	<?php if ($content) { print $content; } ?>
	<?php if ($user->uid): ?>

		<div id="block-generate-qr">
			<img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chld=L|1&chl=<?php echo selfURL(); ?>" width="100" height="100" alt="" class="qrcode" />Generate a<br />QR Code
		</div>
		<div id="block-qr-panel">
			<h1>Here's Your QR Code!</h1>
			<img src="/sites/all/themes/theblock/logo.png" alt="" />
			<p>Download, print and affix this QR Code to your ride&mdash;and spread the word about The BLOCK&mdash;the biggest, baddest, octane-infused automotive community on the web!</p>
			<p style="margin-top: -7px; font-size: 12px;">Printed labels optimized for Avery label #22806.</p>
			<a href="/qr/print/<?php print arg(0); ?>/<?php print arg(1); ?>">Print This Code</a>
			<a href="/qr/download/<?php print arg(0); ?>/<?php print arg(1); ?>">Download</a>
		</div>

	<?php endif; ?>
	<?php /* FRONT PAGE NAVIGATION */ if ($is_front): ?>

		<div class="floating-left-navigation homenav">
			<a href="javascript:animateScrollTo('#page');" class="news header">News</a>
			<a href="javascript:animateScrollTo('#block-views-homepage-slider-block');" class="stories active"  id="nav_block-views-homepage-slider-block">Featured<br />Stories</a>
			<a href="javascript:animateScrollTo('#main-content');" class="headlines" id="nav_main-content">Latest<br />Headlines</a>
			<?php if (!$logged_in): ?><a href="/user/register/" class="getstarted">Get Started</a><?php endif; ?>
		</div>
		<script language="javascript">
		<!--
			highlightMe('block-views-homepage-slider-block');
			highlightMe('main-content');
		//-->
		</script>

	<?php endif ?>
	<?php if ($user->uid): { ?>

		<div class="floating-left-navigation myprofilenav">
			<a href="/user" class="header"><?php
				$theuser = user_load($user->uid);
				if (isset($theuser->picture)) {
					print theme('image_style', array(
						'style_name' => '20x20',
						'path' => $user->picture->uri,
						'attributes' => array(
							'class' => 'avatar'
						)));
				} else {
			?><img src="/sites/all/themes/theblock/images/icon-helmet.png" class="avatar" style="width: 20px; height: 20px;" /><?php } ?>My Profile</a>
			<a href="/node/add/statuses">Update Status</a>
			<a href="/user/<?php print $user->uid; ?>/edit">Edit Profile</a>
			<a href="/node/add/vehicles">Add Vehicle</a>
			<a href="/node/add/album">New Photo Album</a>
			<a href="/user/logout">Logout</a>
		</div>

	<?php } ?>
	<?php if(!$isusersaccount && $usercanedit) { $node = node_load(arg(1)); ?>

		<div class="floating-left-navigation">
			<a class="header">Tools</a>
			<a href="/node/<?php print $node->nid ?>/edit">Edit Content</a>
			<a href="/node/<?php print $node->nid ?>/delete">Delete</a>
		</div>

	<?php } ?>
	<?php endif; 
		// FORUM NAVIGATION
		if(arg(0) == 'forum'):
	?>

	<div class="floating-left-navigation" id="forum-navigation">
		<a href="/forum" class="header forums">Forums</a>
		<?php if ($logged_in) { ?><a href="/forum/new" class="<?php if (arg(1) == 'new') print "active"; ?>">New Topics</a><?php } ?>
		<a href="/forum/unanswered" class="<?php if (arg(1) == 'unanswered') print "active"; ?>">Unanswered Topics</a>
		<a href="/forum/active" class="<?php if (arg(1) == 'active') print "active"; ?>">Active Topics</a>
		<?php if ($logged_in) { ?><a href="/forum/markasread" class="<?php if (arg(1) == 'markasread') print "active"; ?>">Mark All Read</a><?php } ?>
	</div>

	<?php endif;
		// RACING NAVIGATION
		if (arg(1) == 143):
	?>
	<div class="floating-left-navigation racingnav">
		<a href="javascript:animateScrollTo('#main-content');" class="header">Racing</a>
		<a href="javascript:animateScrollTo('.header-nascar');" id="nav_nascar">NASCAR&reg;</a>
		<a href="javascript:animateScrollTo('.header-indy');" id="nav_indy">IndyCar</a>
		<a href="javascript:animateScrollTo('.header-lemans');" id="nav_lemans">ALMS</a>
		<a href="javascript:animateScrollTo('.header-wtcc');" id="nav_wtcc">WTCC</a>
		<a href="javascript:animateScrollTo('.header-grandam');" id="nav_grandam">Grand-Am</a>
		<a href="javascript:animateScrollTo('.header-formulad');" id="nav_formulad">Formula D</a>
	</div>
	<script language="javascript">
	<!--
		highlightMe('main-content','floating_racing');
		highlightMe('nascar','floating_racing');
		highlightMe('indy','floating_racing');
		highlightMe('lemans','floating_racing');
		highlightMe('wtcc','floating_racing');
		highlightMe('grandam','floating_racing');
		highlightMe('formulad','floating_racing');
	//-->
	</script>
	<?php
		endif;
		
		// DRIVETRAIN NAVIGATION
		
		if (arg(0) == 'drivetrains'):
	?>
	<div class="floating-left-navigation drivetrainsnav">
		<a href="/drivetrains" class="header">Drivetrains</a>
		<a href="/drivetrains" class="<?php if (!arg(1)) print "active"; ?>">All</a>
		<a href="/drivetrains/165" class="<?php if (arg(1) == '165') print "active"; ?>">Small Block</a>
		<a href="/drivetrains/168" class="<?php if (arg(1) == '168') print "active"; ?>">Big Block</a>
		<a href="/drivetrains/166" class="<?php if (arg(1) == '166') print "active"; ?>">LS Series</a>
		<a href="/drivetrains/167" class="<?php if (arg(1) == '167') print "active"; ?>">LSX Series</a>
		<a href="/drivetrains/169" class="<?php if (arg(1) == '169') print "active"; ?>">Circle Track</a>
	</div>
	<?php
		endif;
		
		// PARTS NAVIGATION
		
		if (arg(0) == 'parts'):
	?>
	<div class="floating-left-navigation partsnav">
		<a href="/parts" class="header">Parts</a>
		<a href="/parts" class="<?php if (!arg(1)) print "active"; ?>">All</a>
		<a href="/parts/370" class="<?php if (arg(1) == '370') print "active"; ?>">Drivetrain</a>
		<a href="/parts/371" class="<?php if (arg(1) == '371') print "active"; ?>">Electronics</a>
	</div>
	<div class="floating-left-navigation" style="margin-top: 20px;">
		<a href="/sites/default/files/GMPP_2013.pdf" onClick="pageTracker._trackPageview('/sites/default/files/GMPP_2013.pdf');"><img src="/sites/all/themes/theblock/images/catalog.png" /></a>
	</div>
	<?php
		endif;
		
		// EVENTS NAVIGATION
		
		if (arg(0) == 'events'):
	?>
	<div class="floating-left-navigation eventsnav">
		<a href="/events" class="header">Events</a>
		<a href="/events" class="<?php if (!arg(1)) print "active"; ?>">All</a>
		<a href="/events/northeast" class="<?php if (arg(1) == 'northeast') print "active"; ?>">Northeast</a>
		<a href="/events/south" class="<?php if (arg(1) == 'south') print "active"; ?>">South</a>
		<a href="/events/midwest" class="<?php if (arg(1) == 'midwest') print "active"; ?>">Midwest</a>
		<a href="/events/west" class="<?php if (arg(1) == 'west') print "active"; ?>">West</a>
	</div>
	<?php
		endif;
		
		// DOWNLOADS NAVIGATION
		
		if (arg(0) == 'downloads'):
	?>
	<div class="floating-left-navigation downloadsnav">
		<a href="/downloads" class="header">Downloads</a>
		<a href="/downloads" class="<?php if (!arg(1)) print "active"; ?>">All</a>
		<a href="/downloads/148" class="<?php if (arg(1) == '148') print "active"; ?>">Corvette</a>
		<a href="/downloads/125+147" class="<?php if (arg(1) == '125 147') print "active"; ?>">Camaro</a>
		<a href="/downloads/2" class="<?php if (arg(1) == '2') print "active"; ?>">Sonic</a>
		<a href="/downloads/tag/363" class="<?php if (arg(2) == '363') print "active"; ?>">Racing</a>
		<a href="/downloads/tag/362" class="<?php if (arg(2) == '362') print "active"; ?>">Vintage</a>
		<a href="/downloads/tag/361" class="<?php if (arg(2) == '361') print "active"; ?>">Parts</a>
	</div>
	<?php
		endif;
		
		// USER PROFILE NAVIGATION
		
		if (arg(0) == 'user' && arg(1) != 'login' && arg(1) != 'password' && arg(1) != 'register'):
	?>
	<div class="floating-left-navigation profilenav" id="user-profile-navigation">
		<a href="javascript:animateScrollTo('#content');" class="driver header"><?php print drupal_get_title(); ?></a>
		<a href="javascript:animateScrollTo('#block-views-stats-accolades-block');" class="stats"  id="nav_block-views-stats-accolades-block">Stats/<br />Accolades</a>
		<a href="javascript:animateScrollTo('#block-views-my-vehicles-block');" class="vehicles" id="nav_block-views-my-vehicles-block">Vehicles</a>
		<a href="javascript:animateScrollTo('#block-views-my-engines-block');" class="engines" id="nav_block-views-my-engines-block">Drivetrains</a>
		<a href="javascript:animateScrollTo('#block-views-my-parts-block');" class="mods" id="nav_block-views-my-parts-block">Performance Modifications</a>
		<a href="javascript:animateScrollTo('#block-views-photos-videos-block');" class="media" id="nav_block-views-photos-videos-block">Photo Albums</a>
		<a href="javascript:animateScrollTo('#block-views-user-comments-block');" class="connections" id="nav_block-views-user-comments-block">Connections</a>
	</div>
	<script language="javascript">
	<!--
		highlightMe('page-title');
		highlightMe('block-views-stats-accolades-block');
		highlightMe('block-views-my-vehicles-block');
		highlightMe('block-views-my-engines-block');
		highlightMe('block-views-my-parts-block');
		highlightMe('block-views-photos-videos-block');
		highlightMe('block-views-user-comments-block');
	//-->
	</script>
	<?php endif; ?>
</div>