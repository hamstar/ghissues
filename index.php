<?php



include 'global.php';

$builder = new ReqsBuilder( new Github( "hamstar:Braincase", new Curl ) );
$builder->run();

$fmtr = new Formatter( $builder );
echo Markdown( $fmtr->get_markdown() );