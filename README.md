# T3AM Server - TYPO3 Authentication Manager Server

(Pron.: /tiːm/)

## What does it do?

T3AM  Server provides a simple yet secure and fast API to check and retrieve backend users as well as validation of their password.
T3AM (client) allows you to log in at any TYPO3 with a single username/password which is managed by a central system (where T3AM Server is installed).

[T3AM (client)](https://github.com/in2code-de/t3am) is required if you want to use T3AM Server.

## Installation & Configuration

Prerequisite: You should have installed T3AM (client) in another TYPO3 instance already!

1. Get T3AM Server
   a) Composer: `composer require in2code/t3am_server`
   b) TER download: [extensions.typo3.org](https://extensions.typo3.org/extension/t3am_server)
   c) github dowload [https://github.com/in2code-de/t3am_server](https://github.com/in2code-de/t3am_server/releases/latest)
2. Activate T3AM Server in the Extension Manager.
3. Open the List Module and select the root page (ID=0)
4. Create a new T3AM Client and fill in the name and description, save (without closing) and copy the token.
5. Go to your client installation (where you installed T3AM (client)), open the Extension Manager, configure T3AM by pasting the token in the respective field. Add the Server URL.

Now anyone which has an active backend account on the T3AM Server side can use that account to log into the client system without prior account creation.
If a user gets deleted in T3AM Server (not removed from the database!) the user will be removed from the client system at next login.

## Features

* RSA-encrypted transmission for the password
* Detects deleted and disabled users
* Blazing fast

## Credits

Extension.svg: <div>Icons made by <a href="https://www.flaticon.com/authors/smashicons" title="Smashicons">Smashicons</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>

## Changelog

V2.0.1
* [BUGFIX] Re-add TYPO3 v8 password check mechanism
* [BUGFIX] Use the reflection parameter type's getName method in PHP gte 7.1]
* [BUGFIX] Allow arbitrary return types on dispatcher results
* [BUGFIX] Correctly detect the be user state
* [REFACTOR] Add type hints to all methods
* [REFACTOR] Simplify the usage of the query builder
* [REFACTOR] Import all functions
* [REFACTOR] Use strict_types evreywhere
* [REFACTOR] Move exception codes and messaged to the exception class

v2.0
* Support for TYPO3 8 & 9.0
