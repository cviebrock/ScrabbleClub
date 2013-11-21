<div class="page-header">
	<h1>About the Club</h1>
</div>

<div class="row">

	<div class="span6">

		<h2>Location</h2>

		<p>
			Winnipeg Scrabble&reg; Club #498 is a member of the
			<?php echo HTML::link('http://www.scrabbleplayers.org/', 'North American Scrabble® Players Association', array('target'=>'_blank')); ?>.
		</p>

		<p>
			The club meets every Thursday evening 6:30 &ndash; 10:00 p.m. at the
			Kenaston Village Recreation Centre, 516 Kenaston Boulevard, Winnipeg, MB
			(northeast corner of Grant and Kenaston, behind the strip mall).
		</p>


		<h2>Club Rules</h2>

		<ul>
			<li>There are no membership requirements. Fee is $3.00 per night.</li>
			<li>North American Scrabble&reg; Players Association rules are followed, but are relaxed for new players.</li>
			<li>Clocks are used with each player getting 25 minutes for their turns.</li>
			<li>A computer word judge (e.g. <?php echo HTML::link('http://zyzzyva.net/','Zyzzyva',array('target'=>'_blank')); ?>) or dictionaries are consulted when a play is challenged.</li>
			<li>New players have the option of being provided with a list of allowable 2- and 3-letter words, until they are comfortable playing without it.</li>
			<li>Usually 4 games are played per night, however sometimes you can squeeze in 5 games.</li>
			<li>Tournament quality equipment is provided, plus some players bring their own.</li>
		</ul>

		<p>
			Officially sanctioned (NASPA rated) tournaments are held at least twice a year.
			NASPA requires a membership fee of $30 US upon entering a sanctioned tournament.
		</p>


		<h2>Contact</h2>

		<p>
			For more information contact any of the following:
		</p>

		<ul>
			<li>Linda Pearn at 204-253-8978 or <?php echo HTML::mailto('lpearn@mts.net','via email'); ?></li>
			<li>Julie Kading at 204-257-4742 or <?php echo HTML::mailto('jkading@shaw.ca','via email'); ?></li>
			<li>Darlene McBride at 204-489-5418 or <?php echo HTML::mailto('darmc@shaw.ca','via email'); ?></li>
			<li>Harold Stone at 204-339-5460 or <?php echo HTML::mailto('h_stone@shaw.ca','via email'); ?></li>
			<li>Brian Williams at 204-489-0682 (evenings) or <?php echo HTML::mailto('thewall812@yahoo.com','via email'); ?></li>
		</ul>

<?php if( $url = Config::get('facebook.url') ): ?>
		<h2>Like Us on Facebook</h2>
		<p>
			Visit our Facebook page at <?php echo HTML::link($url, deprotofy($url), array('target'=>'_blank')); ?> and be sure to like us!
		</p>
		<div class="fb-like" data-href="<?php echo $url; ?>"
		 data-send="true" data-width="450" data-show-faces="true"></div>
<?php endif; ?>



	</div>


	<div class="span6">
		<iframe width="380" height="380" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.ca/maps?client=safari&amp;oe=UTF-8&amp;q=516+Kenaston+Boulevard&amp;ie=UTF8&amp;hq=&amp;hnear=516+Kenaston+Blvd,+Winnipeg,+Division+No.+11,+Manitoba+R3N+1Z1&amp;gl=ca&amp;t=m&amp;ll=49.86604,-97.202911&amp;spn=0.021023,0.03253&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe><br /><small><a href="https://maps.google.ca/maps?client=safari&amp;oe=UTF-8&amp;q=516+Kenaston+Boulevard&amp;ie=UTF8&amp;hq=&amp;hnear=516+Kenaston+Blvd,+Winnipeg,+Division+No.+11,+Manitoba+R3N+1Z1&amp;gl=ca&amp;t=m&amp;ll=49.86604,-97.202911&amp;spn=0.021023,0.03253&amp;z=14&amp;iwloc=A&amp;source=embed" style="text-align:left" target="_blank">View Larger Map</a></small>
	</div>


</div>
