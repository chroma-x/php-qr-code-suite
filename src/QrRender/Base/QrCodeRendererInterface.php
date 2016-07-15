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

	const MARGIN = 2;

	/**
	 * @param QrCode $qrCode
	 * @param $filename
	 * @return void
	 */
	public function render(QrCode $qrCode, $filename);

}
