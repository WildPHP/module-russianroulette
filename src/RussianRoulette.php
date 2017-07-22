<?php
/**
 * Copyright 2017 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Modules\RussianRoulette;

use WildPHP\Core\Channels\Channel;
use WildPHP\Core\Commands\CommandHandler;
use WildPHP\Core\Commands\CommandHelp;
use WildPHP\Core\ComponentContainer;
use WildPHP\Core\Connection\Queue;
use WildPHP\Core\ContainerTrait;
use WildPHP\Core\Modules\BaseModule;
use WildPHP\Core\Users\User;

class RussianRoulette extends BaseModule
{
	use ContainerTrait;

	/**
	 * @var int
	 */
	protected $tries = 10;

	protected $current = 0;

	protected $bullet = 0;

	/**
	 * RussianRoulette constructor.
	 *
	 * @param ComponentContainer $container
	 */
	public function __construct(ComponentContainer $container)
	{
		$commandHelp = new CommandHelp();
		$commandHelp->addPage('See for yourself what happens.');
		CommandHandler::fromContainer($container)->registerCommand('pull', [$this, 'pullTrigger'], $commandHelp, 0, 0);
		CommandHandler::fromContainer($container)->registerCommand('spin', [$this, 'spinGun'], $commandHelp, 0, 0);

		$this->setContainer($container);
		$this->resetGame();
	}

	/**
	 * @param Channel $source
	 * @param User $user
	 * @param array $args
	 * @param ComponentContainer $container
	 */
	public function spinGun(Channel $source, User $user, array $args, ComponentContainer $container)
	{
		$this->resetGame();
		$chance = 1/$this->tries*100;
		Queue::fromContainer($container)->privmsg($source->getName(), 'The barrel has been spun and the bullet is now in a different location. ' .
			'Chance per pull: ' . $chance . '%');
	}

	/**
	 * @param Channel $source
	 * @param User $user
	 * @param array $args
	 * @param ComponentContainer $container
	 */
	public function pullTrigger(Channel $source, User $user, array $args, ComponentContainer $container)
	{
		$this->current++;

		if ($this->current == $this->bullet)
		{
			Queue::fromContainer($container)->kick($source->getName(), $user->getNickname(), 'KAPOW!');
			Queue::fromContainer($container)->privmsg($source->getName(), $user->getNickname() . ' was fatally shot. Whoops.');
			$this->resetGame();
			return;
		}

		Queue::fromContainer($container)->privmsg($source->getName(), '*click*');
	}

	protected function resetGame()
	{
		$this->current = 0;
		$this->bullet = mt_rand(1, $this->tries);
	}

	/**
	 * @return string
	 */
	public static function getSupportedVersionConstraint(): string
	{
		return '^3.0.0';
	}
}