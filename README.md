DEPRECATED as PHP 7.4 is facing dawn
====================================
* superceded by private (not yet public) repo
* PHP 7.4 is about to enter security-fix-only stage
* PHP 8.1 is around the corner


Template for composer packages (PHP 7.4)
========================================

## Setup
  
* Fresh repository & clone
   ```
   # create a new empty repository in github
   NEW_PROJECT_NAME=dom-tools
   git clone https://github.com/ebln/template-php-package-7.4.git ${NEW_PROJECT_NAME} && cd ${NEW_PROJECT_NAME}
   rm -rf .git && git init
   git config --list
   # optionally tweak config before commiting
   nano ./.provision/init.sh
   # adjust parameters in init.sh
   sh   ./.provision/init.sh
   git remote add origin git@github.com-ebln:ebln/${NEW_PROJECT_NAME}.git
   git add -A && git commit -m 'Initial commit'
   git branch -M main
   git push -u origin main
   ```  
  
* Use the template
    ```
    NEW_PROJECT_NAME=FOOBAR; \
    git clone https://github.com/ebln/template-php-package-7.4.git ${NEW_PROJECT_NAME} && cd ${NEW_PROJECT_NAME}
    
    git remote set-url origin git@github.com-ebln:ebln/${NEW_PROJECT_NAME}.git
    git push --set-upstream origin main
    
    nano ./.provision/init.sh
    sh   ./.provision/init.sh
    ```
  
* Configure PhpStorm → CLI Interpreters
  * Server		`Docker`
  * Config… 	`./.provision/docker-compose.yml`
  * Service 	`php`
  * Env. vars. 	`XDEBUG_REMOTE_HOST=172.17.0.1`
  * Lifecycle   `run`
