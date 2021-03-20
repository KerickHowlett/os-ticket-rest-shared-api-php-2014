# My First Professional Program.

This was the first bit of code I've ever programmed professionally, so please excuse the lack of adherence to best practices and design patterns.

This RESTful API was engineered back in the fall semester of 2014 during my internship, and it performed basic CRUD operations to interface with an open source ticketing application called [OSTICKET](https://osticket.com/). Coded with PHP, it used the cURL extension to send out API REST calls containing JSON data objects to a MySQL database for basic CRUD operations.

For security, it utilized an API key (not included for obvious reasons) as well as an LDAP extension to perform basic authentication.

This API was developed in a [WAMP](https://www.wampserver.com/en/) dev environment, which also came with PhpMyAdmin to host a database schema.

The only file with any actual code in it is [vpua_api.php](https://github.com/KerickHowlett/my-first-professional-program/blob/master/vpua_api.php). The other files are only there for posterity sake.

## Disclaimer
Since this API wasn't used to access any sensitive information, I received explicit permission from the [University of Louisville](https://louisville.edu/)'s Office of Advancement to use this as a part of my portfolio.

This may be taken down at any time upon their request.
