Invoice
=======

Introduction
------------
This application creates invoices based on the information stored in the database tables

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

(The `self-update` directive is to ensure you have an up-to-date `composer.phar`
available.)

Todo:
-----
- Addition of box ownership to owners
- Add 'winterstallers'
- Addition of ship size to winterstallers
- Add parameters per year
- Create invoices from the data
- Create statistics
- Do something with authentication