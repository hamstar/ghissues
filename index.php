<?php



include 'global.php';

$start = microtime(true);
$gh = new Github( "hamstar:Braincase", new Curl )
$issues = $gh->get_issues();
echo round( microtime(true) - $start, 2 )." seconds to get issues<br/><br/>";

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
echo Markdown( $fmtr->get_markdown() );