<?php

class GRDR_Grd_Dj_Cpt_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'GRDR_Grd_Dj_Cpt') );
	}

	function test_class_access() {
		$this->assertTrue( dj_rotator_for_wordpress()->grd-dj-cpt instanceof GRDR_Grd_Dj_Cpt );
	}

  function test_cpt_exists() {
    $this->assertTrue( post_type_exists( 'grdr-grd-dj-cpt' ) );
  }
}
