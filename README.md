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


Configuration:
--------------

**config.yml**

># Redis
>snc_redis:
>    # configure predis as client
>    clients:
>        default:
>            type: predis
>            alias: default
>            dsn: redis://localhost
>        doctrine:
>            type: predis
>            alias: doctrine
>            dsn: redis://localhost
>    # configure doctrine caching
>    doctrine:
>        metadata_cache:
>            client: doctrine
>            entity_manager: default
>            document_manager: default
>        result_cache:
>            client: doctrine
>            entity_manager: [default]
>        query_cache:
>            client: doctrine
>            entity_manager: default