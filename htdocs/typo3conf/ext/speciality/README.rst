Override configuration for development
---------------------------------------

@todo check what can be done at the core level after Context landed in the Core as for 6.2. http://forge.typo3.org/issues/50131

While developing the website in a development context, it might be interesting to override some default values such as the domain name for instance.
It can be performed by adding configuration in directory ``Configuration/Development`` with two TS configuration files:

* setup.txt
* constants.txt

Theses two files will override the default configuration since loaded on the top.

