[![Build Status](https://travis-ci.org/phpshko/rest-example.svg?branch=master)](https://travis-ci.org/phpshko/rest-example)

Installation
------------

Up containers, generate local/*.php config, install composer dependency, migrate db and generate user
```
make init

```

Run all tests
```
make run-tests
```


Run bash in container
```
make nginx
make rabbit
make rabbit_test
make db
make db_test
make php
```