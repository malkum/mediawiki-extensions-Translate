<?php
/**
 * Implementation of JsSelectToInput class which is compatible with MediaWiki's preferences system.
 * @file
 * @author Niklas Laxström
 * @copyright Copyright © 2010 Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/**
* Implementation of JsSelectToInput class which is extends HTMLTextField.
 */
class HTMLJsSelectToInputField extends HTMLTextField {

	/**
	 * @param $value
	 * @return string
	 */
	function getInputHTML( $value ) {
		$input = parent::getInputHTML( $value );

		if ( isset( $this->mParams['select'] ) ) {
			$input = $this->mParams['select']->getHtmlAndPrepareJs() . '<br />' . $input;
		}

		return $input;
	}

	/**
	 * @param $value
	 * @return array
	 */
	function tidy( $value ) {
		$value = array_map( 'trim', explode( ',', $value ) );
		$value = array_unique( array_filter( $value ) );

		return $value;
	}

	/**
	 * @param $value
	 * @param $alldata
	 * @return bool|String
	 */
	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		if ( !isset( $this->mParams['valid-values'] ) ) {
			return true;
		}

		if ( $value === 'default' ) {
			return true;
		}

		$codes = $this->tidy( $value );
		$valid = array_flip( $this->mParams['valid-values'] );

		foreach ( $codes as $code ) {
			if ( !isset( $valid[$code] ) ) {
				return wfMsgExt( 'translate-pref-editassistlang-bad', 'parse', $code );
			}
		}

		return true;
	}

	/**
	 * @param $value
	 * @param $alldata
	 * @return string
	 */
	function filter( $value, $alldata ) {
		$value = parent::filter( $value, $alldata );
		return implode( ', ', $this->tidy( $value ) );
	}
}
