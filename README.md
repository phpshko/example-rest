[![Build Status](https://travis-ci.org/phpshko/rest-example.svg?branch=master)](https://travis-ci.org/phpshko/rest-example)

Реализовать REST api метод users, который принимает данные пользователя (имя, email, возраст, пол, фото) и сохраняет в БД, 
предварительно провалидировав все поля. Фото пользователя нужно поместить в очередь на RabbitMQ для последующей обработки.

Написать обработчик, который будет уменьшать исходное фото до максимум 900px по больше стороне и создавать картинку превью 100x100.

Добавить в users метод получения данных пользователя по id.

Запросы к апи доступны только для авторизованных пользователей. Авторизация по JWT токену.
Реализовать все на Yii 2.

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
