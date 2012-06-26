<?php

class ReqsBuilder {
	
	private $gh;

	public $reqs;
	public $use_cases;
	public $features;

	function __construct( $github ) {
		
		$this->gh = $github;
	}

	function run() {
		
		$this->get_issues();
		$this->calculate_priorities();
		$this->build_features();
		$this->build_use_cases();
		$this->build_reqs();
	}

	function get_issues() {
		
		$this->issues = $this->gh->issues();
	}

	function calculate_priorities() {
		
		foreach ( $this->issues as &$i ) {

			$i->prio = "";

			if ( $this->has_label( $i, "prio:high") )
				$i->prio = "High";

			if ( $this->has_label( $i, "prio:medium") )
				$i->prio = "Medium";

			if ( $this->has_label( $i, "prio:low") )
				$i->prio = "Low";
		}
	}

	function build_features() {
		
		foreach ( $this->issues as $i ) {
			
			if ( $this->check_fields( $i ) )
				continue;

			if ( $this->has_label( $i, 'feature') ) {
				$this->features[ $i->number ] = $i;
			}
		}
	}

	function build_use_cases() {
		
		foreach ( $this->issues as $i ) {
			
			if ( $this->has_label( $i, 'use-case') ) {

				$id = array_shift( $this->get_parent_ids( $i ) );
				$i->body = substr( $i->body, strpos($i->body, "\n") );

				$this->use_cases[ $i->number ] = $i;
				$this->features[ $id ]->use_cases[ $i->number ] = &$this->use_cases[ $i->number ];
			}
		}
	}

	function build_reqs() {
		
		foreach ( $this->issues as $i ) {

			if ( $this->has_label( $i, 'requirement') ) {

				$this->reqs[ $i->number ] = $i;

				$ids = $this->get_parent_ids( $i );

				foreach ( $ids as $id )
					$this->use_cases[ $id ]->reqs[ $i->number ] = &$this->reqs[ $i->number ];
			}
		}
	}

	function has_label( $issue, $label) {
		
		if ( count( $issue->labels ) == 0 )
			return false;

		foreach ( $issue->labels as $l )
			if ( $l->name == $label )
				return true;

		return false;
	}

	function get_parent_ids( $issue ) {
		
		$first_line = array_shift( explode("\n", $issue->body) );
				
		if ( preg_match_all("@#(\d+)@", $first_line, $matches ) ) {

			return $matches[1];
		}

		return array();
	}

	function check_fields( $i ) {
		
		if ( !isset( $i->title ) )
			return FALSE;

		if ( !isset( $i->body ) )
			return FALSE;

		return TRUE;
	}
}