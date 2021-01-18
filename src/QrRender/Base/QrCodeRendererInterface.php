<?php

namespace ChromaX\QrCodeSuite\QrRender\Base;

use ChromaX\QrCodeSuite\QrEncode\QrCode\QrCode;

/**
 * Interface QrCodeRendererInterface
 *
 * @package ChromaX\QrCodeSuite\QrRender\Base
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
