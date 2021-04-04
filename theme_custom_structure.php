<?php
/*
Plugin Name:  _ANDYP - Custom Theme Structure
Plugin URI:   https://wordpress.stackexchange.com/a/312159/110572
Description:  Page Template with page-{slug}.php to a Sub Directory
Version:      1.0.0
Author:       Andy Pearson
*/

// defining the sub-directory so that it can be easily accessed from elsewhere as well.
define( 'ANDYP_THEME_STRUCTURE', 'src/templates' );


function andyp_theme_structure( $templates = array() ) {

    /**
     * Create list of locations here.
     */
    $page_templates = [
        'src/views',
    ];
    $page_templates = apply_filters( 'andyp_template_folders', $page_templates );



    // Generally this doesn't happen, unless another plugin / theme does modifications
    // of their own. In that case, it's better not to mess with it again with our code.
    if( empty( $templates ) || ! is_array( $templates ) ) {  return $templates; }



    // if there is custom template, then our page-{slug}.php template is at the next index 
    $page_template_index = 0;
    $page_slug = get_page_template_slug();
    if( $templates[0] ===  $page_slug ) {
        $page_template_index = 1;
    }


    foreach( $page_templates as $key => $value)
    {
        $page_templates[$key] = $value . '/' . $templates[$page_template_index];
    }

    // As of WordPress 4.7, the URL decoded page-{$slug}.php template file is included in the
    // page template hierarchy just before the URL encoded page-{$slug}.php template file.
    // Also, WordPress always keeps the page id different from page slug. So page-{slug}.php will
    // always be different from page-{id}.php, even if you try to input the {id} as {slug}.
    // So this check will work for WordPress versions prior to 4.7 as well.
    if( $templates[$page_template_index] === urldecode( $templates[$page_template_index + 1] ) ) {
        $page_templates[] = ANDYP_THEME_STRUCTURE . '/' . $templates[$page_template_index + 1];
    }

    // Insert new entries.    
    array_splice( $templates, $page_template_index, 0, $page_templates );

    return $templates;
}
// the original filter hook is {$type}_template_hierarchy,
// wihch is located in wp-includes/template.php file
add_filter( 'page_template_hierarchy', 'andyp_theme_structure' );
add_filter( 'search_template_hierarchy', 'andyp_theme_structure' );