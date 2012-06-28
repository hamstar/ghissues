<?php

class Github {
	
	private $api_url = "https://api.github.com/repos";

	function __construct( $repo_string, Curl $curl ) {
		
		list( $user, $repo ) = explode( "/", $repo_string );
		$this->api_url.= "/$user/$repo";
		$this->curl = $curl;
	}

	/**
	* @return array all the issues for the current repo
	*/
	function get_issues() {

		$issues = array();
		$page_has_issues = TRUE;
		$page = 1;

		// Get every page of issues until there's none left
		while ( $page_has_issues == TRUE ) {
		
			// Call the api and decode the issues
			$url = $this->api_url . "/issues?page=$page";
			$json = $this->curl->get( $url )->body;
			$_issues = json_decode( $json );

			// No issues on this page? jump out
			if ( count( $_issues ) == 0 ) {
				$page_has_issues = FALSE; // extra protection from infinite loop
				break;
			}

			// Add the issues to the ones we already have
			$issues = array_merge( $issues, $_issues );
			++$page; // and hit the next page on the next loop
		}

		return $issues;
	}

	static function print_issues( $issues ) {

		foreach ( $issues as $i ) {

			$labels = array();
			if ( count( $i->labels ) > 0 ) {
				foreach ( $i->labels as $l ) {
					$labels[] = $l->name;
				}
			}

			echo("Issue {$i->number}: {$i->title} (".implode(',', $labels).")<br/>");
		}
	}
}