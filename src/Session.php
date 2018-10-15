<?php

declare(strict_types = 1);

namespace Vaalyn\SessionService;

class Session implements SessionInterface {
	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @param array $settings
	 */
	public function __construct(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * @inheritDoc
	 */
	public function get(string $key) {
		return $this->exists($key) ? $_SESSION[$key] : null;
	}

	/**
	 * @inheritDoc
	 */
	public function set(string $key, $value): SessionInterface {
		$_SESSION[$key] = $value;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function delete(string $key): SessionInterface {
		if ($this->exists($key)) {
			unset($_SESSION[$key]);
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function clear(): SessionInterface {
		$_SESSION = [];

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function exists(string $key): bool {
		return array_key_exists($key, $_SESSION);
	}

	/**
	 * @inheritDoc
	 */
	public function getId(): string {
		return session_id();
	}

	/**
	 * @inheritDoc
	 */
	public function start(): SessionInterface {
		session_set_cookie_params(
			$this->settings['lifetime'],
			$this->settings['path'],
			$this->settings['domain'],
			$this->settings['secure'],
			$this->settings['httponly']
		);

		session_name($this->settings['name']);
		session_cache_limiter('');
		session_start();

		setcookie(
			session_name(),
			session_id(),
			time() + $this->settings['lifetime'],
			$this->settings['path'],
			$this->settings['domain'],
			$this->settings['secure'],
			$this->settings['httponly']
		);

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function destroy(): void {
		if ($this->getId()) {
			session_unset();
			session_destroy();
			session_write_close();

			if (ini_get('session.use_cookies')) {
				$params = session_get_cookie_params();
				setcookie(
					session_name(),
					'',
					time() - 4200,
					$params['path'],
					$params['domain'],
					$params['secure'],
					$params['httponly']
				);
			}
		}
	}
}
