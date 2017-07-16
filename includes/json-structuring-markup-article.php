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
 * Schema.org Article Settings Page
 */
function json_structuring_markup_article_settings_page() {
  if ( !current_user_can( 'administrator' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  if (  isset($_POST['submit']) ){
    $schema =  array(
      'type'            =>    $_POST['type'],
      'default_image'   =>    $_POST['default_image'],
      'org_name'        =>    $_POST['org_name'],
      'org_logo'        =>    $_POST['org_logo'],
      'activated'       =>    $_POST['activated']
    );
    update_option('json_structuring_markup_article', serialize( $schema ) );
  }
  $schema_article_settings = unserialize( get_option('json_structuring_markup_article') );
  if( isset($schema_article_settings) ){
    $default_image    =     $schema_article_settings['default_image'];
    $org_name         =     $schema_article_settings['org_name'];
    $org_logo         =     $schema_article_settings['org_logo'];
    $activated        =     esc_attr( $schema_article_settings['activated'] );
    $schema_activated_checked = '';
    if($activated == 'on'){
      $schema_activated_checked = 'checked';
    }
  }else{
    $default_image = '';
    $org_name = get_bloginfo('name');
    $org_logo = '';
    $activated = 'off';
  }
  echo '<div class="wrap">';
  echo '<h1>Article SCHEMA Markup SETTINGS</h1>';
  echo '<div>Provide the fields to make the validated schema for article.</div>';
  echo '<form enctype="multipart/form-data" action="" method="POST" id="article-schema">';
?>
  <table class="schema-admin-table">
    <caption>Basic Setting</caption>
	  <tbody>
      <tr>
        <th>headline :</th>
        <td><small>Default : post_title</small></td>
      </tr>
	    <tr>
        <th>datePublished :</th>
        <td><small>Default : get_the_time( DATE_ISO8601, ID )</small></td>
      </tr>
	    <tr>
        <th>dateModified :</th>
        <td><small>Default : get_the_modified_time( DATE_ISO8601, false, ID )</small></td>
      </tr>
      <tr>
        <th>description :</th>
        <td><small>Default : post_excerpt</small></td>
      </tr>
    </tbody>
	</table>

  <table class="schema-admin-table">
    <caption>mainEntityOfPage ( recommended )</caption>
	  <tbody>
      <tr>
        <th>@type :</th>
        <td><small>"WebPage"</small></td>
      </tr>
	    <tr>
        <th>@id :</th>
        <td><small>Default : get_permalink( ID )</small></td>
      </tr>
    </tbody>
  </table>

  <table class="schema-admin-table">
    <caption>image ( required )</caption>
	  <tbody>
      <tr>
        <th>@type :</th>
        <td><small>"ImageObject"</small></td>
      </tr>
		  <tr>
        <th><label for="upload_image">url :</label></th>
        <td>
          <input type="text" name="default_image" id="logo" class="regular-text" required value="<?php echo esc_attr( $default_image ); ?>" /><small>Default: thumbnail(Featured Image)<br />When Featured image is not available then this image would be shown in the markup</small>
        </td>
      </tr>
      <tr>
        <th>height :</th>
        <td><small>Auto : The height of the image, in pixels.</small></td>
      </tr>
		  <tr>
        <th>width :</th>
        <td><small>Auto : The width of the image, in pixels. Images should be at least 696 pixels wide.</small></td>
      </tr>
    </tbody>
  </table>

  <table class="schema-admin-table">
    <caption>publisher( required )</caption>
    <tbody>
      <tr>
        <th>@type :</th>
        <td><small>"Organization"</small></td>
      </tr>
  		<tr>
        <th><label for="name">Organization Name :</label></th>
        <td><input type="text" name="org_name" id="name" class="regular-text" required value="<?php echo $org_name; ?>" /><small>Default : bloginfo("name")</small></td>
      </tr>
    </tbody>
	</table>

  <table class="schema-admin-table">
    <caption>logo ( required )</caption>
    <tbody>
      <tr>
        <th>@type :</th>
        <td><small>"ImageObject"</small></td>
      </tr>
		  <tr>
        <th><label for="logo">url :</label></th>
        <td><input type="text" name="org_logo" id="logo" class="regular-text" required value="<?php echo $org_logo; ?>" /></td>
      </tr>
      <tr>
        <th>height :</th>
        <td><small>Auto : height >= 60px.</small></td>
      </tr>
		  <tr>
        <th>width :</th>
        <td><small>Auto : width &lt;= 600px.</small></td>
      </tr>
    </tbody>
  </table>
  
  <table class="schema-admin-table">
    <tbody>
      <tr>
        <td><input type="checkbox" name="activated" value="on" <?php echo $schema_activated_checked; ?> /><strong>Activate SCHEMA.ORG Article</strong></td>
      </tr>
    </tbody>
  </table>

  <p>Setting Knowledge : <a href="https://developers.google.com/structured-data/rich-snippets/articles" title="Article Schema" target="_blank">https://developers.google.com/structured-data/rich-snippets/articles</a></p> 
<?php
  submit_button();
  echo '</form>';
  echo '</div>';
}

/**
 * Print JSON-LD Article schema markup
 */
function json_structuring_markup_display_article(){
  $schema_article_settings = unserialize( get_option('json_structuring_markup_article') );
  $activated = '';
  if( isset($schema_article_settings) ) {
    $default_image    =     esc_attr( $schema_article_settings['default_image'] );
    $org_name         =     esc_attr( $schema_article_settings['org_name'] );
    $org_logo         =     esc_attr( $schema_article_settings['org_logo'] );
    $activated        =     esc_attr( $schema_article_settings['activated'] );
  }
  if ( $activated == 'on' ) {
    global $post;
    $images = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
		if( isset($images) && empty($images) ) {
      $images[0] = $default_image;
    }
    $excerpt = json_structuring_markup_escape_text_tags($post->post_excerpt);
    $content = $excerpt === "" ? mb_substr( json_structuring_markup_escape_text_tags( $post->post_content ), 0, 110 ) : $excerpt;
    $args = array(
      "@context" => "http://schema.org",
      "@type"    => "Article",
      "mainEntityOfPage" => array(
        "@type" => "WebPage",
        "@id"   => get_permalink( $post->ID )
      ),
      "headline" => json_structuring_markup_escape_text_tags( $post->post_title ),
      "image"    => array(
        "@type"  => "ImageObject",
        "url"    => $images[0],
        "width"  => "auto",
        "height" => "auto"
      ),
      "datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
      "dateModified"  => get_post_modified_time(  DATE_ISO8601, __return_false(), $post->ID ),
      "author" => array(
        "@type" => "Person",
        "name"  => json_structuring_markup_escape_text_tags( get_the_author_meta( 'display_name', $post->post_author ) )
      ),
      "publisher" => array(
        "@type" => "Organization",
        "name"  => $org_name,
        "logo"  => array(
          "@type"  => "ImageObject",
          "url"    => $org_logo,
          "width"  => "auto",
          "height" => "auto"
        )
      ),
      "description" => $content
    );
    json_structuring_markup_set_schema_json( $args );
	}
}
