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
 * Schema.org Organization Settings Page
 */
function json_structuring_markup_organization_settings_page() {
  if ( !current_user_can( 'administrator' ) )  {
	  wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  if (isset($_POST['submit'])){
    $schema_org =  array(
        'name'                =>    $_POST['name'],
        'url'                 =>    $_POST['url'],
        'logo'                =>    $_POST['logo'],
        'founder'             =>    $_POST['founder'],
        'founding_date'       =>    $_POST['founding_date'],
        'founding_location'   =>    $_POST['founding_location'],
        'activated'           =>    $_POST['activated'],
    );
    update_option('json_structuring_markup_organization', serialize( $schema_org ) );
    
    $schema_corporate =  array(
        'enabled'             =>    $_POST['enabled'],
        'telephone'           =>    $_POST['telephone'],
        'contact_type'        =>    $_POST['contact_type'],
        'area_served'         =>    $_POST['area_served'],
        'contact_option_1'    =>    $_POST['contact_option_1'],
        'contact_option_2'    =>    $_POST['contact_option_2'],
        'available_language'  =>    $_POST['available_language']
    );
    update_option('json_structuring_markup_organization_corporate', serialize( $schema_corporate ) );
    
    $schema_social =  array(
        'facebook'            =>    $_POST['facebook'],
        'twitter'             =>    $_POST['twitter'],
        'google_plus'         =>    $_POST['google_plus'],
        'instagram'           =>    $_POST['instagram'],
        'youtube'             =>    $_POST['youtube'],
        'linked_in'           =>    $_POST['linked_in'],
        'my_space'            =>    $_POST['my_space'],
        'pinterest'           =>    $_POST['pinterest'],
        'sound_cloud'         =>    $_POST['sound_cloud'],
        'tumblr'              =>    $_POST['tumblr'],        
    );
    update_option('json_structuring_markup_organization_social', serialize( $schema_social ) );
  }
  
  $schema_org_settings          =   unserialize( get_option('json_structuring_markup_organization') );
  $schema_org_corp_settings     =   unserialize( get_option('json_structuring_markup_organization_corporate') );
  $schema_org_social_settings   =   unserialize( get_option('json_structuring_markup_organization_social') );

  $name                   =   get_bloginfo('name');
  $url                    =   get_bloginfo("url");
  $logo                   =   '';
  $founder                =   $founding_date = $founding_location = '';
  $activated              =   $enabled = 'off';
  $telephone              =   '';
  $area_served            =   'US';
  $available_language     =   'English';
  $contact_option_1       =   '';
  $contact_option_2       =   '';
  $facebook = $twitter = $google_plus = $instagram = $youtube = $linked_in = $my_space = $pinterest = $sound_cloud = $tumblr = '';
  $schema_activated_checked = $schema_enabled_checked = '';
  
  if( isset($schema_org_settings) && !empty($schema_org_settings) ) {
    $name                 =   $schema_org_settings['name'];
    $url                  =   $schema_org_settings['url'];
    $logo                 =   $schema_org_settings['logo'];
    $founder              =   $schema_org_settings['founder'];
    $founding_date        =   $schema_org_settings['founding_date'];
    $founding_location    =   $schema_org_settings['founding_location'];
    $activated            =   esc_attr( $schema_org_settings['activated'] );
    if($activated == 'on') {
      $schema_activated_checked = 'checked';
    }
  }
  
  if( isset($schema_org_corp_settings) && !empty($schema_org_corp_settings) ) {
    $enabled              =   esc_attr( $schema_org_corp_settings['enabled'] );
    if ( $enabled == 'on' ) {
      $schema_enabled_checked =   'checked';
    }
    $telephone            =   $schema_org_corp_settings['telephone'];
    $contact_type         =   $schema_org_corp_settings['contact_type'];
    $area_served          =   $schema_org_corp_settings['area_served'];
    $contact_option_1     =   $schema_org_corp_settings['contact_option_1'];
    $contact_option_2     =   $schema_org_corp_settings['contact_option_2'];
    $available_language   =   $schema_org_corp_settings['available_language'];
    if( $contact_option_1 == 'TollFree' ) {
      $schema_contact_option_1_checked = 'checked';
    }
    if( $contact_option_2 == 'HearingImpairedSupported' ) {
      $schema_contact_option_2_checked = 'checked';
    }
  }
  
  if( isset($schema_org_social_settings) && !empty($schema_org_social_settings) ) {
    $facebook             =   $schema_org_social_settings['facebook'];
    $twitter              =   $schema_org_social_settings['twitter'];
    $google_plus          =   $schema_org_social_settings['google_plus'];
    $instagram            =   $schema_org_social_settings['instagram'];
    $youtube              =   $schema_org_social_settings['youtube'];
    $linked_in            =   $schema_org_social_settings['linked_in'];
    $my_space             =   $schema_org_social_settings['my_space'];
    $pinterest            =   $schema_org_social_settings['pinterest'];
    $sound_cloud          =   $schema_org_social_settings['sound_cloud'];
    $tumblr               =   $schema_org_social_settings['tumblr'];
  }
  
  echo '<div class="wrap">';
  echo '<h1>Organization SCHEMA Markup SETTINGS</h1>';
  echo '<div>Provide the fields to make the validated schema for organization.</div>';
  echo '<form enctype="multipart/form-data" action="" method="POST" id="org-schema">';
?>
  <table class="schema-admin-table org-basic-settings">
    <caption>Basic Setting</caption>
    <tbody>
      <tr>
        <th><label for="type">Type : </label></th>
        <td>Organization</td>
      </tr>
      <tr>
        <th><label for="name">Name :</label></th>
        <td><input type="text" name="name" id="name" class="regular-text" required value="<?php echo $name; ?>" /><small>Default : bloginfo("name")</small></td>
      </tr>
      <tr>
        <th><label for="url">url :</label></th>
        <td><input type="text" name="url" id="url" class="regular-text" required value="<?php echo $url; ?>" /><small>Default : bloginfo("url")</small></td>
      </tr>
      <tr>
        <th><label for="logo">Logo :</label></th>
        <td><input type="text" name="logo" id="logo" class="regular-text" required value="<?php echo $logo; ?>" /></td>
      </tr>
    </tbody>
  </table>
  
  <table class="schema-admin-table org-foundation">
    <caption>Foundation ( Optional )</caption>
    <tbody>
      <tr>
        <th><label for="founder">Founder :</label></th>
        <td><input type="text" name="founder" id="founder" class="regular-text" value="<?php echo $founder; ?>" /></td>
      </tr>
      <tr>
        <th><label for="founding-date">Founding Date :</label></th>
        <td><input type="text" name="founding_date" id="founding-date" class="regular-text" value="<?php echo $founding_date; ?>" /></td>
      </tr>
      <tr>
        <th><label for="founding-location">Founding Location :</label></th>
        <td><input type="text" name="founding_location" id="founding-location" class="regular-text" value="<?php echo $founding_location; ?>" /></td>
      </tr>
    </tbody>
  </table>

  <table class="schema-admin-table org-corporate">
    <caption>Corporate Contacts ( Optional )</caption>
    <tbody>
      <tr>
        <th><label for="enabled">Contact Point :</label></th>
        <td><input type="checkbox" name="enabled" value="on" <?php echo $schema_enabled_checked; ?> />Enabled</td>
      </tr>
      <tr>
        <th><label for="telephone">Telephone :</label></th>
        <td><input type="text" name="telephone" id="telephone" class="regular-text" value="<?php echo $telephone; ?>" /><small>e.g:  +1-800-555-1212</small></td>
      </tr>
      <tr>
        <th><label for="corp-type">Type : </label></th>
        <td>
          <select name="contact_type">
            <option value="customer support" <?php if ( $contact_type == '' || $contact_type == 'customer support' ) echo 'selected="selected"'; ?>>customer support</option>
            <option value="technical support" <?php if ( $contact_type == 'technical support' ) echo 'selected="selected"'; ?> >technical support</option>
            <option value="billing support" <?php if ( $contact_type == 'billing support' ) echo 'selected="selected"'; ?> >billing support</option>
            <option value="bill payment" <?php if ( $contact_type == 'bill payment' ) echo 'selected="selected"'; ?> >bill payment</option>
            <option value="sales" <?php if ( $contact_type == 'sales' ) echo 'selected="selected"'; ?> >sales</option>
            <option value="reservations" <?php if ( $contact_type == 'reservations' ) echo 'selected="selected"'; ?> >reservations</option>
            <option value="credit card support" <?php if ( $contact_type == 'credit card support' ) echo 'selected="selected"'; ?> >credit card support</option>
            <option value="emergency" <?php if ( $contact_type == 'emergency' ) echo 'selected="selected"'; ?> >emergency</option>
            <option value="baggage tracking" <?php if ( $contact_type == 'baggage tracking' ) echo 'selected="selected"'; ?> >baggage tracking</option>
            <option value="roadside assistance" <?php if ( $contact_type == 'roadside assistance' ) echo 'selected="selected"'; ?> >roadside assistance</option>
            <option value="package tracking" <?php if ( $contact_type == 'package tracking' ) echo 'selected="selected"'; ?> >package tracking</option>
          </select>
        </td>
      </tr>
      <tr>
        <th><label for="area-served">Area Served :</label></th>
        <td><input type="text" name="area_served" id="area-served" class="regular-text" value="<?php echo $area_served; ?>" /><small>Single Area: US Multiple Area: US, CA, MX</small></td>
      </tr>
      <tr>
        <th><label for="contact-option">Contact Option :</label></th>
        <td>
          <div><input type="checkbox" name="contact_option_1" value="TollFree" <?php echo $schema_contact_option_1_checked; ?> />TollFree</div>
          <div><input type="checkbox" name="contact_option_2" value="HearingImpairedSupported" <?php echo $schema_contact_option_2_checked; ?> />HearingImpairedSupported</div>
        </td>
      </tr>
      <tr>
        <th><label for="available-language">Available Language :</label></th>
        <td><input type="text" name="available_language" id="available-language" class="regular-text" value="<?php echo $available_language; ?>" /><small>Single Language: English Multiple Languages: English, French</small></td>
      </tr>
    </tbody>
  </table>

  <table class="schema-admin-table org-social">
    <caption>Social Profiles ( Optional )</caption>
    <tbody>
      <tr>
        <th><label for="facebook">Facebook :</label></th>
        <td><input type="text" name="facebook" id="facebook" class="regular-text" value="<?php echo $facebook; ?>" /></td>
      </tr>
      <tr>
        <th><label for="twitter">Twitter :</label></th>
        <td><input type="text" name="twitter" id="twitter" class="regular-text" value="<?php echo $twitter; ?>" /></td>
      </tr>
      <tr>
        <th><label for="google-plus">Google+ :</label></th>
        <td><input type="text" name="google_plus" id="google-plus" class="regular-text" value="<?php echo $google_plus; ?>" /></td>
      </tr>
      <tr>
        <th><label for="instagram">Instagram :</label></th>
        <td><input type="text" name="instagram" id="instagram" class="regular-text" value="<?php echo $instagram; ?>" /></td>
      </tr>
      <tr>
        <th><label for="youtube">YouTube :</label></th>
        <td><input type="text" name="youtube" id="youtube" class="regular-text" value="<?php echo $youtube; ?>" /></td>
      </tr>
      <tr>
        <th><label for="linked-in">LinkedIn :</label></th>
        <td><input type="text" name="linked_in" id="linked-in" class="regular-text" value="<?php echo $linked_in; ?>" /></td>
      </tr>
      <tr>
        <th><label for="my-space">Myspace :</label></th>
        <td><input type="text" name="my_space" id="my-space" class="regular-text" value="<?php echo $my_space; ?>" /></td>
      </tr>
      <tr>
        <th><label for="pinterest">Pinterest :</label></th>
        <td><input type="text" name="pinterest" id="pinterest" class="regular-text" value="<?php echo $pinterest; ?>" /></td>
      </tr>
      <tr>
        <th><label for="sound-cloud">SoundCloud :</label></th>
        <td><input type="text" name="sound_cloud" id="sound-cloud" class="regular-text" value="<?php echo $sound_cloud; ?>" /></td>
      </tr>
      <tr>
        <th><label for="tumblr">Tumblr :</label></th>
        <td><input type="text" name="tumblr" id="tumblr" class="regular-text" value="<?php echo $tumblr; ?>" /></td>
      </tr>
    </tbody>
  </table>

  <table class="schema-admin-table">
    <tbody>
      <tr>
        <td><input type="checkbox" name="activated" value="on" <?php echo $schema_activated_checked; ?> /><strong>Activate SCHEMA.ORG Organization</strong></td>
      </tr>
    </tbody>
  </table>

  <p>Setting Knowledge : <a href="https://developers.google.com/search/docs/data-types/corporate-contacts" title="Organization Schema" target="_blank">https://developers.google.com/search/docs/data-types/corporate-contacts</a></p> 
<?php
  submit_button(); 
  echo '</form>';
  echo '</div>';
}

/**
 * Print JSON-LD Organization schema markup
 */
function json_structuring_markup_display_organization(){
  $schema_org_settings = unserialize( get_option('json_structuring_markup_organization') );
  $activated = '';
  if( isset($schema_org_settings) && !empty($schema_org_settings) ) {
    $activated = esc_attr( $schema_org_settings['activated'] );
  }
  if ( $activated == 'on' ) {
    $args = array(
      "@context"  =>  "http://schema.org",
      "@type"     =>  "Organization",
      "name"      =>  $schema_org_settings['name'],
      "url"       =>  $schema_org_settings['url'],
      "logo"      =>  $schema_org_settings['logo'],
    );
    if( $schema_org_settings['founder'] != '' ) {
      $args["founder"] = $schema_org_settings['founder'];
    }
    if( $schema_org_settings['founding_date'] != '' ) {
      $args["foundingDate"] = $schema_org_settings['founding_date'];
    }
    if( $schema_org_settings['founding_location'] != '' ) {
      $args["foundingLocation"] = $schema_org_settings['founding_location'];
    }
    $schema_org_social_settings = unserialize( get_option('json_structuring_markup_organization_social') );
    $schema_org_social_settings = array_filter($schema_org_social_settings);
    if ( isset($schema_org_social_settings) && !empty($schema_org_social_settings) ) {
      $org_social = array();
      foreach($schema_org_social_settings as $value) {
        if ( $value ) {
          $org_social[] = esc_url( $value );
        }
      }
      $args["sameAs"] = $org_social;
      
    }
    $schema_org_corp_settings = unserialize( get_option('json_structuring_markup_organization_corporate') );
    if ( isset($schema_org_corp_settings) && !empty($schema_org_corp_settings) && $schema_org_corp_settings['enabled'] == 'on' ) {
      $org_corp = array(
        "@type"       =>  "ContactPoint",
        "telephone"   =>  $schema_org_corp_settings['telephone'],
        "contactType" =>  $schema_org_corp_settings['contact_type']
      );
      if ( $schema_org_corp_settings['contact_option_1'] != '' && $schema_org_corp_settings['contact_option_2'] != '' ) {
        $org_corp_option = array(
          $schema_org_corp_settings['contact_option_1'],
          $schema_org_corp_settings['contact_option_2']
        );
        $org_corp["contactOption"] = $org_corp_option;
      } else {
        if( $schema_org_corp_settings['contact_option_1'] != '' ) {
          $org_corp["contactOption"] = $schema_org_corp_settings['contact_option_1'];
        }
        if( $schema_org_corp_settings['contact_option_2'] != '' ) {
          $org_corp["contactOption"] = $schema_org_corp_settings['contact_option_2'];
        }
      }
      if ( $schema_org_corp_settings['area_served'] != '' ) {
        $schema_org_corp_settings['area_served'] = preg_replace('/\s+/', '', $schema_org_corp_settings['area_served']);
        $served_areas = explode(',', $schema_org_corp_settings['area_served']);
        if( count($served_areas) > 1 ) {
          foreach($served_areas as $value) {
            $org_corp_area[] = $value;
          }
        } else {
          $org_corp_area = $schema_org_corp_settings['area_served'];
        }
        $org_corp["areaServed"] = $org_corp_area;
      }
      if ( $schema_org_corp_settings['available_language'] != '' ) {
        $schema_org_corp_settings['available_language'] = preg_replace('/\s+/', '', $schema_org_corp_settings['available_language']);
        $available_languages = explode(',', $schema_org_corp_settings['available_language']);
        if( count($available_languages) > 1 ) {
          foreach($available_languages as $value) {
            $org_corp_language[] = $value;
          }
        } else {
          $org_corp_language = $schema_org_corp_settings['available_language'];
        }
        $org_corp["availableLanguage"] = $org_corp_language;
      }
      $args["contactPoint"][] = $org_corp;
    }
    json_structuring_markup_set_schema_json( $args );
  }
}
