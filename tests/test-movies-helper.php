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
			],
			[
				1,
				2,
			]
		];
	}

	/**

	 */
	function test_is_suitable(){

		$this->assertTrue( is_suitable_for( 0, 12 ) );

	}

}
