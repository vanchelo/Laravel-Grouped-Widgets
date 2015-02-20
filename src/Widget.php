<?php namespace Vanchelo\GroupedWidgets;

class Widget {

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $abstract;

	/**
	 * @var
	 */
	protected $instance;

	/**
	 * @var string
	 */
	public $group = 'default';

	/**
	 * @var int
	 */
	public $order = 0;

	function __construct($name, $abstract)
	{
		$this->name = $name;
		$this->abstract = $abstract;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function group($name)
	{
		$this->group = $name;

		return $this;
	}

	/**
	 * @param $order
	 * @return $this
	 */
	public function order($order)
	{
		$this->order = (int) $order;

		return $this;
	}

	public function resolved()
	{
		return $this->instance !== null;
	}

	public function instance(callable $instance = null)
	{
		if (is_null($instance)) return $this->instance;

		$this->instance = $instance;
	}

	public static function create($name, $abstract)
	{
		return new self($name, $abstract);
	}

}
