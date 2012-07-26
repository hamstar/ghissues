<?php

	if ( !isset( $_GET['repo'] ) || $_GET['repo'] == "" )
		die("Please specify a repo to get like index.php?repo=hamstar/Braincase");

	$repo = $_GET['repo'];

	$lines = array(
	  "/^@huboard/" // remove huboard tags
	);

	include 'global.php';

?>
<html>
	<head>
		<title>Ghissues</title>
	</head>
	<body>

		<div style="border: 2px solid black; background: #F4E790; padding: 10px;">

			<?php

			if ( isset( $_GET['repo'] ) && isset( $_GET['cache'] ) )
				echo "WARNING: The cache argument overrides the repo argument and loads the issues from the last repo used without the cache argument<br/><br/>";

			if ( isset( $_GET['cache'] ) ) { // use the local cache
				$issues = json_decode( file_get_contents("issues.txt") );
				echo "Using ".count( $issues )." issues from the cache<br/><br/>";
			} else {
				$start = microtime(true);
				$gh = new Github( $repo, new Curl );
				$issues = $gh->get_issues();
				echo round( microtime(true) - $start, 2 )." seconds to get ".count( $issues )." issues from github repo $repo<br/><br/>";
			}

			if ( isset( $_GET['verbose'] ) ) { // print the features one per line
				echo "Printing feature list:<br/>";
				Github::print_issues( $issues );
				echo "<br/>";
			}

			$start = microtime(true);
			$builder = new FeatureBuilder( $issues );
			$features = $builder->get_features();
			echo round( microtime(true) - $start, 2 )
				." seconds to process "
				.count($issues)." issues into "
				.count($features)." features, "
				.count($builder->user_scenarios)." user scenarios, "
				.count($builder->use_cases)." use cases and "
				.count($builder->reqs)." requirements<br/><br/>";

			echo "Printing formatted features...";

			?>
			
			<pre>
			<?php

			$fmtr = new IssueFormatter( $features );
			$fmtr->remove_lines( $lines );

			$text = Markdown( $fmtr->get_markdown() );

			?>
			</pre>

		</div>

		<div>

			<?php echo $text; ?>

		</div>

	</body>
</html>