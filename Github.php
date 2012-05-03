<?php

class Github {
	
	private $api_url = "https://api.github.com/repos";

	function __construct( $repo_string, Curl $curl ) {
		
		$repo = explode( ":", $repo_string );
		$this->api_url.= "/{$repo[0]}/{$repo[1]}";
		$this->curl = $curl;
	}

	function issues() {
		
		$json = $this->curl->get( $this->api_url . '/issues')->body;
		return json_decode( $json );
	}
}