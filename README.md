Skeleton of Symfony 4.3
===================
This is the skeleton that I have configured to prevent loss of time in set up the framework for each project.

Installation
-------------
    * sudo chmod -R 777 .
    * composer config --global process-timeout 5000
    * composer install
    * php bin/console do:da:cr
    * composer next-deploy

Script Cache
-------------
You can remove all cache of project and of server ``composer remove-cache``

Error with session
-------------
Clear cache from browser and exec:

    * composer install
    * composer remove-cache
    
Deploy
-------------
Access on the server with, go into project folder and run the following command to put the code into production mode: ``composer next-deploy``

Or if you want to put the code into development mode: ``composer next-deploy-dev``

Script GIT
-------------
If you want to merge the "dev" branch into "master" branch, run the following script: ``composer merge-dev``

Or if you want to merge the "master" branch into "dev" branch, run the following script: ``composer merge-master``

Error with composer `proc_open(): fork failed errors`
-------------
Exec that commands to incrase the swap memory on your VPS
* `/bin/dd if=/dev/zero of=/var/swap.1 bs=1G count=2`
* `/sbin/mkswap /var/swap.1`
* `/sbin/swapon /var/swap.1`
