<?php
// remove these two lines to disable cross-origin requests
header( 'Access-Control-Allow-Origin: *' );
header( 'Access-Control-Allow-Methods: GET, POST' );

define( 'DEFAULT_NAMES_TO_PRINT', 40 );
define( 'MAX_NAMES_TO_PRINT', 500 );

$OUTPUT_FORMATS = array( 'html', 'json', 'txt' );

if( isset( $_GET[ 'n' ] ) && ( !empty( $_GET[ 'n' ] ) || $_GET[ 'n' ] === '0' ) ) {
	$n = intval( $_GET[ 'n' ] );
}
else {
	$n = DEFAULT_NAMES_TO_PRINT;
}

$error = false;

if( $n < 1 || $n > MAX_NAMES_TO_PRINT ) {
    $error = "Number of titles must be between 1 and " . MAX_NAMES_TO_PRINT . '.';
}

if( empty( $_GET[ 'format' ] ) ) {
    $format = 'html';
}
else {
    $format = strtolower( trim( $_GET[ 'format' ] ) );
}

if( array_search( $format, $OUTPUT_FORMATS ) === false ) {
    $error = "Unknown output format '" . htmlspecialchars( $format ) . "'. Available formats: " . implode( $OUTPUT_FORMATS, ', ' );
}

require_once( 'names.php' );

if( !$error ) {
    if( $format === 'html' ) {
        debug( '<pre>' );
    }

    $compEdition = $_GET[ 'ifcomp' ];

    if( isset( $_GET[ 'edition' ] ) )
    {
        switch( $_GET[ 'edition' ] )
        {
            // for backwards compatibility with old links
            case 'comp08':
                $compEdition = 2008;
            break;

            case 'comp09':
                $compEdition = 2009;
            break;
        }
    }

    if( isset( $comps[ $compEdition ] ) )
    {
        $specialArray = $comps[ $compEdition ];
    }

    if( $compEdition == 'all' )
    {
        $names = array();

        foreach( $comps as $compArray )
        {
            $newNames = array_merge( $names, $compArray );
            $names = $newNames;
        }
    }

    $generatedNames = array();

    for( $loop = 0; $loop < $n; ++$loop )
    {
        do
        {
            $source = array_rand( $names, 2 );

            if( empty( $specialArray ) )
            {
                // no "special edition" selected, just pick two names
                $prefix = explode( ' ', $names[ $source[ 0 ] ] );
                $suffix = explode( ' ', $names[ $source[ 1 ] ] );
            }
            else
            {
                // othewise we'll take randomly either the end part
                // or the beginning part of the title from a special list
                if( rand( 0, 1 ) )
                {
                    $prefix = explode( ' ', $specialArray[ rand( 0, count( $specialArray ) - 1 ) ] );
                    $suffix = explode( ' ', $names[ $source[ 1 ] ] );
                }
                else
                {

                    $prefix = explode( ' ', $names[ $source[ 0 ] ] );
                    $suffix = explode( ' ', $specialArray[ rand( 0, count( $specialArray ) - 1 ) ] );
                }
            }


            $prefix_words = count( $prefix );
            $suffix_words = count( $suffix );

            // if the prefix is only one word, choose that. Otherwise
            // pick a random number of words from it.
            if( $prefix_words == 1 )
            {
                $prefixToPrint = $prefix[ 0 ] . ' ';
            }
            else
            {
                $prefix_pick = rand( 1, count( $prefix ) - 1 );
                $prefixToPrint = '';

                for( $i = 0; $i < $prefix_pick; ++$i )
                {
                    $prefixToPrint .= $prefix[ $i ] . ' ';
                }
            }

        // if the prefix is only "the" or "a" or something else
        // we don't want, loop through the algorithm until
        // we get a good result.
        } while( !words_ok( trim( $prefixToPrint ) ) );


        $fullname = $prefixToPrint;
        debug( $prefixToPrint . '| ' );

        if( $suffix_words == 1 )
        {
            $fullname .= $suffix[ 0 ];
            debug( $suffix[ 0 ] );
        }
        else
        {
            $suffix_pick = rand( 1, count( $suffix ) - 1 );

            for( $i = $suffix_pick; $i < $suffix_words; ++$i )
            {
                $fullname .= $suffix[ $i ] . ' ';
                debug( $suffix[ $i ] . ' ' );
            }
        }

        debug( "\n" );

        $generatedNames[ $loop ] = trim( $fullname );
    }

    if( $format === 'html' ) {
        debug( '</pre>' );
    }
}

function words_ok( $words )
{
    $badWords = array( 'the', 'a', 'an' );

    if( in_array( strtolower( $words ), $badWords ) )
    {
        return false;
    }

    return true;
}

function debug( $msg )
{
    if( isset( $_GET[ 'debug' ] ) )
    {
        echo $msg;
    }
}

if( $error ) {
    header( $_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400 );
}

include "output/" . $format . ".php";