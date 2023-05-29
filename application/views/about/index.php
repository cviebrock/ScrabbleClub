<div class="page-header">
	<h1>About the Club</h1> </div> <div class="row">
	<div class="span7">
		<h2>Location</h2>
		<p>
			Winnipeg Scrabble&reg; Club #498 is a member of the
			<?php echo HTML::link('https://www.scrabbleplayers.org/', 'North American Scrabble® Players Association', array('target'=>'_blank')); ?> (NASPA).
		</p>
		<p>
			The club meets every Thursday evening 6:30 &ndash; 10:00 p.m. at the
			Canadian Mennonite University - South Campus, Room C09,
			600 Shaftsbury Boulevard, Winnipeg, MB
			(on the south-west corner of Grant and Shaftsbury).
		</p>
		<p>
		  Parking is available in the "N" lot of the campus.  The university is also served by
			<?php echo HTML::link('https://winnipegtransit.com/en/routes/find?location=600+shaftsbury&location_id=&commit=Submit', 'Winnipeg Transit', 
array('target'=>'_blank')); ?>.
		</p>
		<h2>Club Rules</h2>
		<ul>
			<li>There are no membership requirements. Fee is $5.00 per night, or blocks of 5 or 10 nights for $25 or $50 respectively.</li>
			<li>North American Scrabble&reg; Players Association rules are followed, but are relaxed for new players.</li>
			<li>Clocks are used with each player getting 25 minutes for their turns in the game.</li>
			<li>A computer word judge (e.g. <?php echo HTML::link('https://zyzzyva.net/','Zyzzyva',array('target'=>'_blank')); ?>) or dictionaries are consulted when a play is 
challenged.</li>
			<li>New players have the option of being provided with a list of allowable 2- and 3-letter words, until they are comfortable playing without it.</li>
			<li>Usually 4 games are played per night, with the last 3 games sometimes being a round-robin format.</li>
			<li>Tournament quality equipment is provided, plus some players bring their own.</li>
			<li>A fragrance-free environment is encouraged.</li>
		</ul>
<div class="alert alert-danger" style="margin: 2rem 0; color: inherit;">
<h2>COVID-19 Rules</h2>
<ul>
<li>Players must wear a mask at all times in indoor public places.  The mask must be worn in a manner that covers your mouth, nose and chin without gapping.</li>
<li>Players must be double-vaccinated.  Proof of vaccination will be checked (via MB's QR-code).</li>
<li>Players are encouraged to use hand sanitizer, provided throughout CMU and in the room during play.  Sanitizing before games and when using the word judge computer is strongly recommended.</li>
</ul>
<p>
If you are not feeling well, or otherwise suspect you may have COVID or another illness <strong>DO NOT ATTEND</strong>.
</p>
<p>
Rules and club play are subject to change due to MB Health directives, CMU policies, and/or club decisions.
</p>
</div>
		<p>
			Officially sanctioned (NASPA rated) tournaments are held once a year.
			NASPA requires a membership fee of $30 US upon entering a sanctioned tournament.
		</p>
		<h2>Contact</h2>
		<p>
			For more information contact any of the following:
		</p>
		<ul>
			<li>Linda Pearn at 204-253-8978 or <?php echo HTML::mailto('lpearn@mts.net','via email'); ?></li>
			<li>Julie Kading at 204-257-4742 or <?php echo HTML::mailto('jkading@shaw.ca','via email'); ?></li>
			<li>Colin Viebrock at <?php echo HTML::mailto('colin@winnipeg.scrabbleclub.org','colin@winnipeg.scrabbleclub.org'); ?></li>
		</ul> <?php if( $url = Config::get('facebook.url') ): ?>
		<h2>Like Us on Facebook</h2>
		<p>
			Visit our Facebook page at <?php echo HTML::link($url, deprotofy($url), array('target'=>'_blank')); ?>
			and be sure to like us!
		</p>
		<div class="fb-like" data-href="<?php echo $url; ?>"
		 data-send="true" data-width="450" data-show-faces="true"></div> <?php endif; ?>
	</div>
	<div class="span5">
		<iframe 
src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2572.2273989219248!2d-97.23167515211485!3d49.8569729301699!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x52ea74b64be04721%3A0x7bbf4524b6bee8d6!2s600+Shaftesbury+Blvd%2C+Canadian+Mennonite+University%2C+Winnipeg%2C+MB+R3P!5e0!3m2!1sen!2sca!4v1417325488588" 
width="380" height="380" frameborder="0" style="border:0"></iframe>
		<p>
			<img src="/img/cmu.jpg" alt="Canadian Mennonite University Campus Map" width="380" height="691" />
		</p>
	</div>

</div>
