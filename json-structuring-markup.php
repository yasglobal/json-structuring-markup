<?php 

/**
 * Plugin Name: JSON Structuring Markup
 * Plugin URI: https://wordpress.org/plugins/json-structuring-markup/
 * Description: JSON Structuring Markup is simply the easiest solution to add valid schema.org as a JSON script in the head of posts and pages. The provided LD-JSON is validated from the google structured data.
 * Version: 0.1
 * Donate link: https://www.paypal.me/yasglobal
 * Author: YAS Global Team
 * Author URI: http://www.yasglobal.com/web-design-development/wordpress/json-structuring-markup/
 * License: GPL v3
 */

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

function json_structuring_markup_settings_link($links) { 
   $settings_link = '<a href="admin.php?page=json-structuring-markup-settings">Settings</a>'; 
   array_unshift($links, $settings_link); 
   return $links; 
}

function json_structuring_markup_menu() {
	add_menu_page('JSON SCHEMA Markup SETTINGS', 'JSON Markup', 'administrator', 'json-structuring-markup-settings', 'json_structuring_markup_settings_page');
  add_submenu_page( 'json-structuring-markup-settings', 'JSON SCHEMA Article Markup SETTINGS', 'Article Markup', 'administrator', 'json-structuring-article-markup', 'json_structuring_markup_article_settings_page' );
  add_submenu_page( 'json-structuring-markup-settings', 'JSON SCHEMA NewsArticle Markup SETTINGS', 'NewsArticle Markup', 'administrator', 'json-structuring-newsarticle-markup', 'json_structuring_markup_newsarticle_settings_page' );
  add_submenu_page( 'json-structuring-markup-settings', 'JSON SCHEMA Organization Markup SETTINGS', 'Organization Markup', 'administrator', 'json-structuring-organization-markup', 'json_structuring_markup_organization_settings_page' );
  add_submenu_page( 'json-structuring-markup-settings', 'JSON SCHEMA Website Markup SETTINGS', 'Website Markup', 'administrator', 'json-structuring-website-markup', 'json_structuring_markup_website_settings_page' );
}

/**
 * Main Plugin Settings Page
 */
function json_structuring_markup_settings_page() {
  if ( !current_user_can( 'administrator' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  echo '<div class="wrap">';
  echo '<h1>JSON SCHEMA Markup SETTINGS</h1>';
  echo '<div>Provide the number of schema type to add on your pages and posts.</div>';
  $url = get_bloginfo("url");
  $schema_article_settings      = unserialize( get_option('json_structuring_markup_article') );
  $schema_newsarticle_settings  = unserialize( get_option('json_structuring_markup_newsarticle') );
  $schema_org_settings          =   unserialize( get_option('json_structuring_markup_organization') );
  $schema_website_settings = unserialize( get_option('json_structuring_markup_website') );
  $article_class = $newsarticle_class = $org_class = $website_class = '';
  $article_status = $newsarticle_status = $org_status = $website_status = 'Disabled';
  if (isset($schema_article_settings) && !empty($schema_article_settings)) {
    if(esc_attr( $schema_article_settings['activated'] ) == 'on'){
      $article_class = 'class = enabled';
      $article_status = 'Enabled';
    }
  }
  if (isset($schema_newsarticle_settings) && !empty($schema_newsarticle_settings)) {
    if(esc_attr( $schema_newsarticle_settings['activated'] ) == 'on'){
      $newsarticle_class = 'class = enabled';
      $newsarticle_status = 'Enabled';
    }
  }
  if (isset($schema_org_settings) && !empty($schema_org_settings)) {
    if(esc_attr( $schema_org_settings['activated'] ) == 'on'){
      $org_class = 'class = enabled';
      $org_status = 'Enabled';
    }
  }
  if (isset($schema_website_settings) && !empty($schema_website_settings)) {
    if(esc_attr($schema_website_settings['activated'] ) == 'on'){
      $website_class = 'class = enabled';
      $website_status = 'Enabled';
    }
  }
?>
<table class="schema-list-table">
  <tbody>
    <tr>
      <th>Schema.org Type</th>
      <th>Status</th>
      <th>Opeations</th>
    </tr>
    <tr <?php echo $article_class; ?>>
      <td>Article</td>
      <td><?php echo $article_status; ?></td>
      <td><a href="<?php echo $url; ?>/wp-admin/admin.php?page=json-structuring-article-markup" title="Edit Article">Edit Article</a></td>
    </tr>
    <tr <?php echo $newsarticle_class; ?>>
      <td>News Article</td>
      <td><?php echo $newsarticle_status; ?></td>
      <td><a href="<?php echo $url; ?>/wp-admin/admin.php?page=json-structuring-newsarticle-markup" title="Edit NewsArticle">Edit NewsArticle</a></td>
    </tr>
    <tr <?php echo $org_class; ?>>
      <td>Organization</td>
      <td><?php echo $org_status; ?></td>
      <td><a href="<?php echo $url; ?>/wp-admin/admin.php?page=json-structuring-organization-markup" title="Edit Organization">Edit Organization</a></td>
    </tr>
    <tr <?php echo $website_class; ?>>
      <td>Web Site</td>
      <td><?php echo $website_status; ?></td>
      <td><a href="<?php echo $url; ?>/wp-admin/admin.php?page=json-structuring-website-markup" title="Edit Web Site">Edit Web Site</a></td>
    </tr>
  </tbody>
</table>
  <p><strong>Validation :</strong> You can validate any type of the Schema from here: <a href="https://search.google.com/structured-data/testing-tool" title="Schema Validator" target="_blank">https://search.google.com/structured-data/testing-tool</a></p> 
  <?php
  echo '</div>';
}

function json_structuring_markup_escape_text_tags ( $text ) {
	return (string) str_replace( array( "\r", "\n" ), '', strip_tags( $text ) );
}

function json_structuring_markup_set_schema_json ( array $args ) {
	echo '<script type="application/ld+json">' , PHP_EOL;
	echo json_encode( $args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) , PHP_EOL;
	echo '</script>' , PHP_EOL;
}

function json_structuring_markup_styles() {
   wp_register_style( 'style', plugins_url('/style.css', __FILE__) );
   wp_enqueue_style( 'style' );
}

if(function_exists("add_action") && function_exists("add_filter")) { 

   include_once(plugin_dir_path( __FILE__ ) . 'includes/json-structuring-markup-article.php');
   include_once(plugin_dir_path( __FILE__ ) . 'includes/json-structuring-markup-newsarticle.php');
   include_once(plugin_dir_path( __FILE__ ) . 'includes/json-structuring-markup-organization.php');
   include_once(plugin_dir_path( __FILE__ ) . 'includes/json-structuring-markup-website.php');

   $plugin = plugin_basename(__FILE__); 
   add_filter("plugin_action_links_$plugin", 'json_structuring_markup_settings_link' );

   add_action( 'admin_menu', 'json_structuring_markup_menu' );
   add_action ('wp_head','json_structuring_markup_display_article');
   add_action ('wp_head','json_structuring_markup_display_newsarticle');
   add_action ('wp_head','json_structuring_markup_display_organization');
   add_action ('wp_head','json_structuring_markup_display_website');

   if (isset($_GET['page']) && ($_GET['page'] == 'json-structuring-markup-settings' || $_GET['page'] == 'json-structuring-article-markup' || $_GET['page'] == 'json-structuring-newsarticle-markup' || $_GET['page'] == 'json-structuring-organization-markup' || $_GET['page'] == 'json-structuring-website-markup' )) {
      add_action('admin_print_styles', 'json_structuring_markup_styles');
   }

}
