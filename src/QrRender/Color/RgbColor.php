<?php

namespace QrCodeSuite\QrRender\Color;

/**
 * Class RgbColor
 *
 * @package QrCodeSuite\QrRender\Color
 */
class RgbColor
{

	/**
	 * @var int
	 */
	private $red = 0;

	/**
	 * @var int
	 */
	private $green = 0;

	/**
	 * @var int
	 */
	private $blue = 0;

	/**
	 * RgbColor constructor.
	 *
	 * @param int $red
	 * @param int $green
	 * @param int $blue
	 */
	public function __construct($red, $green, $blue)
	{
		$this
			->setRed($red)
			->setGreen($green)
			->setBlue($blue);
	}

	/**
	 * @return int
	 */
	public function getRed()
	{
		return $this->red;
	}

	/**
	 * @param int $red
	 * @return $this
	 */
	public function setRed($red)
	{
		if (!$this->validateColorChannelValue($red)) {
			throw new \InvalidArgumentException('Red color channel value invalid.');
		}
		$this->red = $red;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getGreen()
	{
		return $this->green;
	}

	/**
	 * @param int $green
	 * @return $this
	 */
	public function setGreen($green)
	{
		if (!$this->validateColorChannelValue($green)) {
			throw new \InvalidArgumentException('Green color channel value invalid.');
		}
		$this->green = $green;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getBlue()
	{
		return $this->blue;
	}

	/**
	 * @param int $blue
	 * @return $this
	 */
	public function setBlue($blue)
	{
		if (!$this->validateColorChannelValue($blue)) {
			throw new \InvalidArgumentException('Blue color channel value invalid.');
		}
		$this->blue = $blue;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getHex()
	{
		return
			'#'
			. $this->colorChannelValueToHex($this->getRed())
			. $this->colorChannelValueToHex($this->getGreen())
			. $this->colorChannelValueToHex($this->getBlue());
	}

	/**
	 * @param mixed $colorChannelValue
	 * @return bool
	 */
	private function validateColorChannelValue($colorChannelValue)
	{
		if (!is_int($colorChannelValue)) {
			return false;
		}
		return ($colorChannelValue >= 0 && $colorChannelValue <= 255);
	}

	/**
	 * @param int $int
	 * @return mixed
	 */
	private function colorChannelValueToHex($int)
	{
		$hex = (string)dechex($int);
		return str_pad($hex, 2, '0', STR_PAD_LEFT);
	}

}
