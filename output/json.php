<?php
header( 'Content-Type: application/json; charset=utf8' );

if( $error ) {
    die( json_encode( $error ) );
}

echo json_encode( $generatedNames );