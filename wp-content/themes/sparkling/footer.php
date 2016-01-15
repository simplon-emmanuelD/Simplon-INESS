<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package sparkling
 */
?>
		</div><!-- close .row -->
	</div><!-- close .container -->
</div><!-- close .site-content -->

	<div id="footer-area">
		<div class="container footer-inner">
			<div class="row">
				<?php get_sidebar( 'footer' ); ?>
			</div>
		</div>

		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="site-info container">
<p> Suivez nous ! </p>
<a href="https://www.facebook.com/SimplonINESS-1471760086465486/?fref=ts" target="_blank">
<img src="http://2.bp.blogspot.com/-HOSe6KT3GzQ/U5dBjhr6P7I/AAAAAAAAAA8/ppf6TdrCB0M/s1600/facebook.png"width="35" height="35" /></a>

<a href="https://twitter.com/Simplon_INESS" target="_blank">
<img src="http://ri2.sierraclub.org/sites/ri.sierraclub.org/files/styles/large/public/Twitter-Logo-Icon-transparent_0.png?itok=-y2zmK9Z"width="35" height="35" /></a>

<a href="https://github.com/Simplon-Narbonne" target="_blank">
<img src="https://files.slack.com/files-pri/T04735J22-F0J0S8W5A/logo_github.png"width="35" height="35" /></a>



<p style=float:right;> Crée par : Emmanuel Delos, Jerome Nanguet, Alexis Raffin</p>

				<div class="row">
					<?php if( of_get_option('footer_social') ) sparkling_social_icons(); ?>

					<nav role="navigation" class="col-md-6">
						<?php sparkling_footer_links(); ?>
					</nav>
					<div class="copyright col-md-6">
						<?php echo of_get_option( 'custom_footer_text', 'sparkling' ); ?>
						
					</div>
				</div>
			</div><!-- .site-info -->
			<div class="scroll-to-top"><i class="fa fa-angle-up"></i></div><!-- .scroll-to-top -->
		</footer><!-- #colophon -->
	</div>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>