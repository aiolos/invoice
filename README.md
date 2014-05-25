Invoice
=======

Introduction
------------
This application creates invoices based on the objects of the owner

The application is using Zend Framework 2.

Installation
------------

Using Composer (recommended)
----------------------------
Clone the repository and manually invoke `composer` using the shipped
`composer.phar`:

    cd my/project/dir
    git clone git://github.com/aiolos/invoice.git
    cd invoice
    php composer.phar self-update
    php composer.phar install
    (run doctrine-module to create database tables)

(The `self-update` directive is to ensure you have an up-to-date `composer.phar`
available.)

Todo:
-----
- Add new owners and 'winterstallers'
- Addition of ship size to winterstallers
- Create statistics
- Roles in authentication
- Access for owners to see own powerusage
- More dynamic invoices