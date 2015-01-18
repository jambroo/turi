Turi
================================

Turi is a photo management application written in PHP 5.4 using the Yii2 Framework. The application will store the photos externally on an Amazon S3 instance and will retrieve them via Amazon's CloudFront service. Database entries will be made for Turi user credentials and management pages for the photos and their galleries.


DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this application template that your Web server supports PHP 5.4.0. Using composer the required jambroo-aws package will be retrievable.


INSTALLATION
------------

...

CONFIGURATION
-------------

### Database

The system comes with sample configuration to connect to an sqlite server. Please see basic/config/db.php.sample and copy it to basic/config/db.php.