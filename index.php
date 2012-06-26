<?php



include 'global.php';

$builder = new ReqsBuilder( new Github( "hamstar:Braincase", new Curl ) );
$builder->run();

$fmtr = new IssueFormatter( $builder );
echo Markdown( $fmtr->get_markdown() );