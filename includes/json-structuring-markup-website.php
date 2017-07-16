<?php 

/**
 *  JSON Structuring Markup Plugin
 *  Copyright (C) 2016, Sami Ahmed Siddiqui <sami@samisiddiqui.com>
 *
 *  This file is a part of JSON Structuring Markup.
 *
 *  JSON Structuring Markup is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.

 *  JSON Structuring Markup is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.

 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Schema.org Website Settings Page
 */
function json_structuring_markup_website_settings_page() {
  if ( !current_user_can( 'administrator' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  if (isset($_POST['submit'])){
    $schema =  array(
      'name'           =>    $_POST['name'],
      'alternateName'  =>    $_POST['alternate_name'],
      'url'            =>    $_POST['url'],
      'action'        =>    $_POST['potential_action'],
      'target'         =>    $_POST['target'],
      'activated'      =>    $_POST['activated'],
    );
    update_option('json_structuring_markup_website', serialize( $schema ) );
  }
  $schema_website_settings = unserialize( get_option('json_structuring_markup_website') );
  $schema_potential_checked = $schema_activated_checked = '';
  if( isset($schema_website_settings) && !empty($schema_website_settings) ){
    $name               =     $schema_website_settings['name'];
    $alternateName      =     $schema_website_settings['alternateName'];
    $url                =     $schema_website_settings['url'];
    $potential_active   =     $schema_website_settings['action'];
    $potential_target   =     $schema_website_settings['target'];
    $activated          =     esc_attr( $schema_website_settings['activated'] );
    if($activated == 'on'){
      $schema_activated_checked = 'checked';
    }
    if($potential_active == 'on'){
      $schema_potential_checked = 'checked';
    }
  }else{
    $name             =   get_bloginfo('name');
    $alternateName    =   get_bloginfo('name');
    $url              =   get_bloginfo('url');
    $potential_target =   get_bloginfo("url").'?q={search_term_string}';
  }
  echo '<div class="wrap">';
  echo '<h2>Website SCHEMA Markup SETTINGS</h2>';
  echo '<div>Provide the fields to make the validated schema for Website.</div>';
  echo '<form enctype="multipart/form-data" action="" method="POST" id="website-schema">';
  ?>
  <table class="schema-admin-table">
    <caption>Basic Setting</caption>
      <tr>
        <th>name :</th>
          <td>
            <input type="text" name="name" id="name" class="regular-text" value="<?php echo $name; ?>" required><small>Default : bloginfo("name")</small>
          </td>
      </tr>
      <tr>
        <th>alternateName :</th>
        <td>
          <input type="text" name="alternate_name" id="alternate_name" class="regular-text" value="<?php echo $alternateName; ?>" required><small>Default : bloginfo("name")</small>
        </td>
      </tr>
      <tr>
        <th>url :</th>
        <td>
          <input type="text" name="url" id="url" class="regular-text" value="<?php echo $url; ?>" required><small>Default : bloginfo("url")</small>
        </td>
      </tr>
  </table>
	
  <table class="schema-admin-table">
    <caption>Sitelink Search Box</caption>
    <tr>
      <th>potentialAction Active :</th>
        <td>
          <input type="checkbox" name="potential_action" id="potential_action" value="on" <?php echo $schema_potential_checked; ?>>Enabled
        </td>
    </tr>
    <tr>
      <th>target :</th>
      <td>
        <input type="text" name="target" id="target" class="regular-text" value="<?php echo  $potential_target; ?>"><small>Default : bloginfo("url") + /?q={search_term_string}</small>
      </td>
    </tr>
  </table>
  
  <table class="schema-admin-table">
    <tbody>
      <tr>
        <td><input type="checkbox" name="activated" value="on" <?php echo $schema_activated_checked; ?> /><strong>Activate SCHEMA.ORG Web   site</strong></td>
      </tr>
    </tbody>
  </table>

  <p>Setting Knowledge : <a href="https://schema.org/WebSite" title="Website Schema Markup" target="_blank">https://schema.org/WebSite</a></p> 
  <?php
  submit_button(); 
  echo '</form>';
  echo '</div>';
}

/**
 * Print JSON-LD Website schema markup
 */
function json_structuring_markup_display_website(){
  $schema_website_settings = unserialize( get_option('json_structuring_markup_website') );
  $activated = '';
  if( isset($schema_website_settings) ){
    $activated  = esc_attr( $schema_website_settings['activated'] );
  }
  if ( $activated == 'on' ) {
		$args = array(
				"@context"        => "http://schema.org",
				"@type"           => "Website",
        "name"            => esc_attr( $schema_website_settings['name'] ),
        "alternateName"   => esc_attr( $schema_website_settings['alternateName'] ),
        "url"             => esc_attr( $schema_website_settings['url'] ),
		);
    if ($schema_website_settings['action'] == 'on') {
      $args["potentialAction"] = array(
          "@type"       => "SearchAction",
          "target"      => $schema_website_settings['target'],
          "query-input" => "required name=search_term_string"
        );
    }
		json_structuring_markup_set_schema_json( $args );
	}
}
