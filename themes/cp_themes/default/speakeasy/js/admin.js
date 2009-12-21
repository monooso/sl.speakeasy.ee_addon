/**
 * Initialise the main navigation.
 */
function init_nav() {
  $('#sjl #masthead ul a').click(function(e) {
    
    /* Handy reference to the link that fired the event. */
    $link = $(e.target);
    
    /* Remove the 'active' class from any list items in the navigation. */
    $link
      .parents('#masthead')
      .find('li')
      .removeClass('active');
      
    /* Fade the current content out. */
    $('#sjl .content-block:not(' + $link.attr('href') + ')')
			.fadeOut('fast', function() {
				$('#sjl ' + $link.attr('href')).fadeIn('fast');
			});
      
		/* Highlight the active link. */
    $link.parent('li').addClass('active');
    
    /* Cancel the default link action. */
    return false;
    
  });
} // init_nav


/**
 * Initialise the "message details" field.
 */
function init_message_details() {
	$('#sjl .message-details')
		.siblings('label')
		.find(':radio')
		.click(function() {
			if ($(this).val() == 'y') {
				$(this)
					.parent('label')
					.siblings('.message-details')
					.slideDown();
			} else {
				$(this)
					.parent('label')
					.siblings('.message-details')
					.slideUp();
			}
		});
		
	$('.message-details')
		.siblings('label:has(:radio:checked[value="n"])')
		.siblings('.message-details')
		.hide();
}


$(document).ready(function() {
  $('#sjl').addClass('enhanced');   /* Add a CSS hook. */
  init_nav();                       /* Initialise the navigation. */
	init_message_details();						/* Initialise the message details. */
});