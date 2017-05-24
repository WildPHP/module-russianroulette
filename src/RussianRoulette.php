<?php
/**
 * Created by PhpStorm.
 * User: rick2
 * Date: 24-4-2017
 * Time: 20:48
 */

namespace WildPHP\Modules\RussianRoulette;

use WildPHP\Core\Channels\Channel;
use WildPHP\Core\Commands\CommandHandler;
use WildPHP\Core\Commands\CommandHelp;
use WildPHP\Core\ComponentContainer;
use WildPHP\Core\Connection\Queue;
use WildPHP\Core\ContainerTrait;
use WildPHP\Core\Logger\Logger;
use WildPHP\Core\Users\User;

class RussianRoulette
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

	public function spinGun(Channel $source, User $user, array $args, ComponentContainer $container)
	{
		$this->resetGame();
		$chance = 1/$this->tries*100;
		Queue::fromContainer($container)->privmsg($source->getName(), 'The barrel has been spun and the bullet is now in a different location. ' .
			'Chance per pull: ' . $chance . '%');
	}

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
}