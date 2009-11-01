<div id="sjl">

	<div id="masthead">
		<h1><?php echo $vars['lang']->line('extension_title') ." <em>v{$vars['version']}</em>"; ?></h1>
		
		<div><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=9354405" title="<?php echo $vars['lang']->line('donate'); ?>"><?php echo $vars['lang']->line('donate'); ?></a></div>

		<ul>
			<li class="active"><a href="#settings" title="<?php echo $vars['lang']->line('settings_link_title'); ?>"><?php echo $vars['lang']->line('settings_link'); ?></a></li>
			<li><a href="#about" title="<?php echo $vars['lang']->line('about_link_title'); ?>"><?php echo $vars['lang']->line('about_link'); ?></a></li>
		</ul>
	</div><!-- #masthead -->
	
	<div class="content-block" id="settings">
		<?php echo $vars['form_open']; ?>
			<fieldset>
				<h2><?php echo $vars['lang']->line('activation_url_title'); ?></h2>
				
				<div class="info">
				  <?php echo $vars['lang']->line('activation_url_info'); ?>
				</div><!-- .info -->
				
				<table cellpadding="0" cellspacing="0">
					<tbody>
						<tr class="odd">
							<th>
							  <label for="activation_url"><?php
							    echo $vars['lang']->line('activation_url_label');
							    echo '<span>' .$vars['lang']->line('activation_url_hint'). '</span>';
							  ?></label></th>
							<td><input class="text" id="activation_url" name="activation_url" type="text" value="<?php echo $vars['settings']['activation_url']; ?>" /></td>
						</tr>
					</tbody>
				</table>
			</fieldset>
		
			<fieldset>
				<h2><?php echo $vars['lang']->line('activation_email_title'); ?></h2>
				<div class="info">
					<?php echo $vars['lang']->line('activation_email_info'); ?>
				</div><!-- .info -->
			
				<table cellpadding="0" cellspacing="0">
					<tbody>
						<tr class="odd">
						  <th>
						    <label for="email_subject"><?php echo $vars['lang']->line('activation_email_subject_label'); ?></label></th>
						  <td><input class="text" id="email_subject" name="email_subject" type="text" value="<?php echo $vars['settings']['email_subject']; ?>" /></td>
						</tr>
						
						<tr class="even">
						  <th>
						    <label for="email_body"><?php
						      echo $vars['lang']->line('activation_email_body_label');
						      echo '<span>' .$vars['lang']->line('activation_email_body_hint'). '</span>';
						    ?></label>
						  </th>
						  <td><textarea cols="20" id="email_body" name="email_body" rows="10"><?php echo $vars['settings']['email_body']; ?></textarea></td>
						</tr>
						
						<tr class="odd">
						  <th>
						    <label for="email_signature"><?php
						      echo $vars['lang']->line('activation_email_signature_label');
						      echo '<span>' .$vars['lang']->line('activation_email_signature_hint'). '</span>';
						    ?></label>
						  </th>
						  <td><textarea cols="20" id="email_signature" name="email_signature" rows="5"><?php echo $vars['settings']['email_signature']; ?></textarea></td>
						</tr>
					</tbody>
				</table>
			</fieldset>
			
			<?php include('settings-update.php'); ?>
		
			<fieldset class="submit">
				<input type="submit" value="<?php echo $vars['lang']->line('save_settings'); ?>" />
			</fieldset>
		</form>
	</div>
	
	<div class="content-block" id="about">
	  <div class="col">
  	  <h2>Credits</h2>
  	  <p>Speakeasy was written by the dashing Stephen Lewis, who rents out his 1337 ExpressionEngine skillz at <a href="http://experienceinternet.co.uk" title="Experience Internet, home of ExpressionEngine hamburger awesomeness">Experience Internet</a>. You may wish to check out some of <a href="http://experienceinternet.co.uk/" title="ExpressionEngine addons, written by Stephen Lewis of Experience Internet">his other EE addons</a>, whilst you&rsquo;re there.</p>
	  
  	  <p>Should you wish to contact Stephen for a chat, or because you wish to transfer large sums of money into his bank account, you can <a href="http://twitter.com/monooso/" title="Follow Stephen">find him on Twitter</a>, or <a href="http://experienceinternet.co.uk/contact/" title="Drop Stephen a line">via the Experience Internet website</a>.</p>
	  
  	  <p>Speakeasy was originally developed for Matt Weinberg at <a href="http://vectormediagroup.com/" title="Visit the Vector Media Group website">Vector Media Group</a>, for the <a href="http://greentechmedia.com/" title="Visit the Greentech Media website">Greentech Media</a> website. A big thanks to both companies for allowing this addon to be released to the great unwashed (that&rsquo;s you).</p>
  	</div>
	  
	  <div class="col">
  	  <h2>The legal bits</h2>
  	  <p>Speakeasy is released under a <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/" title="Find out more about the Speakeasy license terms">Creative Commons Attribution-Noncommerical-Share Alike 3.0 Unported</a> license. In practical terms, this means that you can copy, adapt, and distribute Speakeasy as much as you like, provided that you:</p>
	  
  	  <ul>
  	    <li>Credit the original author</li>
  	    <li>Don&rsquo;t use any part of Speakeasy in a commercial product</li>
  	    <li>License your code in a similar fashion, so others can continue to benefit from your work</li>
  	  </ul>
	  
  	  <p>Speakeasy is released as-is, without any warranty, express or implied. The author, Stephen Lewis, will endeavour to offer support to those of you gracious enough to use his addon, but this is because he&rsquo;s an all-round good egg. Should he fall under a bus, or just become excessively busy, support may be slow, and perhaps even surly.</p>
	  
  	  <p>Finally, Stephen Lewis, Experience Internet, Vector Media Group, and Greentech Media are not responsible for any mishaps, catastrophes, disasters, spillages, data loss, or hair loss that may be caused by the installation or use of Speakeasy. It&rsquo;s worked great for us, but your experience may differ.</p>
  	</div>
	</div><!-- #about -->

</div><!-- #sjl -->