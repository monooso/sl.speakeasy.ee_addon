<div id="sjl">

	<div id="masthead" class="clearfix">
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
			
			<fieldset>
				<h2><?php echo $vars['lang']->line('comment_submission_message_title'); ?></h2>
				<div class="info">
					<p><?php echo $vars['lang']->line('comment_submission_message_info'); ?></p>
				</div><!-- .info -->
				
				<table cellpadding="0" cellspacing="0">
					<tbody>
						<tr class="odd">
							<th>
								<label>
									<?php echo $vars['lang']->line('display_message_label'); ?>
									<span><?php echo $vars['lang']->line('display_message_hint'); ?></span>
								</label>
							</th>
							<td class="nested">
								<label>
									<input id="display_message_y" name="display_message" type="radio" value="y"
									 	<?php if ($vars['settings']['display_message'] === 'y') echo 'checked="checked"'; ?>/>
									<?php echo $vars['lang']->line('yes'); ?>
								</label>
							
								<label>
									<input id="display_message_n" name="display_message" type="radio" value="n"
										<?php if ($vars['settings']['display_message'] !== 'y') echo 'checked="checked"'; ?> />
									<?php echo $vars['lang']->line('no'); ?>
								</label>
							
								<div class="message-details">
									<table cellpadding="0" cellspacing="0">
										<tbody>
											<tr>
												<th>
													<label for="message_title"><?php echo $vars['lang']->line('message_title_label'); ?>
														<span><?php echo $vars['lang']->line('message_title_hint'); ?></span></label></th>
												<td>
													<input id="message_title" name="message_title" type="text" value="<?php echo $vars['settings']['message_title']; ?>" />
												</td>
											</tr>
											
											<tr>
												<th>
													<label for="message_heading"><?php echo $vars['lang']->line('message_heading_label'); ?>
														<span><?php echo $vars['lang']->line('message_heading_hint'); ?></span></label></th>
												<td><input id="message_heading" name="message_heading" type="text" value="<?php echo $vars['settings']['message_heading']; ?>" /></td>
											</tr>
											
											<tr>
												<th><label for="message_text"><?php echo $vars['lang']->line('message_text_label'); ?>
													<span><?php echo $vars['lang']->line('message_text_hint'); ?></span>
												</label></th>
												<td>
													<textarea cols="10" id="message_text" name="message_text" rows="10"><?php echo $vars['settings']['message_text']; ?></textarea>
												</td>
											</tr>
											
											<tr>
												<th>
													<label for="message_link"><?php echo $vars['lang']->line('message_link_label'); ?>
														<span><?php echo $vars['lang']->line('message_link_hint'); ?></span></label></th>
												<td><input id="message_link" name="message_link" type="text" value="<?php echo $vars['settings']['message_link']; ?>" /></td>
											</tr>
											
											<tr>
												<th>
													<label for="message_delay"><?php echo $vars['lang']->line('message_delay_label'); ?>
														<span><?php echo $vars['lang']->line('message_delay_hint'); ?></span></label></th>
												<td>
													<select id="message_delay" name="message_delay">
														<?php
															for ($count = 1, $max = 10; $count <= $max; $count++)
															{
																echo "<option value='{$count}'";
																echo $vars['settings']['message_delay'] == $count ? " selected='selected'" : '';
																echo ">{$count}</option>";
															}
														?>
													</select>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</td>
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
	
	<div class="clearfix content-block" id="about">
	  <div class="col">
			<div class="figure inset">
				<img src="/themes/cp_themes/default/speakeasy/img/sl.jpg" width="75" height="75" alt="The author, looking dashing." />
			</div>
		
  	  <h2>About the Author</h2>
  	  <p>Speakeasy was written by the dashing Stephen Lewis, a man who the great <a href="http://twitter.com/kennymeyers/" title="Who is this idiot?">Kenny Meyers</a> once described as &ldquo;Meh&rdquo;.</p>
			
			<p>Stephen plies his trade at <a href="http://experienceinternet.co.uk" title="Experience Internet, home of ExpressionEngine hamburger awesomeness">Experience Internet</a>, building <a href="http://experienceinternet.co.uk/services/custom-expressionengine-development/" title="Custom ExpressionEngine website and addon development">custom EE sites and addons</a> for a phalanx of grateful clients. You may wish to check out some of <a href="http://experienceinternet.co.uk/resources/" title="ExpressionEngine addons, written by Stephen Lewis of Experience Internet">his other EE addons</a>, whilst you&rsquo;re there.</p>
	  
  	  <p>Should you wish to contact Stephen for a chat, or because you wish to transfer large sums of money into his bank account, you can <a href="http://twitter.com/monooso/" title="Follow Stephen">find him on Twitter</a>, or <a href="http://experienceinternet.co.uk/contact/" title="Drop Stephen a line">via the Experience Internet website</a>.</p>
	  
			<h2>Credit where it&rsquo;s due</h2>
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
	  
  	  <p>Speakeasy is released as-is, without any warranty, express or implied. The author (Stephen Lewis) will endeavour to offer support to those of you gracious enough to use his addon, because he&rsquo;s an all-round good egg. Should he fall under a bus, or just become excessively busy, support may be slow, and perhaps even surly.</p>
	  
  	  <p>Finally, Stephen Lewis, Experience Internet, Vector Media Group, and Greentech Media are not responsible for any mishaps, catastrophes, disasters, spillages, data loss, or hair loss that may be caused by the installation or use of Speakeasy. It&rsquo;s worked great for us, but your experience may differ.</p>
  	</div>
	</div><!-- #about -->

</div><!-- #sjl -->