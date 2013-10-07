<?php

class Kwgldev_View_Helper_BackLink {

    public function backLink ($sUrl, $sText = 'Back') {
		$sContent = '<a class="btn" href="' . $sUrl . '"><i class="icon-arrow-left" title="Back"></i> ' . $sText . '</a>';
		return $sContent;
    }

}