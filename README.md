Vault PHP SDK
=============

This is a fork of the great [jippi/vault-php-sdk](https://github.com/jippi/vault-php-sdk) project. That project is using Guzzle 4/5 and upgrading to Guzzle 6 would cause some backwards-incompatible changes.
My version of the project is is using Guzzle 6 (thanks [@stevenscg](https://github.com/jippi/vault-php-sdk/pull/3) for doing most of that work) and also has more services implemented.
For now, I've only implemented those that I need, but I will do others in the future. Feel free to submit PRs.
I plan to add tests in the future too.

Installation
------------

This library can be installed with composer:

    composer require violuke/vault-php-sdk

Usage
-----

The simple way to use this SDK, is to instantiate the service factory:

```
$sf = new Violuke\Vault\ServiceFactory();
```

Then, a service could be retrieve from this factory:

```
$sys = $sf->get('sys');
```

Finally, make a call on the service

```
echo 'The vault ' . ($sys->sealed() ? 'is' : 'is not') . ' sealed';
```

All services methods follow the same convention:

```
$response = $service->method($mandatoryArgument, $someOptions);
```

* All API mandatory arguments are placed as first;
* All API optional arguments are directly mapped from `$someOptions`;
* Most methods return raw guzzle response, but there are some exceptions (like `$sys->sealed()` from the example above).


Transit Examples
--------

```
// assumes you've registered a new transit source, e.g.
// vault mount -path myTransitPath transit
$serviceFactory = new Violuke\Vault\ServiceFactory(
    $options
);
$transit = $serviceFactory->get("transit");
$transit->setTransitPath("myTransitPath");
// ..etc etc
$transit->encrypt($keyName, $valueString);

```


Examples
--------

The `examples` directory is a pure extract from [jippi](https://github.com/jippi/vault-php-sdk)'s CakePHP 3 app using the SDK - its crude,
but should show the basics of the SDK

Available services
------------------

* `sys`
* `data`
* `auth/token`
* `transit`

License
-------

MIT
