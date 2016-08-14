Welcome to SMP/Bundle!
===================


Installation:
-------------

**composer.json:**

>    [...]
>   "require" : {
>       [...]
>       "smp/appbundle" : "dev-master"
>   },
>   "repositories" : [{
>       "type" : "vcs",
>       "url" : "https://github.com/namzug82/AppBundle.git"
>   }],
>   [...]


**Do:**

>curl -sS https://getcomposer.org/installer | php
>php composer.phar update smp/appbundle


**app/AppKernel:**

>new Smp\AppBundle\SmpAppBundle(),