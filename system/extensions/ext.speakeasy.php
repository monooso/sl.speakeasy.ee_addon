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

if ( ! defined('EXT'))
{
	exit('Invalid file request');
}


class Speakeasy {
  
  /**
   * Required properties.
   */
  
  /**
   * The extension name.
   *
   * @access  public
   * @var     string
   */
  public $name = 'Speakeasy';
  
  /**
   * The extension version.
   *
   * @access  public
   * @var     string
   */
  public $version = '1.1.1';
  
  /**
   * The extension description.
   *
   * @access  public
   * @var     string
   */
  public $description = 'Free speech for all (except spammers).';
  
  /**
   * The documentation URL.
   *
   * @access  public
   * @var     string
   */
  public $docs_url = 'http://experienceinternet.co.uk/resources/details/speakeasy';
  
  /**
   * Does this extension have custom settings?
   *
   * @access  public
   * @var     string
   */
  public $settings_exist = 'y';
  
  /**
   * The extension settings.
   *
   * @access  public
   * @var     array
   */
  public $settings = array();
	
	
	/**
	 * Constructor
	 *
	 * @access  public
	 * @param   array|string    $settings     Associative array or empty string.
	 */
	public function __construct($settings='')
	{
		$this->settings = $this->_load_settings();
	}
	
	
	/**
	 * Called from the insert_comment_end extension hook. Checks whether the
	 * comment requires self-moderation, and takes appropriate action.
	 *
	 * @access  public
	 * @param   array     $data         Data associated with the newly-submitted comment.
	 * @param   string    $moderate     Does this comment require moderation.
	 * @param   string    $comment_id   The comment ID.
	 */
	public function insert_comment_end($data, $moderate, $comment_id = '')
	{
		global $DB, $FNS, $PREFS, $SESS;
		
		// We only need to do our thing if the comment doesn't require moderation.
		if (strtolower($moderate) === 'n')
		{
			return;
		}
		
		// comment_id isn't available prior to EE 1.6.1.
		// We check if it exists, and if it doesn't we do things the old-fashioned way.
		if ($comment_id === '')
		{
			// Retrieve the last comment associated with the weblog in question.
			$query = $DB->query("
				SELECT comment_id
				FROM exp_comments
				WHERE entry_id = '" . $DB->escape_str($data['entry_id']) . "'
				ORDER BY comment_id DESC
				LIMIT 1");

			$comment_id = (isset($query->row['comment_id'])) ? $query->row['comment_id'] : 0;
		}
		
		// Retrieve the referring URL (i.e. the comments page URL).
		$data['comment_url'] = $PREFS->ini('site_url', TRUE) . $PREFS->ini('site_index') . '/';
		$data['comment_url'] .= ($SESS->tracker['0'] == 'index' ? '' : $SESS->tracker['0']);
		$data['comment_url'] = $FNS->remove_double_slashes($data['comment_url']);
		
		// For the sake of convenience, we add the comment ID to the main data array.
		$data['comment_id'] = $comment_id;
		
		// Create an entry in the exp_sl_speakeasy table, and send the activation email.
		$data['activation_code'] = $this->_add_comment_to_database($data);
		$this->_send_activation_email($data);
	}
	
	
	/**
	 * Activates the extension.
	 *
	 * @access  public
	 */
	public function activate_extension()
	{
		global $DB;

		$sql[] = $DB->insert_string('exp_extensions', array(
				'extension_id' => '',
				'class'        => get_class($this),
				'method'       => 'insert_comment_end',
				'hook'         => 'insert_comment_end',
				'settings'     => '',
				'priority'     => 10,
				'version'      => $this->version,
				'enabled'      => 'y'
				));
				
		$sql[] = "CREATE TABLE IF NOT EXISTS `exp_speakeasy` (
			`id` int(10) NOT NULL AUTO_INCREMENT,
			`weblog_id` int(10) NOT NULL,
			`entry_id` int(10) NOT NULL,
			`comment_id` int(10) NOT NULL,
			`comment_url` varchar(500) NOT NULL,
			`email` varchar(100) NOT NULL,
			`activation_code` varchar(10) NOT NULL,
			PRIMARY KEY (id)
			)";
			
		foreach ($sql AS $query)
		{
			$DB->query($query);
		}		
	}


