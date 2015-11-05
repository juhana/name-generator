<?php
if( !empty( $_GET[ 'callback' ] ) ){
    $callback = $_GET[ 'callback' ];

    if( preg_match( '/[^0-9a-zA-Z\$_]|^(abstract|boolean|break|byte|case|catch|char|class|const|continue|debugger|default|delete|do|double|else|enum|export|extends|false|final|finally|float|for|function|goto|if|implements|import|in|instanceof|int|interface|long|native|new|null|package|private|protected|public|return|short|static|super|switch|synchronized|this|throw|throws|transient|true|try|typeof|var|volatile|void|while|with|NaN|Infinity|undefined)$/', $callback ) ) {
        die( 'Invalid callback identifier. Only alphanumeric characters, _ and $ allowed.' );
    }

    header( 'Content-Type: text/javascript; charset=utf8' );

    echo $callback . '(' . json_encode( $generatedNames ) . ');';
}
else {
    require "json.php";
}