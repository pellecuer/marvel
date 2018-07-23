marvel
======

A Symfony project created on July 19, 2018, 3:21 pm.

1/Clone the repository Marvel
git@github.com:pellecuer/marvel.git
2/Install Guzzle library
http://docs.guzzlephp.org/en/stable/
https://github.com/guzzle/guzzle 
Commande Line : php composer.phar require guzzlehttp/guzzle
3/Configure your database parameters in Symfony :
App/config/parameters

See : https://symfony.com/doc/3.4/doctrine.html
2/Create account on https://developer.marvel.com/ 
Récupérer une clé publique et une clé privée.
3/Create hhtp request on Marvel API with examples below :

https://developer.marvel.com/docs
https://developer.marvel.com/documentation/authorization
4/Set Controllers
Controller twentyAction :
Display 20 characters, from index100, sorted by name.  
ControllerGetdetailAction :
Display characters’ detail. 
Add or delete favorite’ characters