	/**
	 * Updates the extension.
	 *
	 * @access  public
	 * @param   string    $current    The current version of the extension (or an empty string).
	 * @return  bool      FALSE if the extension is not installed, or is the current version.
	 */
	public function update_extension($current='')
	{
		global $DB;

		if ($current == '' OR $current == $this->version)
			return FALSE;

		if ($current < $this->version)
		{
			$DB->query("
			  UPDATE exp_extensions
				SET version = '" . $DB->escape_str($this->version) . "' 
			  WHERE class = '" . get_class($this) . "'"
			  );
		}
	}


	/**
	 * Disables the extension, and deletes settings from the database.
	 *
	 * @access  public
	 */
	public function disable_extension()
	{
		global $DB;
		
		$sql[] = "DELETE FROM exp_extensions WHERE class = '" . get_class($this) . "'";
		$sql[] = "DROP TABLE IF EXISTS exp_speakeasy";
		
		foreach ($sql AS $query)
		{
			$DB->query($query);
		}
	}
	
	
	/**
	 * Displays the settings form.
	 *
	 * @access  public
	 * @param   string    $current    Previously-saved settings.
	 */
	public function settings_form($current = '')
	{
	  global $DSP, $IN, $LANG, $PREFS;
	  
	  $DSP->crumbline = TRUE;
	  $DSP->title = $LANG->line('extension_title');
	  
	  // Breadcrumbs.
	  $DSP->crumb = $DSP->anchor(BASE.AMP. 'C=admin' .AMP. 'area=utilities', $LANG->line('crumb_utilities'));
	  $DSP->crumb .= $DSP->crumb_item($DSP->anchor(BASE.AMP. 'C=admin' .AMP. 'M=utilities' .AMP. 'P=extensions_manager', $LANG->line('crumb_extensions_manager')));
	  $DSP->crumb .= $DSP->crumb_item($LANG->line('extension_title') .' '. $this->version);
	  
	  // Disable extension button.
	  $DSP->right_crumb(
	    $LANG->line('disable_extension'),
	    BASE.AMP. 'C=admin' .AMP. 'M=utilities' .AMP. 'P=toggle_extension' .AMP. 'which=disable' .AMP. 'name=' .$IN->GBL('name')
	    );
	    
	  // Include the CSS and JavaScript.
	  $DSP->body = '';
	  $DSP->body .= '<link rel="stylesheet" type="text/css" media="screen,projection" href="' .$PREFS->ini('theme_folder_url'). 'cp_themes/' .$PREFS->ini('cp_theme'). '/speakeasy/css/admin.css" />';
	  $DSP->body .= '<script type="text/javascript" src="' .$PREFS->ini('theme_folder_url'). 'cp_themes/' .$PREFS->ini('cp_theme'). '/speakeasy/js/admin.js"></script>';
	  
	  // Create the variables required by the View.
	  $vars = array(
	    'lang'      => $LANG,
	    'form_open' => $DSP->form_open(
	      array(
	        'action'  => 'C=admin' .AMP. 'M=utilities' .AMP. 'P=save_extension_settings',
	        'name'    => 'extension_settings',
	        'id'      => 'extension_settings'
	        ),
	      array(
	        'name'    => strtolower(get_class($this))
	        )
	      ),
	    'settings'  => $this->settings,
	    'version'   => $this->version
	    );
	  
	  // Load the View.
    ob_start();
    include(PATH_EXT.'/speakeasy/views/settings.php');
    $DSP->body .= ob_get_clean();
	}
	
	
	/**
	 * Saves the extension settings.
	 *
	 * @access  public
	 */
	public function save_settings()
	{
	  global $DB, $IN;
	  
	  // Retrieve the extension settings.
	  $settings = array(
	    'activation_url'  => $IN->GBL('activation_url', 'POST'),
  	  'email_subject'   => $IN->GBL('email_subject', 'POST'),
  	  'email_body'      => $IN->GBL('email_body', 'POST'),
  	  'email_signature' => $IN->GBL('email_signature', 'POST'),
  	  'update_check'    => $IN->GBL('update_check', 'POST')
	    );
	  
	  // Save the extension settings.
	  $DB->query($DB->update_string(
	    'exp_extensions',
	    array('settings' => addslashes(serialize($settings))),
	    'class = "' .get_class($this). '"'
	    ));
	}
	
	
	/**
	 * --------------------------------------------
	 * PRIVATE METHODS
	 * --------------------------------------------
	 */
	 
	
	/**
	 * Loads the extension settings from the database. If no saved settings exist, the default settings are loaded.
	 *
	 * @access  private
	 * @return  array   Array of named extension settings.
	 */
	private function _load_settings()
	{
	  global $DB, $REGX, $PREFS;
	  
	  // Load the settings from the database.
	  $db_settings = $DB->query("SELECT `settings` FROM `exp_extensions` WHERE `class` = '" .get_class($this). "' LIMIT 1");
	  
	  if ($db_settings->num_rows === 1 && $db_settings->row['settings'] !== '')
	  {
	    $settings = $REGX->array_stripslashes(unserialize($db_settings->row['settings']));
	  }
	  else
	  {	    
	    // No saved settings. Load the defaults.
	    $settings = $this->_get_default_settings();
	  }
	  
	  // Return the settings.
	  return $settings;
	}
	
	
	/**
	 * Loads the default extension settings.
	 *
	 * @access  private
	 * @return  array   Array of named extension settings.
	 */
	private function _get_default_settings()
	{
	  global $LANG;
	  
	  $LANG->fetch_language_file(get_class($this));
	  
	  return array(
	    'activation_url'  => '',
	    'email_subject'   => $LANG->line('default_email_subject'),
	    'email_body'      => $LANG->line('default_email_body'),
	    'email_signature' => $LANG->line('default_email_signature'),
	    'update_check'    => 'y'
	    );
	}
	
	
	/**
	 * Generates a unique activation code.
	 *
	 * @access		private
	 * @return 		string 		The unique activation code.
	 */
	private function _generate_activation_code()
	{
		$code = md5(uniqid(rand(), FALSE));
		$code = strtoupper(base_convert($code, 10, 32));
		$code = substr($code, 0, 10);
		
		return $code;
	}
	
	
	/**
	 * Adds a new comment requiring moderation to the exp_speakeasy table.
	 *
	 * @access		private
	 * @param			array 		$data			An array of information about the newly-submitted comment.
	 * @return 		string 		The activation code.
	 */
	private function _add_comment_to_database($data)
	{
		global $DB;
		
		// Generate a unique activation code.
		$activation_code = $this->_generate_activation_code();
		
		$dbdata = array(
			'weblog_id'				=> $data['weblog_id'],
			'entry_id'				=> $data['entry_id'],
			'comment_id'			=> $data['comment_id'],
			'comment_url'			=> $data['comment_url'],
			'email'						=> $data['email'],
			'activation_code'	=> $activation_code
			);
		
		$sql = $DB->insert_string('exp_speakeasy', $dbdata);
		$DB->query($sql);
				
		return $activation_code;
	}
	
	
	/**
	 * Sends an email to the person responsible for submitting the comment. The email contains
	 * an "activation link", which the user can click to "approve" his comment.
	 *
	 * @access		private
	 * @param			array			$data			An array of information about the newly-submitted comment.
	 */
	private function _send_activation_email($data)
	{
		global $FNS, $PREFS, $REGX;
		
		// Check we have the required information.
		if ((! isset($data['name']))
		OR ( ! isset($data['email']))
		OR ( ! isset($data['activation_code']))
		OR ( ! isset($this->settings['activation_url'])))
		{
			return FALSE;
		}
		
		// Load the EEmail class, if required.
		if ( ! class_exists('EEmail'))
		{
			require PATH_CORE . 'core.email' . EXT;
		}
		
		// Build the activation URL.
		$activation_url = $FNS->create_url($this->settings['activation_url'], TRUE, FALSE) . $data['activation_code'] . '/';
		
		// Build the message body.		
		$message = $data['name'] . ",\n\n";
		$message .= $this->settings['email_body'] . "\n\n";
		$message .= $activation_url . "\n\n";
		$message .= $this->settings['email_signature'];
		$message = $REGX->entities_to_ascii($message);
		
		// Create a new email instance.
		$email = new EEmail;
		
		// Build and send the email.		
		$email->initialize();		
		$email->from($PREFS->ini('webmaster_email'), $PREFS->ini('webmaster_name'));
		$email->to($data['email']);
		$email->reply_to($PREFS->ini('webmaster_email'));
		$email->subject($this->settings['email_subject']);
		$email->message($message);
		$email->Send();
	}
	
}

?>