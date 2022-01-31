<?php
/**
 * @package     ChrisDavenport
 * @subpackage  BigTownWalk
 *
 * @copyright   Copyright (C) 2022 Davenport Technology Services. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Date\Date;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

/** @var HtmlDocument $this */

$app  = Factory::getApplication();
$user = Factory::getUser();
$doc  = $app->getDocument();
$uri  = Uri::root(true);

// Output as HTML5.
$this->setHtml5(true);

// Get params from template.
$params = $app->getTemplate(true)->params;

// Get request variables.
$option   = (string) $app->input->getCmd('option');
$view     = (string) $app->input->getCmd('view');
$layout   = (string) $app->input->getCmd('layout');
$task     = (string) $app->input->getCmd('task');
$itemid   = (int) $app->input->getCmd('Itemid');
$sitename = $app->get('sitename');

// Remove unwanted old jQuery version loaded by Joomla.
$headData = $doc->getHeadData();
$scripts  = $headData['scripts'];
unset(
	$scripts[$uri . '/media/jui/js/jquery.js'],
	$scripts[$uri . '/media/jui/js/jquery.min.js'],
	$scripts[$uri . '/media/jui/js/jquery-noconflict.js'],
	$scripts[$uri . '/media/jui/js/jquery-migrate.js'],
	$scripts[$uri . '/media/jui/js/jquery-migrate.min.js'],
	$scripts[$uri . '/media/jui/js/chosen.jquery.js'],
	$scripts[$uri . '/media/jui/js/chosen.jquery.min.js'],
	$scripts[$uri . '/media/jui/js/jquery.autocomplete.js'],
	$scripts[$uri . '/media/jui/js/jquery.autocomplete.min.js'],
	$scripts[$uri . '/media/jui/js/bootstrap.js']
);
$headData['scripts'] = $scripts;
$doc->setHeadData($headData);

// Add template JavaScript.
HTMLHelper::_('script', 'template.js', ['version' => 'auto', 'relative' => true]);

// Add html5 shiv.
HTMLHelper::_('script', 'jui/html5.js', ['version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9']);

// Load custom JavaScript file.
HTMLHelper::_('script', 'bigtownwalk.js', ['version' => 'auto', 'relative' => true]);

// Get menus.
$menu = $app->getMenu();

// Are we on the home page?
$homePage = $menu->getActive() === $menu->getDefault();
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link href="templates/bigtownwalk/css/bootstrap.min.css" rel="stylesheet">
	<link href="templates/bigtownwalk/css/style.css" rel="stylesheet">
	<link href="templates/bigtownwalk/css/bigtownwalk.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>
	<jdoc:include type="head" />

