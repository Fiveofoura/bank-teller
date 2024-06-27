## Prerequisites

This app has been created using Laravel 11. Download the code and set up your web server to point to the 'Public' directory as document root, install the database and change the .env config file to connect your database. Please see the Laravel prerequisistes for PHP and your web server.

## The Bank Teller App

The home page will allow you to search on the customer ID. All accounts belonging to the customer and associated transactions will be displayed. You can update the address of the customer and add transactions on this page.

The site is mobile friendly.

## Security

The site use CSRF tokens and form field validation. The query builder in Laravel has its own built in protection as does the blade template variable output, but where it wasn't possible to use this, htmlentities has been used with ENT_QUOTES. A debounce mechanism was created to protect from duplicate form submission using a UUID in a hidden form field and form field validation has been implemented using regular expressions and white lists.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

The bank teller app is free to distribute and use but no warranty is provided - use at your own risk.
