Welcome to SMP/Bundle!
===================


Installation:
-------------

**composer.json:**

>    [...]
>   "require" : {
>       [...]
>       "smp/SmpBundle" : "dev-master"
>   },
>   "repositories" : [{
>       "type" : "vcs",
>       "url" : "https://github.com/namzug82/SmpBundle.git"
>   }],
>   [...]


**Do:**

>curl -sS https://getcomposer.org/installer | php
>php composer.phar update smp/SmpBundle


**app/AppKernel:**

>new SmpBundle\SmpSmpBundle(),


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


**parameters.yml**

>parameters:
>    images_directory: '%kernel.root_dir%/../web/uploads/images'
>    database_host: 127.0.0.1
>    database_port: null
>    database_name: 
>    database_user: 
>    database_password: 
>    mailer_transport: 
>    mailer_host: 
>    mailer_user: 
>    mailer_password: 
>    secret: SuperSecretToken
>    s3_bucket: 
>    s3_key: 
>    s3_secret: 
>    s3_region: 
>    s3_version: 
>    sphinx_host: localhost
>    sphinx_port: 9312
>    sphinx_user_entity_index: 'SmpBundle:User'
>    sphinx_material_entity_index: 'SmpBundle:Material'


**composer.json**

>{
>    "extra": {
>        "incenteev-parameters": {
>            "keep-outdated": true
>        }
>    }
>}


**routing.yml**

>frontend:
>    resource: "@SmpBundle/Controller/Frontend"
>    type:     annotation
>
>admin:
>    resource: "@SmpBundle/Controller/Admin"
>    type:     annotation
>
>login_check:
>    pattern: /login_check
>
>logout:
>    pattern: /logout


**security.yml**

>security:
>
>    encoders:
>        SmpBundle\Entity\User: { algorithm: sha512, iterations: 10 }
>
>    providers:
>        users:
>            entity:
>                class: SmpBundle:User 
>                property: email
>
>    firewalls:
>        frontend:
>            pattern: ^/
>            anonymous: ~
>            provider: users
>            form_login:
>                login_path: /login
>                check_path: /login_check
>                default_target_path: home
>            logout:
>                path: /logout
>            remember_me:
>                key: smp1234
>                lifetime: 3600  
>        dev:
>            pattern: ^/(_(profiler|wdt)|css|images|js)/
>            security: false
>
>    access_control:
>        - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
>        - { path: ^/registration, roles: IS_AUTHENTICATED_ANONYMOUSLY }
>        - { path: ^/admin, roles: ROLE_ADMIN }
>        - { path: ^/*, roles: IS_AUTHENTICATED_ANONYMOUSLY }


**services.yml**

>parameters:
>    s3_client.config:
>        bucket:  "%s3_bucket%"
>        key:     "%s3_key%"
>        secret:  "%s3_secret%"
>        region:  "%s3_region%"    
>        version: "%s3_version%"      
>
>services:
>
>    cache:
>        class: Doctrine\Common\Cache\ApcCache
>
>    slugger:
>        class: SmpBundle\Service\Slugger
>
>    predis_client:
>        class: SmpBundle\Service\PredisClient
>
>    s3_client:
>      class: SmpBundle\Service\S3Bucket
>      arguments: ["@service_container", "%s3_client.config%"]
>
>    users_materials_linker:
>      class: SmpBundle\Service\UsersMaterialsLinker
>      arguments: ["@doctrine.orm.entity_manager"]
>
>    material_repo:
>        class: SmpBundle\Repository\MaterialRepository
>
>    user_sphinx_repo:
>        class: SmpBundle\Repository\UserSphinxRepository
>        arguments: ["@service_container"]
>
>    material_sphinx_repo:
>        class: SmpBundle\Repository\MaterialSphinxRepository
>        arguments: ["@service_container"]
>
>    target_user_retriever:
>        class: SmpBundle\Service\TargetUserRetriever
>        arguments: ["@service_container", "@doctrine.orm.entity_manager"]


**Others resources**

Inside the Resources folder you can find a sql file with database sample, same css style sheets, javascripts, twig templates, etc. 
To check this app I recommend you to move all these sources in your app/Resources folder and you must set sphinxsearch and redis in your server.



Also you can find the application repository here: **https://bitbucket.org/namzug82/final-project-mupwar**