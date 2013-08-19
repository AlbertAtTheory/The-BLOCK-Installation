<?php
/**
 * This is an example page template that has 100% width header and footer backgrounds.
 * Additionally you will notice most of the elements have new wrapper divs, so in effect
 * you can style things like the main menu, breadcrumbs, secondary content region etc
 * with 100% width backgrounds. This template is compatible with AT's normal page layout
 * settings (just the backgrounds will be 100%).
 *
 * To use copy this to your subtheme and rename it page.tpl.php
 */
	$userid = explode("/", current_path());
?>
<div id="page-wrapper" class="profile"><div id="page">

  <?php if($page['leaderboard']): ?>
    <div id="leaderboard-wrapper"><div class="container clearfix">
      <?php print render($page['leaderboard']); ?>
    </div></div>
  <?php endif; ?>

  <div id="header-wrapper"><div class="container clearfix">
    <header class="clearfix">

      <?php if ($linked_site_logo): ?>
        <div id="logo"><?php print $linked_site_logo; ?></div>
      <?php endif; ?>

      <?php print render($page['header']); ?>
    </header>
  </div></div>

  <?php if ($page['menu_bar'] || $primary_navigation || $secondary_navigation): ?>
    <div id="nav-wrapper"><div class="container clearfix">
      <?php print render($page['menu_bar']); ?>
      <?php if ($primary_navigation): print $primary_navigation; endif; ?>
    </div></div>
  <?php endif; ?>
<?php if ($messages || $page['help']): ?>
	<div id="messages-help-wrapper"><div class="container clearfix">
		<?php print render($page['help']); ?>
		<?php print $messages; ?>
	</div></div>
<?php endif; ?>
  <?php if ($breadcrumb): ?>
    <div id="breadcrumb-wrapper"><div class="container clearfix">
      <section id="breadcrumb" class="clearfix">
        <?php print $breadcrumb; ?>
      </section>
    </div></div>
  <?php endif; ?>

  <?php if ($page['secondary_content']): ?>
    <div id="secondary-content-wrapper"><div class="container clearfix">
      <?php print render($page['secondary_content']); ?>
    </div></div>
  <?php endif; ?>

  <div id="content-wrapper"><div class="container">
    <div id="columns"><div class="columns-inner clearfix">
      <div id="content-column"><div class="content-inner">

        <?php print render($page['highlighted']); ?>

        <?php $tag = $title ? 'section' : 'div'; ?>
        <<?php print $tag; ?> id="main-content">

		<div id="content">
			<?php
				global $user;
				if (arg(0) == 'user' && $user->uid == arg(1)){
			?>
			<div class="block-widget" style="position: absolute; top: 10px; left: 570px; z-index: 2;"><a href="/user/<?php print arg(1); ?>/edit" title="Edit My Profile"><img src="/sites/all/themes/theblock/images/icon-pencil.png" style="margin-top: 4px;" /></a></div>
			<?php
				}
			?>
			<?php print render($page['content']); ?>
			<?php print render($page['profile_fields']); ?>
		</div>
          
		<?php if ($title || $primary_local_tasks || $secondary_local_tasks || $action_links): ?>
            <header id="profile_header">
              <?php print render($title_prefix); ?>
              <?php if ($title): ?>
                <h1 id="page-title"><?php print $title; ?></h1>
              <?php endif; ?>
              <?php print render($title_suffix); ?>
              <?php
				global $user;
				if ($user->uid && $user->uid != arg(1)){
			?>
              <div id="profile-widgets">
              	<div id="block-follow-widget" class="block-widget"><a href="/relationship/<?php print $userid[1] ?>/request?destination=<?php print current_path(); ?>" title="Add this user to your followers">Follow</a></div>
              	<div class="block-widget"><a href="/messages/new/<?php print $userid[1] ?>" title="Send this user a message">Message</a></div>
              	<div id="block-email-widget" class="block-widget"><a href="/forward?path=<?php print current_path(); ?>" title="E-mail a link to this users profile">Share via Email</a></div>
              	<div id="profile-share-buttons">
              		<!--<div class="fb-like" data-send="false" data-layout="button_count" data-width="40" data-show-faces="false"></div>//-->
              	</div>
              </div>
              <?php } ?>
            </header>
          <?php endif; ?>

          <?php print $feed_icons; ?>

        </<?php print $tag; ?>>

        <?php print render($page['content_aside']); ?>

      </div></div>

      <?php print render($page['sidebar_first']); ?>
      <?php print render($page['sidebar_second']); ?>

    </div></div>
  </div></div>

  <?php if ($page['tertiary_content']): ?>
    <div id="tertiary-content-wrapper"><div class="container clearfix">
      <?php print render($page['tertiary_content']); ?>
    </div></div>
  <?php endif; ?>

  <?php if ($page['footer']): ?>
    <div id="footer-wrapper"><div class="container clearfix">
      <footer class="clearfix">
        <?php print render($page['footer']); ?>
      </footer>
   </div></div>
  <?php endif; ?>

</div></div>
