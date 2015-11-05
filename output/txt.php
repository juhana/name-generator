<?php
header( 'Content-Type: text/plain; charset=utf8' );

if( $error ) {
    die( $error );
}

for( $i = 0; $i < count( $generatedNames ); ++$i ) {
    echo $generatedNames[ $i ] . "\n";
}