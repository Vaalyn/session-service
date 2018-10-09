<?php

declare(strict_types = 1);

namespace Vaalyn\SessionService;

interface SessionInterface {
	/**
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get(string $key);

	/**
	 * @param string $key
	 * @param mixed $value
	 *
	 * @return SessionInterface
	 */
	public function set(string $key, $value): SessionInterface;

	/**
	 * @param string $key
	 *
	 * @return SessionInterface
	 */
	public function delete(string $key): SessionInterface;

	/**
	 * @return SessionInterface
	 */
	public function clear(): SessionInterface;

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function exists(string $key): bool;

	/**
	 * @return string
	 */
	public function getId(): string;

	/**
	 * @return SessionInterface
	 */
	public function start(): SessionInterface;

	/**
	 * @return void
	 */
	public function destroy(): void;
}
