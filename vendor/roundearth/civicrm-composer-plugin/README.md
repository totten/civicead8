# Composer plugin for Drupal projects with CiviCRM

This Composer plugin can be added to a fully 'composerized' Drupal 8 site
in order to easily install CiviCRM on it.

This will ONLY work on a Drupal 8 site based on 
[drupal-composer/drupal-project](https://github.com/drupal-composer/drupal-project),
so if you have an older Drupal 8 site that's not, you'll need to convert it
before using this plugin.

## Usage

You need a couple of dependencies first:

- [Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx) (at a relatively recent version)
- [Bower](https://bower.io/#install-bower)
- [Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)

*Make sure that you have a recent version of Composer! A couple of people have
tried to use this plugin with older versions and have experienced issues.*

After that, you can run this command:

```
composer require roundearth/civicrm-composer-plugin civicrm/civicrm-drupal-8
```
## Installing CiviCRM

See
[the Composer project template](https://gitlab.com/roundearth/drupal-civicrm-project#installing-civicrm)
for some tips on how to install CiviCRM once the code has been added via
composer.

## How does it work?

This is the file that does all the real work:

[https://gitlab.com/roundearth/civicrm-composer-plugin/blob/master/src/Handler.php](https://gitlab.com/roundearth/civicrm-composer-plugin/blob/master/src/Handler.php)

## References

- [https://www.mydropwizard.com/blog/better-way-install-civicrm-drupal-8](https://www.mydropwizard.com/blog/better-way-install-civicrm-drupal-8)

