<?php

namespace QrCodeSuite\QrRender;

use QrCodeSuite\QrEncode\QrCode\QrCode;
use QrCodeSuite\QrRender\Color\CmykColor;
use QrCodeSuite\QrRender\Exception\IoException;

/**
 * Class QrCodeRendererTiff
 *
 * @package QrCodeSuite\QrRender
 */
class QrCodeRendererTiff implements Base\QrCodeRendererInterface
{

	const MARGIN = 2;

	/**
	 * @var CmykColor
	 */
	private $foregroundColor;

	/**
	 * @var CmykColor
	 */
	private $backgroundColor;

	/**
	 * QrCodeRendererTiff constructor.
	 */
	public function __construct()
	{
		$this
			->setForegroundColor(new CmykColor(0, 0, 0, 0))
			->setBackgroundColor(new CmykColor(0, 0, 0, 100));
	}

	/**
	 * @return CmykColor
	 */
	public function getForegroundColor()
	{
		return $this->foregroundColor;
	}

	/**
	 * @param CmykColor $foregroundColor
	 * @return $this
	 */
	public function setForegroundColor(CmykColor $foregroundColor)
	{
		$this->foregroundColor = $foregroundColor;
		return $this;
	}

	/**
	 * @return CmykColor
	 */
	public function getBackgroundColor()
	{
		return $this->backgroundColor;
	}

	/**
	 * @param CmykColor $backgroundColor
	 * @return $this
	 */
	public function setBackgroundColor(CmykColor $backgroundColor)
	{
		$this->backgroundColor = $backgroundColor;
		return $this;
	}

	/**
	 * @param QrCode $qrCode
	 * @param string $filename
	 * @throws IoException
	 */
	public function render(QrCode $qrCode, $filename)
	{
		if (!is_dir(dirname($filename)) || !is_writable(dirname($filename))) {
			throw new IoException('QR code path not writable.');
		}

		// Get basic info
		$width = $qrCode->getWidth();
		$height = $qrCode->getHeight();

		// Calculate params
		$blockSize = ceil(1000 / ($width + 2 * self::MARGIN));
		$symbolWidth = ($width + 2 * self::MARGIN) * $blockSize;
		$symbolHeight = ($height + 2 * self::MARGIN) * $blockSize;

		// Define colors
		$black = new \ImagickPixel($this->foregroundColor->getImagickNotation());
		$white = new \ImagickPixel($this->backgroundColor->getImagickNotation());

		// Prepare canvas
		$canvas = new \Imagick();
		$canvas->newImage($symbolWidth, $symbolHeight, $white, "tiff");
		$canvas->setImageColorspace(\Imagick::COLORSPACE_CMYK);
		$canvas->setImageDepth(8);

		// Prepare block
		$block = new \Imagick();
		$block->newImage($blockSize, $blockSize, $black, "tiff");
		$block->setImageColorspace(\Imagick::COLORSPACE_CMYK);
		$block->setImageDepth(8);

		// Draw blocks
		$top = self::MARGIN * $blockSize;
		for ($h = 1; $h <= $height; $h++) {
			$left = self::MARGIN * $blockSize;
			$qrCodePointRow = $qrCode->getRow($h);
			for ($w = 1; $w <= $width; $w++) {
				$qrCodePpoint = $qrCodePointRow->getPoint($w);
				if ($qrCodePpoint->isActive()) {
					$canvas->compositeImage($block, \Imagick::COMPOSITE_ATOP, $left, $top);
				}
				$left += $blockSize;
			}
			$top += $blockSize;
		}

		// Write out the image
		$writeSuccess = @$canvas->writeImage($filename);
		$canvas->clear();
		$canvas->destroy();
		$block->clear();
		$block->destroy();

		if ($writeSuccess !== true) {
			throw new IoException('QR code output file not writable');
		}
	}

}
