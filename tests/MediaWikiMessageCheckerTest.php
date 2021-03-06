<?php
/**
 * Unit tests.
 *
 * @file
 * @author Niklas Laxström
 * @copyright Copyright © 2012-2013, Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/**
 * Unit tests for MediaWikiMessageCheckerTest class.
 */
class MediaWikiMessageCheckerTest extends MediaWikiTestCase {

	/**
	 * @dataProvider getPluralFormCountProvider
	 */
	public function testGetPluralFormCount( $expected, $code, $comment ) {
		$provided = MediaWikiMessageChecker::GetPluralFormCount( $code );
		$this->assertEquals( $expected, $provided, $comment );
	}

	public function getPluralFormCountProvider() {
		return array(
			array( 2, 'en', 'English has two plural forms' ),
			array( 3, 'ro', 'Romanian has three plural forms' ),
			array( 5, 'br', 'Breton has five plural forms' ),
		);
	}

	/**
	 * @dataProvider getPluralFormsProvider
	 */
	public function testGetPluralForms( $expected, $string, $comment ) {
		$provided = MediaWikiMessageChecker::getPluralForms( $string );
		$this->assertEquals( $expected, $provided, $comment );
	}

	public function getPluralFormsProvider() {
		return array(
			array(
				array( array( '1', '2' ) ),
				'a{{PLURAL:#|1|2}}b',
				'one plural magic word is parsed correctly'
			),

			array(
				array( array( '1', '2' ), array( '3', '4' ) ),
				'{{PLURAL:#|1|2}}{{PLURAL:#|3|4}}',
				'two plural magic words are parsed correctly'
			),

			array(
				array( array( '1', '2{{car}}3' ) ),
				'a{{PLURAL:#|1|2{{car}}3}}',
				'one plural magic word with curlies inside is parsed correctly'
			),

			array(
				array( array( '0=0', '1=one', '1', '2' ) ),
				'a{{PLURAL:#|0=0|1=one|1|2}}',
				'one plural magic word with explicit forms is parsed correctly'
			),
			array(
				array(),
				'a{{PLURAL:#|0=0|1=one|1|2}',
				'unclosed plural tag is ignored'
			),
		);
	}

	/**
	 * @dataProvider removeExplicitPluralFormsProvider
	 */
	public function testRemoveExplicitPluralForms( $expected, $forms, $comment ) {
		$provided = MediaWikiMessageChecker::removeExplicitPluralForms( $forms );
		$this->assertEquals( $expected, $provided, $comment );
	}

	public function removeExplicitPluralFormsProvider() {
		return array(
			array(
				array( '1', '2' ),
				array( '1', '2' ),
				'default forms are not removed',
			),

			array(
				array( '1', '2' ),
				array( '0=0', '1', '0=0', '2', '1=one' ),
				'explicit forms are removed regardless of position',
			),
		);
	}
}
