<?php


/**
 * Sample test case.
 */
class MovieHelper extends WP_UnitTestCase {

	/**
	 * @return array
	 */
	function provider_is_suitable() {
		return [
			[
				1,
				2,
			]
		];
	}

	/**
	 * @dataProvider provider_is_suitable
	 */
	function test_is_suitable( $age, $movie ){

		$this->assertTrue( true );


	}

}
