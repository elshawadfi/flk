<div class="malcare9">
	<section id="malcare-1">
		<div class="malcare-logo-img text-center">
			<img height="70" width="240" src="<?php echo plugins_url("/../img/mc-top-logo.svg", __FILE__); ?>" alt="">
		</div>
		<div class="container-malcare" id="">
			<div class="row">
				<div class="col-xs-12 malcare-1-container">
					<h2 class="text-center heading">Signup to secure your website with MalCare's 360 degree protection</h2>
					<?php $this->showErrors(); ?>
					<div class="search-container text-center ">
						<form dummy=">" action="<?php echo $this->bvinfo->appUrl(); ?>/home/mc_signup" style="padding-top:10px; margin: 0px;" onsubmit="document.getElementById('get-started').disabled = true;"  method="post" name="signup">
							<input type='hidden' name='bvsrc' value='wpplugin' />
							<input type='hidden' name='origin' value='protect' />
							<?php echo $this->siteInfoTags(); ?>
							<input type="text" placeholder="Enter your email address to continue" id="email" name="email" class="search" required>
							<h5 class="check-box-text mt-2"><input type="checkbox" class="check-box" name="consent" value="1">
							<label>I agree to MalCare <a href="https://www.malcare.com/tos" target="_blank" rel="noopener noreferrer">Terms of Service</a> and <a href="https://www.malcare.com/privacy" target="_blank" rel="noopener noreferrer">Privacy Policy</a></label></h5>
							<button id="get-started" type="submit" class="e-mail-button"><span class="text-white">Submit</span></button>		
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section id="malcare-3">
		<div class="container-malcare" id="">
			<div class="heading-malcare text-center">
				<h5>MALCARE 360 DEGREE PROTECTION</h5>
				<h4>How can Malcare help protect your site?</h4>
			</div>
			<div class="row">
				<div class="col-xs-12 d-flex">
					<div class="col-xs-12 col-lg-6">
						<div>
							<img class = "main-image" src="<?php echo plugins_url("/../img/main-image.png", __FILE__); ?>"/>
						</div>
						<div class="text-center malcare-video">
							<a href="https://www.youtube.com/watch?v=rBuYh2dIadk" target="_blank">
								<img src="<?php echo plugins_url("/../img/play-video.png", __FILE__); ?>"/>
								Watch the Malcare Video
							</a>
						</div>
					</div>
					<div class="col-xs-12 col-lg-6 d-flex">
						<div id="accordion">
							<div>
								<input type="radio" name="accordion-group" id="option-1" checked />
								<div class="acc-card">
								<label for="option-1">
									<h5>MALCARE SCANNER</h5>
									<h4>WordPress Malware Scanner that will NEVER slow down your website.</h4>
								</label>
								<div class="article">
									<p>MalCare’s “Early Detection Technology” finds WordPress Malware that other popular plugins miss! 
										It uses 100+ signals to accurately detect and pinpoint even “Unknown” malware. You can now scan your website 
										for malware automatically, with ZERO overload on your server!</p>
								</div>
								</div>
							</div>
							<div>
								<input type="radio" name="accordion-group" id="option-2" />		
								<div class="acc-card">
								<label for="option-2">
									<h5>MALCARE FIREWALL</h5>
									<h4>Get 100% Protection from Hackers with our Advanced WordPress Firewall </h4>
								</label>
								<div class="article">
									<p>Automatically block malicious traffic with MalCare’s intelligent visitor pattern detection. 
										With CAPTCHA-based Login Protection, Timely alerts for suspicious logins and Security Features 
										recommended by WordPress - you can say Goodbye to Hackers!</p>
								</div>		
								</div>
							</div>
							<div>
								<input type="radio" name="accordion-group" id="option-3" />	
								<div class="acc-card">
								<label for="option-3">
									<h5>MALCARE CLEANER</h5>
									<h4>Instant Malware Removal that takes less than 60 Seconds in just 1-Click!</h4>
								</label>
								<div class="article">
									<p>No more waiting for hours or days to clean your hacked website. With MalCare’s fully automated 
										malware removal, you malware will be gone in a jiffy! Our powerful cleaner removes even complex &amp; 
										unknown malware in a matter of seconds. Leave the heavy lifting to us while you sit back and 
										relax - your site is in safe hands!</p>	
								</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section id="malcare-9">
		<div class="carousel text-center">
			<div class="left-fig"></div>
			<div class="slide-div text-center">				
			<input type="radio" name="slides" id="radio-1" checked>
			<input type="radio" name="slides" id="radio-2">
			<input type="radio" name="slides" id="radio-3">
			<input type="radio" name="slides" id="radio-4">
			<ul class="slides text-center">
				<li class="slide text-center">
					<img class="user" src="https://mk0malcaredecgig0d6a.kinstacdn.com/wp-content/uploads/2019/09/Ivica-Delic-1.jpg"/><br/>
					<p>
						<h1>&ldquo;</h1>
						<h4>Incredibly simple but powerful plugin. I am amazed how smooth its all going, scanning is very fast and I am so happy that I found it&#128578;</h4>
						<h5>Ivica Delic</h5>
					</p>
				</li>
				<li class="slide text-center">
					<img class = "user" src="https://mk0malcaredecgig0d6a.kinstacdn.com/wp-content/uploads/2019/01/Miriam-Schwab-2.jpg"/><br/>

					<p>
						<h1>&ldquo;</h1>
						<h4>When you are backing up a site MalCare tells you if that site is infected & cleans it up for you. Another way for us web developers to save valuable time & resources! MalCare is magical.</h4>
						<h5>Miriam Schwab, Strattic</h5>
					</p>
				</li>
				<li class="slide text-center">
					<img class = "user" src="https://mk0malcaredecgig0d6a.kinstacdn.com/wp-content/uploads/2019/09/david-mccan-wordpress-cpt-1-1.jpg"/><br/>

					<p>
						<h1>&ldquo;</h1>
						<h4>I’m very pleased. This has reduced server load since the scans are run from their server. Setup took 5 minutes. The team has been very responsive.</h4>
						<h5>David McCan, WebTNG</h5>
					</p>
				</li>
				<li class="slide text-center">
					<img class = "user" src="https://mk0malcaredecgig0d6a.kinstacdn.com/wp-content/uploads/2019/09/Armand-Girard-1.jpg"/><br/>

					<p>
						<h1>&ldquo;</h1>
						<h4>It’s nice to know that my site is being monitored by MalCare. It’s one less thing I have to worry about thereby giving me more time to work on my business.</h4>
						<h5>Armand Girard, Central Florida Promo</h5>
					</p>
				</li>
			</ul>
			<div class="slidesNavigation text-center">
			<label for="radio-1" id="dotForRadio-1"></label>
			<label for="radio-2" id="dotForRadio-2"></label>
			<label for="radio-3" id="dotForRadio-3"></label>
			<label for="radio-4" id="dotForRadio-4"></label>
			</div>
		</div>
		</div>
	</section>

	<section id="malcare-4">
		<div class="container-malcare text-center" id="">
			<div class="row">
				<div class="col-lg-12">
					<div class="heading-malcare">
						<h5>TRUSTED BY BRANDS WORLDWIDE</h5>
						<h4>25,000 happy customers and counting</h4>
					</div>
					<div class="heading-malcare text-center brand d-flex ">
						<img src="<?php echo plugins_url("/../img/wpbuffs.png", __FILE__); ?>" style="height: 42px;"/>
						<img src="<?php echo plugins_url("/../img/cloudways.png", __FILE__); ?>" style="height: 42px;"	/>
						<img src="<?php echo plugins_url("/../img/gowp.png", __FILE__); ?>" style="height: 42px;"/>
						<img src="<?php echo plugins_url("/../img/sitecare.png", __FILE__); ?>" style="height: 42px;" />
						<img src="<?php echo plugins_url("/../img/astra.png", __FILE__); ?>" style="height: 42px;" />
					</div>
				</div>
			</div>
		</div>
	</section>
</div>