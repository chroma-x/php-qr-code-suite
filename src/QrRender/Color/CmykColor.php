<?php

namespace QrCodeSuite\QrRender\Color;

/**
 * Class CmykColor
 *
 * @package QrCodeSuite\QrRender\Color
 */
class CmykColor
{

	/**
	 * @var int
	 */
	private $cyan = 0;

	/**
	 * @var int
	 */
	private $magenta = 0;

	/**
	 * @var int
	 */
	private $yellow = 0;

	/**
	 * @var int
	 */
	private $black = 0;

	/**
	 * CmykColor constructor.
	 *
	 * @param int $cyan
	 * @param int $magenta
	 * @param int $yellow
	 * @param int $black
	 */
	public function __construct($cyan, $magenta, $yellow, $black)
	{
		$this
			->setCyan($cyan)
			->setMagenta($magenta)
			->setYellow($yellow)
			->setBlack($black);
	}

	/**
	 * @return int
	 */
	public function getCyan()
	{
		return $this->cyan;
	}

	/**
	 * @param int $cyan
	 * @return $this
	 */
	public function setCyan($cyan)
	{
		if (!$this->validateColorChannelValue($cyan)) {
			throw new \InvalidArgumentException('Cyan color channel value invalid.');
		}
		$this->cyan = $cyan;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getMagenta()
	{
		return $this->magenta;
	}

	/**
	 * @param int $magenta
	 * @return $this
	 */
	public function setMagenta($magenta)
	{
		$this->magenta = $magenta;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getYellow()
	{
		return $this->yellow;
	}

	/**
	 * @param int $yellow
	 * @return $this
	 */
	public function setYellow($yellow)
	{
		$this->yellow = $yellow;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getBlack()
	{
		return $this->black;
	}

	/**
	 * @param int $black
	 * @return $this
	 */
	public function setBlack($black)
	{
		$this->black = $black;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getImagickNotation()
	{
		return
			'cmyk('
			. $this->colorChannelValueToOct($this->getCyan()) . ','
			. $this->colorChannelValueToOct($this->getMagenta()) . ','
			. $this->colorChannelValueToOct($this->getYellow()) . ','
			. $this->colorChannelValueToOct($this->getBlack())
			. ')';
	}

	/**
	 * @return string
	 */
	public function getEpsNotation()
	{
		return
			$this->colorChannelValueToFloat($this->getCyan()) . ' '
			. $this->colorChannelValueToFloat($this->getMagenta()) . ' '
			. $this->colorChannelValueToFloat($this->getYellow()) . ' '
			. $this->colorChannelValueToFloat($this->getBlack());
	}

	/**
	 * @param int $int
	 */
	private function colorChannelValueToOct($int)
	{
		return decoct($int);
	}

	/**
	 * @param int $int
	 */
	private function colorChannelValueToFloat($int)
	{
		return decoct($int);
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
		return ($colorChannelValue >= 0 && $colorChannelValue <= 100);
	}

}
