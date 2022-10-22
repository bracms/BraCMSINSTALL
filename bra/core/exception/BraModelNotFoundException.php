<?php

namespace Bra\core\exception;


class BraModelNotFoundException extends \RuntimeException
{

	protected $error;

	public function __construct($error = "BraModelNotFoundException")
	{
		$this->error   = $error;
		$this->message = is_array($error) ? implode(PHP_EOL, $error) : $error;
	}

	/**
	 * 获取验证错误信息
	 * @access public
	 * @return array|string
	 */
	public function getError(): array|string {
		return $this->error;
	}
}
