# sfOauthServerPlugin

## Introduction

This plugin permits to create easily an authentication for a module or an action. This authentication works both with OAuth 1.0 and 2.0
For instance, it allows to secure an API and control access and permissions of each consumers (applications).

If you find bugs or if you have some suggestions, please contact me.

## Installation

  * Install the plugin (via a package)

        symfony plugin:install sfOauthServerPlugin

  * Activate the plugin in the `config/ProjectConfiguration.class.php`
  
        [php]
        class ProjectConfiguration extends sfProjectConfiguration
        {
          public function setup()
          {
            $this->enablePlugins(array(
              'sfDoctrinePlugin', 
              'sfDoctrineGuardPlugin',
              'sfOauthServerPlugin'
            ));
          }
        }
  * Rebuild your model


  * Enable modules
There are five modules in this plugin : sfOauthAuth, sfOauthApplication,sfOauthDeveloper sfOauthAdmin, sfOauthTest

sfOauthAuth : permits to exchange token and code for the authentication.

sfOauthApplication : has just one action for the moment : authorize
 It is in this action that an user accept or not an application to access to its data.

sfOauthDeveloper : is for developers. You can define developers for an application. For the moment they can change some parameters and see how many people use their application.

sfOauthAdmin : for the backend to manage consumers.

sfOauthTest: examples.


You have to enabled these modules in yours applications.
( In my case, i have three applications :  api where "sfOauthAuth" is enabled, the frontend ("sfOauthApplication" and "sfOauthDeveloper enabled) and the backend with "sfOauthAdmin". )

  * For example :

         Enable the modules sfOauthAuth in "settings.yml" file of api application
          all:
           .settings:
             enabled_modules: [...,sfOauthAuth]

## Usage
Now to secure a module/action, just create a config file "oauth.yml" in the config repertory of the module.
It works exactly like the security.yml

  * This is an example of configuration :

        oauth.yml
        all:
          is_secure : false
        info:
          is_secure : true
        permissions : [ read , write ]

For more information about how write permissions please see here : [http://www.symfony-project.org/jobeet/1_4/Doctrine/en/13#chapter_13_sub_authorization](http://www.symfony-project.org/jobeet/1_4/Doctrine/en/13#chapter_13_sub_authorization)

To fix permissions (or scope) of a consumer, just write them in the field scope of sfOauthServerConsumer with a space between eatch right.
for example : 

  `$consumer->setScope('read write');`


## How it works 


This plugin uses two vendor libraries :

  * **oauth2-php** [http://code.google.com/p/oauth2-php/](http://code.google.com/p/oauth2-php/)
  * **oauth** [http://code.google.com/p/oauth2-php/](http://code.google.com/p/oauth-php/)
  
  
When requests from application are not good they throw exceptions. By default symfony catch them and show an 500 internal error for the production and an error page with many information about exceptions for developmennt.
sfOauthServerPlugin listens exceptions (event : 'application.throw_exception') and if the type (class name) is OAuthException it treats it to show it in the good way and readable for application.
Two formats are avaibles for the display of exceptions ( json and xml) but you can easily add an other by adding a file in /sfOauthServerPlugin/config/error/error.myformat.php


You can find more informations about OAuth authentication steps in the repertory "doc"

## License and credits

This plugin has been developed by Jean-Baptiste Cayrou and is licensed under the MIT license.
