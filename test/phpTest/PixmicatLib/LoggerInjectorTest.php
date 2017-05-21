﻿<?php

class TempClass {
	public function printMessage($msg) {
		return "Hello, $msg!";
	}
	public function throwException() {
		throw new RuntimeException('Exception thrown.');
	}
}
class LoggerInjectorTest extends PHPUnit_Framework_TestCase {
	private $agent;
	public function setUp() {
		$this->agent = new LoggerInjector(new TempClass(),
			new LoggerInterceptor(PMCLibrary::getLoggerInstance('TempClass')));
	}
	public function testInstance() {
		$this->assertNotNull($this->agent);
	}
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInstanceInvaildPrincipal() {
		new LoggerInjector(
			array(1, 2, 3),
			new LoggerInterceptor(PMCLibrary::getLoggerInstance('TempClass'))
		);
	}
	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testInstanceInvaildInterceptor() {
		if (class_exists('\TypeError')) {
			try{
				new LoggerInjector(new TempClass(), new TempClass());
			} catch (\TypeError $e) {
				throw new \PHPUnit_Framework_Error(
					'error',
					0,
					$e->getFile(),
					$e->getLine()
				);
			}
		} 
		else {
			new LoggerInjector(new TempClass(), new TempClass());
		}
	}
	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testInstanceInvaildInterceptor2() {
		if (class_exists('\TypeError')) {
			try{
				new LoggerInjector(new TempClass(), NULL);
			} catch (\TypeError $e) {
				throw new \PHPUnit_Framework_Error(
					'error',
					0,
					$e->getFile(),
					$e->getLine()
				);
			}
		} 
		else {
			new LoggerInjector(new TempClass(), NULL);
		}
	}
	public function testCall() {
		$this->assertEquals('Hello, Mary!', $this->agent->printMessage('Mary'));
	}
	public function testCallNotExists() {
		$this->assertNull($this->agent->NonExists());
	}
	public function testCallException() {
		$this->assertNull($this->agent->throwException());
	}
}
