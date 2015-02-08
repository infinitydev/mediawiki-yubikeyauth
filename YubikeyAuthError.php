<?php

class MWYubikeyAuthError extends MWException {
	function __construct( $message  ) {
		parent::__construct( $message );
	}

	function getPageTitle() {
		return wfMessage('autherror-header')->text();
	}

	function getHTML() {
		global $wgShowExceptionDetails;
		$class = strtolower( get_class( $this->getPrevious() ) );
		$box_content = wfMessage($this->message)->text();
		$box = '<div class="errorbox" style="float: none;">' . $box_content . "</div>";

		if ( $wgShowExceptionDetails ) {
			$box .= "<br /><b>Message from authentication server:</b> " . $this->message;
		}

		return $box . parent::getHTML();
	}
}
?>
