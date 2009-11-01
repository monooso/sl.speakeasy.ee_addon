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
    $('#sjl .content-block:not(' + $link.attr('href') + ')').hide();
		$('#sjl ' + $link.attr('href')).show();
      
		/* Highlight the active link. */
    $link.parent('li').addClass('active');
    
    /* Cancel the default link action. */
    return false;
    
  });
} // init_nav


$(document).ready(function() {
  $('#sjl').addClass('enhanced');   /* Add a CSS hook. */
  init_nav();                       /* Initialise the navigation. */
});