</head>
<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($this->direction === 'rtl' ? ' rtl' : ''); ?>">
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div class="single-page-nav sticky-wrapper" id="tmNavbar">
				<ul class="nav navbar-nav">
					<li><a href="#section1">Homepage</a></li>
					<li><a href="#section2">About Us</a></li>
					<li><a href="#section3">Services</a></li>
					<li><a href="#section4">Contact</a></li>
					<li><a href="http://www.google.com/+templatemo" class="external" title="+templatemo page" target="_blank">External</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<div id="section1">
		<header id="header-area" class="intro-section">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 text-center">
						<div class="header-content">
							<h1>Shrewsbury Big Town Walk 2022</h1>
							<h4>In support of the Lingen Davies charity</h4>
						</div>
					</div>
				</div>
			</div>
		</header>
	</div>

	<div id="section2">
		<!-- Start Feature Area - Three blocks in a row. -->
		<section id="feature-area" class="about-section">
			<div class="container">
				<div class="row text-center inner">
					<div class="col-sm-4">
						<div class="feature-content">
							<img src="templates/bigtownwalk/images/1-1.jpg" alt="Image">
							<h2 class="feature-content-title green-text">Bootstrap v3.3.6</h2>
							<p class="feature-content-description">Morbi sagittis justo a velit placerat ullamcorper quis quis velit. Sed convallis at risus ullamcorper auctor. Praesent quis velit neque. Quisque semper porta nisi vitae suscipit. Duis lectus magna, ornare ac scelerisque.
							</p>
							<a href="#" class="feature-content-link green-btn">button green</a>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="feature-content">
							<img src="templates/bigtownwalk/images/1-2.jpg" alt="Image">
							<h2 class="feature-content-title blue-text">Responsive Design</h2>
							<p class="feature-content-description">Conquer Template is provided by templatemo for free of charge. You can use this template for any kind of website. No credit link is required. All images by <a href="http://unsplash.com" target="_parent">Unsplash</a>. Thank you for visiting our website. Please come again!</p>
							<a href="#" class="feature-content-link blue-btn">See Details</a>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="feature-content">
							<img src="templates/bigtownwalk/images/1-3.jpg" alt="Image">
							<h2 class="feature-content-title red-text">Parallax Layout</h2>
							<p class="feature-content-description">Morbi sagittis justo a velit placerat ullamcorper quis quis velit. Sed convallis at risus ullamcorper auctor. Praesent quis velit neque. Quisque semper porta nisi vitae suscipit. Duis lectus magna, ornare ac scelerisque.
							</p>
							<a href="#" class="feature-content-link red-btn">Button Red</a>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End Feature Area -->

		<!-- Start Blog Area - Two large blocks in a row. -->
		<section id="blog-area">
			<div class="container">
				<div class="row text-center inner equal">
					<div class="col-sm-6">
						<div class="blog-content">
							<img src="templates/bigtownwalk/images/2-1.jpg" alt="Image">
							<h2>Two Column Left Side</h2>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam tempor eros eget eros maximus, ut cursus sem euismod. Donec iaculis tristique odio at consectetur. Nullam dignissim varius suscipit. Sed in leo sit amet velit finibus pretium.<br><br>
								Vestibulum vel mauris at erat mattis accumsan et ac lorem. Cras non venenatis orci, sed tincidunt massa. Duis nisl lectus, auctor eu sodales at, dignissim eu orci. Sed vitae venenatis magna, in blandit metus.</p>
							<br>

						</div>
					</div>
					<div class="col-sm-6">
						<div class="blog-content">
							<img src="templates/bigtownwalk/images/2-2.jpg" alt="Image">
							<h2>Two Column Right Side</h2>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam tempor eros eget eros maximus, ut cursus sem euismod. Donec iaculis tristique odio at consectetur. Nullam dignissim varius suscipit. Sed in leo sit amet velit finibus pretium.<br><br>
								Vestibulum vel mauris at erat mattis accumsan et ac lorem. Cras non venenatis orci, sed tincidunt massa. Duis nisl lectus, auctor eu sodales at, dignissim eu orci. Sed vitae venenatis magna, in blandit metus.</p>
							<span><a href="#">read more</a></span><br>
							<p id="blofr"></p>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End Blog Area -->
	</div>

	<div id="section3">
		<!-- Start Services Area - Parallax background. -->
		<section id="services-area" class="services-section">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 text-center inner our-service">
						<div class="service">
							<h1>Our Services</h1>
							<p>Nunc diam leo, fringilla vulputate elit lobortis, consectetur vestibulum quam. Sed id <br>
								felis ligula. In euismod libero at magna dapibus, in rutrum velit lacinia. <br>
								Etiam a mi quis arcu varius condimentum.</p>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End Services Area -->

		<!-- Start Testimonial Area -->
		<section id="testimonial-area">
			<div class="container">
				<!-- Four blocks in a row. -->
				<div class="row text-center">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 col-xxs-12">
						<div class="testimonial-content">
							<img src="templates/bigtownwalk/images/4-1.jpg" alt="Image">
							<h2>Column One</h2>
							<p>Ut ac odio scelerisque ante ornare commodo. Sed faucibus dui libero, in tincidunt purus pretium quis. Fusce posuere egestas enim eu viverra.</p>
							<br>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 col-xxs-12">
						<div class="testimonial-content">
							<img src="templates/bigtownwalk/images/4-2.jpg" alt="Image">
							<h2>Column Two</h2>
							<p>Ut ac odio scelerisque ante ornare commodo. Sed faucibus dui libero, in tincidunt purus pretium quis.</p>
							<a href="#" class="content-link">read it</a>
							<br>
							<p id="redd"></p>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 col-xxs-12">
						<div class="testimonial-content">
							<img src="templates/bigtownwalk/images/4-3.jpg" alt="Image">
							<h2>Column Three</h2>
							<p>Ut ac odio scelerisque ante ornare commodo. Sed faucibus dui libero, in tincidunt purus pretium quis. Fusce posuere egestas enim eu viverra.</p>
							<br>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 col-xxs-12">
						<div class="testimonial-content">
							<img src="templates/bigtownwalk/images/4-4.jpg" alt="Image">
							<h2>Column Four</h2>
							<p>Ut ac odio scelerisque ante ornare commodo. Sed faucibus dui libero, in tincidunt purus pretium quis.</p>
							<a href="#" class="content-link">details</a>
							<br>
							<p id="dets"></p>
						</div>
					</div>
				</div>

				<!-- One block in a row. -->
				<div class="row">
					<div class="col-lg-12">
						<div class="tm-box">
							<img src="templates/bigtownwalk/images/4-5.jpg" alt="Image" class="img-responsive">
							<div class="tm-box-description">
								<h2>One Big Column</h2>
								<p class="tm-box-p">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam tempor eros eget eros maximus, ut cursus sem euismod. Donec iaculis tristique odio at consectetur. Nullam dignissim varius suscipit. Sed in leo sit amet velit finibus pretium. Vivamus dictum nisi ac fermentum interdum. Vestibulum vel mauris at erat mattis accumsan et ac lorem. Cras non venenatis orci, sed tincidunt massa. Duis nisl lectus, auctor eu sodales at, dignissim eu orci. Sed vitae venenatis magna, in blandit metus. Praesent volutpat cursus rhoncus. Aenean arcu diam, suscipit ac neque in, sollicitudin convallis orci.</p>
								<p class="tm-box-p">Fusce eu porta massa, sed tincidunt turpis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vivamus a urna tellus. Integer pharetra vitae nisi et lacinia. Morbi sagittis justo a velit placerat ullamcorper quis quis velit. Sed convallis at risus ullamcorper auctor. Praesent quis velit neque. Quisque semper porta nisi vitae suscipit. Duis lectus magna, ornare ac scelerisque quis, maximus eget nisi.</p>
								<a href="#" class="content-link">Read More</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End Testimonial Area -->
	</div>

	<div id="section4">
		<!-- Start Contact Area -->
		<section id="contact-area" class="contact-section">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 text-center inner">
						<div class="contact-content">
							<h1>contact form</h1>
							<div class="row">
								<div class="col-sm-12">
									<p>Nunc diam leo, fringilla vulputate elit lobortis, consectetur vestibulum quam. Sed id <br>
										felis ligula. In euismod libero at magna dapibus, in rutrum velit lacinia. <br>
										Etiam a mi quis arcu varius condimentum.</p>
								</div>
							</div>

						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<form action="#" method="post" class="contact-form">
							<div class="col-sm-6 contact-form-left">
								<div class="form-group">
									<input name="name" type="text" class="form-control" id="name" placeholder="Name">
									<input type="email" name="email" class="form-control" id="mail" placeholder="Email">
									<input name="subject" type="text" class="form-control" id="subject" placeholder="Subject">
								</div>
							</div>
							<div class="col-sm-6 contact-form-right">
								<div class="form-group">
									<textarea name="message" rows="6" class="form-control" id="comment" placeholder="Your message here..."></textarea>
									<button type="submit" class="btn btn-default">Send</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
		<!-- End Contact Area -->
	</div>

	<!-- Start Footer Area -->
	<footer id="footer-area">
		<div class="container">
			<div class="row text-center">
				<div class="col-sm-12">
					<div class="footer-content">
						<h1>Use it free!</h1>
						<p>“Conquer is free Bootstrap template from templatemo website.
							<br>No backlink is required to use this layout.”</p>
					</div>
				</div>
			</div>
		</div>
		<hr>
		<div class="container">
			<div class="row">
				<div class="col-sm-12 text-center">
					<p class="copy">Copyright © <?php echo (new Date)->format('Y'); ?> Big Town Walk</p>
				</div>
			</div>
		</div>
	</footer>
	<!-- End Footer Area -->

	<script src="templates/bigtownwalk/js/jquery-3.4.1.min.js"></script>
	<script src="templates/bigtownwalk/js/jquery.scrollUp.min.js"></script> <!-- https://github.com/markgoodyear/scrollup -->
	<script src="templates/bigtownwalk/js/jquery.singlePageNav.min.js"></script> <!-- https://github.com/ChrisWojcik/single-page-nav -->
	<script src="templates/bigtownwalk/js/parallax.js-1.3.1/parallax.js"></script> <!-- http://pixelcog.github.io/parallax.js/ -->
	<script src="templates/bigtownwalk/js/bootstrap.min.js"></script>
	<script src="media/jui/js/chosen.jquery.min.js"></script>
	<script src="media/jui/js/jquery.autocomplete.min.js"></script>
	<script src="media/system/js/validate.js"></script>

	<script>
		// HTML document is loaded. DOM is ready.
		$(function() {

			// Parallax
			$('.intro-section').parallax({
				imageSrc: 'templates/bigtownwalk/images/bg-1.jpg',
				speed: 0.2
			});
			$('.services-section').parallax({
				imageSrc: 'templates/bigtownwalk/images/bg-2.jpg',
				speed: 0.2
			});
			$('.contact-section').parallax({
				imageSrc: 'templates/bigtownwalk/images/bg-3.jpg',
				speed: 0.2
			});

			// jQuery Scroll Up / Back To Top Image
			$.scrollUp({
				scrollName: 'scrollUp',      // Element ID
				scrollDistance: 300,         // Distance from top/bottom before showing element (px)
				scrollFrom: 'top',           // 'top' or 'bottom'
				scrollSpeed: 1000,            // Speed back to top (ms)
				easingType: 'linear',        // Scroll to top easing (see http://easings.net/)
				animation: 'fade',           // Fade, slide, none
				animationSpeed: 300,         // Animation speed (ms)
				scrollText: '', // Text for element, can contain HTML
				scrollImg: true            // Set true to use image
			});

			// ScrollUp Placement
			$( window ).on( 'scroll', function() {

				// If the height of the document less the height of the document is the same as the
				// distance the window has scrolled from the top...
				if ( $( document ).height() - $( window ).height() === $( window ).scrollTop() ) {

					// Adjust the scrollUp image so that it's a few pixels above the footer
					$('#scrollUp').css( 'bottom', '80px' );

				} else {
					// Otherwise, leave set it to its default value.
					$('#scrollUp').css( 'bottom', '30px' );
				}
			});

			$('.single-page-nav').singlePageNav({
				offset: $('.single-page-nav').outerHeight(),
				speed: 1500,
				filter: ':not(.external)',
				updateHash: true
			});

			$('.navbar-toggle').click(function(){
				$('.single-page-nav').toggleClass('show');
			});

			$('.single-page-nav a').click(function(){
				$('.single-page-nav').removeClass('show');
			});

		});
	</script>

	<jdoc:include type="modules" name="debug" style="none" />
    <!--
	A derivative work based on the TemplateMo 476 Conquer Template:
		Copyright (C) 2020 Templatemo. All rights reserved. https://templatemo.com/tm-476-conquer
		Creative Commons Attribution 4.0 International License. http://creativecommons.org/licenses/by/4.0/

	Modifications Copyright (C) <?php echo date('Y'); ?> Davenport Technology Services. https://www.davenporttechnology.com/
		Creative Commons Attribution 4.0 International License. http://creativecommons.org/licenses/by/4.0/
    -->
</body>
</html>
