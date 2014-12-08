SpellChecker Util
=================
[![Latest Stable Version](https://poser.pugx.org/ovr/spellcheck-cli/v/stable.svg)](https://packagist.org/packages/ovr/spellcheck-cli)
[![Build Status](https://travis-ci.org/ovr/spellcheck-cli.svg)](https://travis-ci.org/ovr/spellcheck-cli)
[![License](https://poser.pugx.org/ovr/spellcheck-cli/license.svg)](https://packagist.org/packages/ovr/spellcheck-cli)

> Spell checker for your text in console.

# Installation

in project after composer init:

```bash
composer require ovr/spellcheck-cli:*
```

global env:

```bash
composer global require ovr/spellcheck-cli:*
sudo ln -s ~/.composer/vendor/bin/spellchecker /usr/bin/spellchecker
```

# How to run

You can check specified file by using command

```bash
./spellchecker check file
```