# Russian Roulette Module
[![Build Status](https://scrutinizer-ci.com/g/WildPHP/module-russianroulette/badges/build.png?b=master)](https://scrutinizer-ci.com/g/WildPHP/module-russianroulette/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/WildPHP/module-russianroulette/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/WildPHP/module-russianroulette/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/wildphp/module-russianroulette/v/stable)](https://packagist.org/packages/wildphp/module-russianroulette)
[![Latest Unstable Version](https://poser.pugx.org/wildphp/module-russianroulette/v/unstable)](https://packagist.org/packages/wildphp/module-russianroulette)
[![Total Downloads](https://poser.pugx.org/wildphp/module-russianroulette/downloads)](https://packagist.org/packages/wildphp/module-russianroulette)

Russian Roulette module for WildPHP. Watch what happens!

## System Requirements
If your setup can run the main bot, it can run this module as well.

## Installation
To install this module, we will use `composer`:

```composer require wildphp/module-russianroulette```

That will install all required files for the module. In order to activate the module, add the following line to your modules array in `config.neon`:

    - WildPHP\Modules\RussianRoulette\RussianRoulette

The bot will run the module the next time it is started.

## Usage
Use the `pull` command and see what happens! To add extra randomness, use `spin`.

## License
This module is licensed under the MIT license. Please see `LICENSE` to read it.
