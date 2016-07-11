<?php

namespace Markenwerk\QrCodeSuite\QrRender\Base;

use Markenwerk\QrCodeSuite\QrEncode\QrCode\QrCode;

/**
 * Interface QrCodeRendererInterface
 *
 * @package Markenwerk\QrCodeSuite\QrRender\Base
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
