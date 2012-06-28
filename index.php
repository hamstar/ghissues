<?php


$lines = array(
  "/^@huboard/"
);

include 'global.php';

if ( isset( $_GET['cache'] ) ) { // use the local cache
	$issues = json_decode( file_get_contents("issues.txt") );
} else {
	$start = microtime(true);
	$gh = new Github( $repo, new Curl );
	$issues = $gh->get_issues();
	echo round( microtime(true) - $start, 2 )." seconds to get issues from github<br/><br/>";
}

if ( isset( $_GET['verbose'] ) ) { // print the features one per line
	echo "Printing feature list:<br/>";
	Github::print_issues( $issues );
	echo "<br/>";
}

$start = microtime(true);
$builder = new FeatureBuilder( $issues );
$features = $builder->get_features();
echo round( microtime(true) - $start, 2 )." seconds to process features<br/><br/>";

echo "Printing issues...<br/><br/>";
$fmtr = new IssueFormatter( $features );
$fmtr->remove_lines( $lines );

echo Markdown( $fmtr->get_markdown() );