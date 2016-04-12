<?php

namespace QrCodeSuite\QrRender\Base;

use QrCodeSuite\QrEncode\QrCode\QrCode;

/**
 * Interface QrCodeRendererInterface
 *
 * @package QrCodeSuite\QrRender\Base
 */
interface QrCodeRendererInterface
{

	/**
	 * @param QrCode $qrCode
	 * @param $filename
	 * @return void
	 */
	public function render(QrCode $qrCode, $filename);

}
