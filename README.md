# Seedstars challenge 2

**Full App**

Make an application which stores names and email addresses in a database (SQLite is fine).

a) Has welcome page in http://localhost/
- this page has links to list and create functions

b) Lists all stored names / email address in http://localhost/list

c) Adds a name / email address to the database in http://localhost/add
- should validate input and show errors

**Assumptions**

I used the third-party libraries:
- [AltoRouter](http://altorouter.com/) - a small routing class
- [Plates](http://platesphp.com/) - a native PHP template system
- [Bootstrap](http://getbootstrap.com/) - HTML, CSS, and JS framework

**Instructions**

```
# clone repository
$ git clone https://github.com/soniacurcialeiro/seedstars-challenge-2.git

# change directory
$ cd seedstars-challenge-2

# install composer
$ curl -s https://getcomposer.org/installer | php

# install composer packages
$ php composer.phar install

# run server
$ php -S 0.0.0.0:8080 -t . index.php
```
Open url [http://localhost:8080](http://localhost:8080) on your browser.
