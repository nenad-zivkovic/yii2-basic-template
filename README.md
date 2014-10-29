yii2-basic-template
===================

Yii2-basic-template is based on yii2-app-basic created by yii2 core developers.
There are several upgrades made to this template.

1. This template comes with almost all features that default yii2-app-advanced has.
2. It has additional features listed at the end of this guide.
3. Application structure has been changed to be 'shared hosting friendly'.

Installation
-------------------
>I am assuming that you know how to: install and use Composer, and install additional packages/drivers that may be needed for you to run everything on your system. In case you are new to all of this, you can check my guides for installing default yii2 application templates, provided by yii2 developers, on Windows 8 and Ubuntu based Linux operating systems, posted on www.freetuts.org.

1. Create database that you are going to use for your application (you can use phpMyAdmin or any
other tool you like).

2. Now open up your console and ```cd``` to your web root directory, 
for example: ``` cd /var/www/sites/ ```

3. Run the Composer ```create-project``` command:

   ``` composer create-project nenad/yii2-basic-template basic ```

4. Now you need to tell your application to use database that you have previously created.
Open up db.php config file in ```basic/_protected/config/db.php``` and adjust your connection credentials.

5. Back to the console. Inside your newly installed application, ```cd``` to the ```_protected``` folder.

7. Execute yii migration command that will install necessary database tables:

   ``` ./yii migrate ``` or if you are on Windows: ``` yii migrate ```

8. Execute _rbac_ controller _init_ action that will populate our rbac tables with default roles and
permissions:

   ``` ./yii rbac/init ``` or if you are on Windows: ``` yii rbac/init ```


You are done, you can start your application in your browser.

> Note: First user that signs up will get 'The Creator' (super admin) role. This is supposed to be you. This role have all possible super powers :) . Every other user that signs up after the first one will get 'member' role. Member is just normal authenticated user. 

Testing
-------------------

If you want to run tests you should create additional database that will be used to store 
your testing data. Usually testing database will have the same structure like the production one.
I am assuming that you have Codeception installed globally, and that you know how to use it.
Here is how you can set up everything easily:

1. Let's say that you have created database called ```basic```. Go create the testing one called ```basic_tests```.

2. Inside your ```db.php``` config file change database you are going to use to ```basic_tests```.

3. Open up your console and ```cd``` to the ```_protected``` folder of your application.

4. Run the migrations again: ``` ./yii migrate ``` ( on Windows it is ```yii migrate```)

5. Run rbac/init again: ``` ./yii rbac/init ``` ( on Windows it is ```yii rbac/init```)

6. Now you can tell your application to use your ```basic``` database again instead of ```basic_tests```.
Adjust your ```db.php``` config file again.

7. Now you are ready to tell Codeception to use ```basic_tests``` database.
   
   Inside: ``` _protected/tests/codeception/config/config.php ``` file tell your ```db``` to use 
```basic_tests``` database.

8. Start your php server inside the root of your application: ``` php -S localhost:8080 ``` 
(if the name of your application is _basic_, then root is ```basic``` folder) 

9. Move to ```_protected/tests``` , run ```codecept build``` and then run your tests.

Directory structure
-------------------

```
_protected
    assets/              contains assets definition
    config/              contains application configurations
    console              contains console commands (controllers and migrations)
    controllers/         contains Web controller classes
    helpers/             contains helper classes
    mail/                contains view files for e-mails
    models/              contains model classes
    rbac/                contains role based access control classes
    runtime/             contains files generated during runtime
    tests/               contains various tests for the basic application
    views/               contains view files for the Web application
    widgets/             contains widgets
assets                   contains application assets generated during runtime
themes                   contains your themes
```
Features
-------------------

- Signup with/without account activation
    - The Creator (super admin) can chose whether or not new users need to activate their account using email account activation system before they can log in.
- Login using email/password or username/password combo.
    - The Creator (super admin) can chose how users will login into system. They can log in either by using their username|password combo or email|password.
- Rbac tables are installed with other migrations when you run ```yii migrate``` command.
    - RbacController's init() action will insert 4 roles and 5 permissions in our rbac tables created by migration.
    - Roles can be easily assigned to users by administrators of the site.
    - Nice example of how to use rbac in your code is given in this application. See: AppController.
- Session data is stored in database out of box.
- The Creator role (super admin) can use system settings that comes with template.
- Theming is supported out of box.
- Administrators and The Creator can update their account.
- Code is heavily commented out.
