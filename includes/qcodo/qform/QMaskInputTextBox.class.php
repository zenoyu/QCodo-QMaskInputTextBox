<?php
/**
 * @preserve Copyright 2014 Zeno Yu <zeno.yu@gmail.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *     * Redistributions of source code must retain the above
 *       copyright notice, this list of conditions and the following
 *       disclaimer.
 *
 *     * Redistributions in binary form must reproduce the above
 *       copyright notice, this list of conditions and the following
 *       disclaimer in the documentation and/or other materials
 *       provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDER "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
 * OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR
 * TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF
 * THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 */

 	/**
	 *	QMaskInputTextBox
	 *	Using the JQuery Plugin:
	 *		http://digitalbush.com/projects/masked-input-plugin
	 *
	 *	@author Zeno Yu <zeno.yu@gmail.com>
	 *
	 *	@history:
	 *	2007-10-08 First Release
	 */
	class QMaskInputTextBox extends QTextBox {
		
		// APPEARANCE
		protected $strJavaScripts = 'jquery.js,jquery.maskedinput.js';

		protected $strMask;
		protected $strMaskWarning="Mask not match";

		public function Validate() {
			$strPattern = strtr($this->strMask,
								array(	'(' => '\(',
										')' => '\)',
										'[' => '\[',
										']' => '\]',
										'/' => '\/',
									));
			$strPattern = strtr($strPattern,
								array(	'9' => '[0-9]',
										'a' => '[a-zA-Z]',
										'*' => '[0-9a-zA-Z]'
									));
			if ( (preg_match("/^".$strPattern."$/", $this->strText) ) == false ){
				$this->strWarning = $this->strMaskWarning;
				return false;
			}
			return parent::Validate();
		}

		/**
         * Refresh From AjaxAction if needed
		 */
		public function GetScript(){
			if( !$this->blnVisible )return '';
			if( !$this->blnEnabled )return '';
			if( $this->strMask == '' )return '';
			return sprintf('$("#%s").mask( "%s" )',
							$this->strControlId,
							$this->strMask
						);
		}
		/**
		 *	Setup the Javascript
		 */
		public function GetEndScript() {
			if( !$this->blnVisible )return '';
			if( !$this->blnEnabled )return '';
			$strJavaScript = $this->GetScript();
			return "$().ready(function() {".$strJavaScript.";});";
		}
		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		public function __set($strName, $mixValue) {
			$this->blnModified = true;
			switch ($strName) {
				case "Mask":
					try {
						$this->strMask = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				default:
					try {
						parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
		}
		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				case "Mask":return $this->strMask;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}
?>