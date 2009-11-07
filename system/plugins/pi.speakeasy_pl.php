<?php

/**
 * Extension / plugin enabling self-moderation of comments. Users that are not automatically exempt from
 * comment moderation receive an email containing a link which allows them to approve their own comment.
 *
 * @package   Speakeasy
 * @version   1.1.1
 * @author    Stephen Lewis (http://experienceinternet.co.uk/)
 * @copyright Copyright (c) 2009, Stephen Lewis
 * @license   http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons Attribution-Noncommerical-Share Alike 3.0 Unported
 * @link      http://experienceinternet.co.uk/resources/details/speakeasy/
 *
 * Speakeasy was originally developed for Matt Weinberg at Vector Media Group, for the Greentech Media website.
 * A big thanks to both companies for allowing this addon to be released to the great unwashed (that's you).
 *
 * Stephen Lewis, Experience Internet, Vector Media Group, and Greentech Media are not responsible for any mishaps,
 * catastrophes, disasters, spillages, data loss, or hair loss that may be caused by the installation or use of Speakeasy.
 * It's worked great for us, but your experience may differ.
 */


/**
 * Plugin information used by EE.
 *
 * @global  array   $plugin_info
 */
$plugin_info = array(
		'pi_name' 				=> 'Speakeasy',
		'pi_version' 			=> '1.1.0',
		'pi_author' 			=> 'Stephen Lewis',
		'pi_author_url' 	=> 'http://experienceinternet.co.uk/',
		'pi_description' 	=> 'Free speech for all (except spammers).',
		'pi_usage'				=> Speakeasy_pl::usage()
);


class Speakeasy_pl {

	/**
	* Data returned from the plugin.
	*
	* @access public
	* @var    string
	*/
	public $return_data = '';
	
	
	/**
	 * PHP4 constructor. PHP4 isn't actually supported in the extension,
	 * but ExpressionEngine still requires this function in the plugin.
	 *
	 * @access  public
	 * @see     __construct
	 */
	public function Speakeasy_pl()
	{
	  $this->__construct();
	}
	
	
	/**
	 * Constructor.
	 *
	 * @access  public
	 */
	public function __construct()
	{
		$this->return_data = $this->_approve_comment();
	}
	
	
	/**
	 * Parses the template tag using data in the supplied $results array.
	 *
	 * @access 	private
	 * @param 	array 	  $results 		An array containing name => value pairs to replace in the template tag data.
	 * @param 	string 	  $tagdata 		The tagdata to process. Enables pre-processing of the $TMPL->tagdata.
	 * @return 	string 	  The string to return from the method for output to the browser. Enables post-processing.
	 */
	private function _parse_tagdata($results = '', $tagdata = '')
	{
		global $TMPL, $FNS;
		
		if (( ! is_array($results)) OR ( ! $tagdata))
		{
			return $tagdata;
		}
		
		// Prep the tagdata conditionals.
		$tagdata = $FNS->prep_conditionals($tagdata, $results);
		
		// Loop through the single variables in the template.
		foreach ($TMPL->var_single AS $key => $val)
		{
			if (isset($results[$val]))
			{
				$tagdata = $TMPL->swap_var_single($val, $results[$val], $tagdata);
			}
		}
		
		return $tagdata;
	}
	
	
	/**
	 * Attempt to approve a comment.
	 *
	 * @access		private
	 * @return 		A string containing the parsed tagdata.
	 */
	private function _approve_comment()
	{
		global $TMPL, $DB, $LOC, $FNS, $PREFS;
		
		$tagdata = $TMPL->tagdata;
		
		/**
		 * Retrieve the comment activation ID from the URL.
		 * We originally passed the activation code as a query
		 * string, but that wasn't working because of some sneaky
		 * URL rewriting in the .htaccess file on the staging server.
		 *
		 * Now the extension generates a URL with the activation code
		 * as the last URL segment, enabling us to extract it as a
		 * substring.
		 */
		
		$segments = explode('/', rtrim($FNS->fetch_current_uri() . '/', '/'));
		$code = $segments[count($segments) - 1];	
		
		// Rudimentary check: the activation code should be 10 alphanumeric characters.
		if ( ! preg_match('/^[\w\d]{10}$/', $code))
		{
			return ($this->_parse_tagdata(array('comment_approved' => FALSE), $tagdata));
		}
		
		// Retrieve the comment information from the database.
		$result = $DB->query("SELECT `comment_id`, `comment_url` FROM `exp_speakeasy` WHERE `activation_code` = '{$code}'");
		if ($result->num_rows != 1)
		{
			return ($this->_parse_tagdata(array('comment_approved' => FALSE), $tagdata));
		}
		
		// Make a note of the comment URL and comment ID.
		$comment_url = $result->row['comment_url'];
		$comment_id = $result->row['comment_id'];
		
		/**
		 * We don't want to fail just because somebody attempts to approve a comment
		 * more than once, so we can't just perform the update, and check the number
		 * of affected rows. Instead, we check that the comment exists -- if it does
		 * that's good enough for us to proceed with the update.
		 */
		
		$result = $DB->query("SELECT `comment_id`, `entry_id`, `status` FROM `exp_comments` WHERE `comment_id` = '{$comment_id}'");
		if ($result->num_rows != 1)
		{
			return ($this->_parse_tagdata(array('comment_approved' => FALSE), $tagdata));
		}
		
		// Mark the comment as approved ([o]pen).
		if ($result->row['status'] != 'o')
		{
    	$sql[] = "UPDATE {$PREFS->ini('db_prefix')}_weblog_titles
    	  SET `comment_total` = `comment_total` + 1, `recent_comment_date` = {$LOC->now}
    	  WHERE `entry_id` = '{$result->row['entry_id']}'";
    	
    	$sql[] = $DB->update_string(
    	  'exp_comments',
    	  array('status' => 'o'),
    	  "comment_id = '{$comment_id}'"
    	  );
    	
    	foreach ($sql AS $query)
    	{
    	  $DB->query($query);
    	}
    }
		
		// Huzzah, success!
		return ($this->_parse_tagdata(array('comment_approved' => TRUE, 'comment_url' => $comment_url), $tagdata));
	}
	
	
	/**
	 * Displays usage instructions in the EE control panel.
	 */
	function usage()
	{
		ob_start(); 
	  ?>
		NOTE:
		The plugin expects an "activation_code" URL segment in order to function correctly.
		The Speakeasy extension automatically generates a valid URL for inclusion in the activation email.

		Example usage:
		{exp:speakeasy_pl}
			{if comment_approved}
			<p>Rock on! You can <a href="{comment_url}">see your comment here</a>.</p>
			{if:else}
			<p>Boo! Something went wrong.</p>
			{/if}		
		{/exp:speakeasy_pl}
		
	  <?php
	  	$buffer = ob_get_contents();
	  	ob_end_clean(); 
	  	return $buffer;
	}

}

?>