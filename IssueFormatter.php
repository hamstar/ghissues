<?php

class IssueFormatter {

	private $markdown;
	private $features;
	private $remove_lines;

	function __construct( $features ) {
		
		$this->features = $features;
	}

	function format_feature( $f ) {

		$t = &$this->markdown;
		$t.= "\n\n## Feature #{$f->number}: {$f->title}\t{$f->prio}";
		$t.= $this->process_body("\n\n{$f->body}");

		if ( !empty( $f->use_cases ) ) {

			foreach ( $f->use_cases as $uc )
				$this->format_use_case( $uc );
		}

		if ( !empty( $f->user_scenarios ) ) {

			foreach ( $f->user_scenarios as $us )
				$this->format_user_scenario( $us );
		}
	}

	function format_use_case( $uc ) {
		
		$t = &$this->markdown;
		$t.= "\n\n### Use Case #{$uc->number}: {$uc->title}\t{$uc->prio}";
		$t.= $this->process_body("\n\n{$uc->body}");

		if ( empty( $uc->reqs ) )
			return true;

		$t.= "\n\n#### Requirements\n";

		foreach ( $uc->reqs as $r )
			$this->format_req( $r );
	}

	function format_user_scenario( $us ) {
		$t = &$this->markdown;
		$t.= "\n\n### User Scenario #{$us->number}: {$us->title}\t{$us->prio}";
		$t.= $this->process_body("\n\n{$us->body}");

		if ( empty( $us->reqs ) )
			return true;

		$t.= "\n\n#### Requirements\n";

		foreach ( $us->reqs as $r )
			$this->format_req( $r );
	}

	function format_req( $r ) {
		
		$t = &$this->markdown;
		$t.= "\n* #{$r->number}: {$r->title}\t{$r->prio}";
	}

	function run() {
		
		foreach ( $this->features as $f ) {
			
			$this->format_feature( $f );
		}
	}

	function get_markdown() {
		
		$this->run();
		return $this->markdown;
	}

	function remove_lines( $lines ) {
		$this->remove_lines = $lines;
		return $this;
	}

	function process_body( $text ) {
		
		$lines = explode("\n", $text);

		foreach ( $lines as $i => $line )
			foreach ( $this->remove_lines as $regex )
				if ( preg_match( $regex, $line ) )
					unset( $lines[$i] );
		
		$text = implode("\n", $lines );
		
		return $text;
	}
}