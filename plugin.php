<?php
    
    /*
     Plugin Name: Regenerate Shortened URL
     Plugin URI: https://github.com/TheLeonKing/yourls-api-regenerate-url
     Description: Adds a custom API action 'regenerate_url' to generate a new keyword for a URL that has already been shortened.
     Version: 0.1
     Author: The Leon King
     Author URI: https://github.com/TheLeonKing/
    */
    
    yourls_add_filter( 'api_action_regenerate_url', 'api_regenerate_url' );
    
    function api_regenerate_url() {
        
        // Check if an old keyword was passed.
        if ( ! isset( $_REQUEST['old'] ) ) {
            return array(
                'statusCode' => 400,
                'status'     => 'fail',
                'simple'     => "Need an 'old' parameter",
                'message'    => 'error: missing param',
            );
        }
        
        // Sanitize the old keyword, so as to accept both 'http://ozh.in/abc' and 'abc'.
        $oldkeyword = $_REQUEST['old'];
        $oldkeyword = str_replace( YOURLS_SITE . '/' , '', $oldkeyword);
        $oldkeyword = yourls_sanitize_string( $oldkeyword );
        
        // Find the long URL and title associated with the keyword.
        $url = yourls_get_keyword_longurl( $oldkeyword );
        $url = urldecode($url);
        
        $title = yourls_get_keyword_title( $oldkeyword );
        
        // Use the provided keyword, or generate a new one.
        if ( isset( $_REQUEST['new'] ) ) {
            $newkeyword = $_REQUEST['new'];
            
            if ( ! yourls_keyword_is_free($newkeyword) ) {
                return array(
                    'statusCode' => 400,
                    'simple'     => "Keyword $newkeyword already exists",
                    'message'    => 'error: new keyword already exists',
                );
            }
        } else {
            $newkeyword = yourls_apply_filter( 'random_keyword', $newkeyword, $url, $title );
            
            // Keep generating new keywords until we find one that is unused.
            while ( ! yourls_keyword_is_free($newkeyword) ) {
                $newkeyword = yourls_apply_filter( 'random_keyword', $newkeyword, $url, $title );
            }
        }

        // Update the database and send a response.
        if ( yourls_edit_link( $url, $oldkeyword, $newkeyword, $title ) ) {
            return array(
                'statusCode' => 200,
                'simple'     => "Keyword $oldkeyword changed to $newkeyword for $url",
                'message'    => 'success: changed',
            );
        } else {
            return array(
                'statusCode' => 500,
                'status'     => 'fail',
                'simple'     => 'Error: could not update keyword',
                'message'    => 'error: unknown error',
            );
        }
        
    }

?>
