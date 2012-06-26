<?php

class IssueFormatter {

	private $markdown;
	private $builder;

	function __construct( ReqsBuilder $builder ) {
		
		$this->builder = $builder;
	}

	function format_feature( $f ) {
		
		$t = &$this->markdown;
		$t.= "\n\n## {$f->title}\t{$f->prio}";
		$t.= "\n\n{$f->body}";

		if ( empty( $f->use_cases ) )
			return true;

		foreach ( $f->use_cases as $uc )
			$this->format_use_case( $uc );
	}

	function format_use_case( $uc ) {
		
		$t = &$this->markdown;
		$t.= "\n\n### Use Case #{$uc->number}: {$uc->title}\t{$uc->prio}";
		$t.= "\n\n{$uc->body}";

		if ( empty( $uc->reqs ) )
			return true;

		$t.= "\n\n#### Requirements\n";

		foreach ( $uc->reqs as $r )
			$this->format_req( $r );
	}

	function format_req( $r ) {
		
		$t = &$this->markdown;
		$t.= "\n* #{$r->number}: {$r->title}\t{$r->prio}";
	}

	function run() {
		
		foreach ( $this->builder->features as $f ) {
			
			$this->format_feature( $f );
		}
	}

	function get_markdown() {
		
		$this->run();
		return $this->markdown;
	}
}