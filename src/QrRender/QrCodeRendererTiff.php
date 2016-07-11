<?php

namespace Markenwerk\QrCodeSuite\QrRender;

use Markenwerk\CommonException\IoException;
use Markenwerk\QrCodeSuite\QrEncode\QrCode\QrCode;
use Markenwerk\QrCodeSuite\QrRender\Color\CmykColor;

/**
 * Class QrCodeRendererTiff
 *
 * @package Markenwerk\QrCodeSuite\QrRender
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
	 * @var int
	 */
	private $approximateSize = 1000;

	/**
	 * @var int
	 */
	private $width;

	/**
	 * @var int
	 */
	private $height;

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
	 * @return int
	 */
	public function getApproximateSize()
	{
		return $this->approximateSize;
	}

	/**
	 * @param int $approximateSize
	 * @return $this
	 */
	public function setApproximateSize($approximateSize)
	{
		$this->approximateSize = $approximateSize;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getWidth()
	{
		return $this->width;
	}

	/**
	 * @return int
	 */
	public function getHeight()
	{
		return $this->height;
	}

	/**
	 * @param QrCode $qrCode
	 * @param string $filename
	 * @throws IoException\FileWritableException
	 */
	public function render(QrCode $qrCode, $filename)
	{
		if (!is_dir(dirname($filename)) || !is_writable(dirname($filename))) {
			throw new IoException\FileWritableException('QR code path not writable.');
		}

		// Get basic info
		$width = $qrCode->getWidth();
		$height = $qrCode->getHeight();

		// Calculate params
		$blockSize = round($this->approximateSize / ($width + 2 * self::MARGIN));
		$this->width = (int)($width + 2 * self::MARGIN) * $blockSize;
		$this->height = (int)($height + 2 * self::MARGIN) * $blockSize;

		// Define colors
		$black = new \ImagickPixel($this->foregroundColor->getImagickNotation());
		$white = new \ImagickPixel($this->backgroundColor->getImagickNotation());

		// Prepare canvas
		$canvas = new \Imagick();
		$canvas->newImage($this->width, $this->height, $white, "tiff");
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
			throw new IoException\FileWritableException('QR code output file not writable');
		}
	}

}
