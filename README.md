Wekancode Sample Application Using Symfony 3.4
========================

Modules
--------------
Apart from authentication, other modules use jwt token based authentication.
  * Authentication
    * Register
        * Uses bcrypt algorithm
    * Login

  * User

Test
------------
For functional testing refer tests folder


Requirements
--------------
1) php >= 5.5.9
2) Mysql
3) Symfony 3.4

Documentation
---------------
After running the server, visit http://localhost:8000/api/doc for swagger documentation.

Folder Structure
-----------------
1) Modules are separated into bundles.
2) Form validations classes are grouped in Validators folder.
3) Common class, trait which are shared across bundles are grouped in AppBundle.
