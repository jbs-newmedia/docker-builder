# PHP example

```
require './JBSNewMedia/DockerBuilder/Builder.php';

 /* 
  * Port
  * $port=1
  * project:8901 - database: 8701 - adminer:8801
  * project-test:8902 - database: 8702 - adminer:8802
  *
  * $port=3
  * project:8903 - database: 8703 - adminer:8803
  * project-test:8904 - database: 8704 - adminer:8804
  * 
  * Debian
  * bullseye or buster
  *
  * Php
  * 8.0, 8.1 or 8.2
  *
  * Type
  * app, lib, service or framework
  */

$Builder=new \JBSNewMedia\DockerBuilder\Builder('company', 'project');
$Builder->setProjectPort(1);
$Builder->setProjectDebian('bullseye');
$Builder->setProjectAuthor('mr example');
$Builder->setProjectEmail('send@example.com');
$Builder->setProjectPhp('8.1');
$Builder->setProjectType('app');
$Builder->createZIP();
```

# PHPStorm plugins

[cypress-support](https://plugins.jetbrains.com/plugin/13819-cypress-support)

# PHPStorm/Project settings

### Docker
- navigate to /docker/**{project-name}**/ > docker-compose.yml > *mouse right click* > Run
- navigate to /docker/**{project-name}**-test/ > docker-compose.yml > *mouse right click* > Run

### PHP Interpreter
- File > Settings > PHP > CLI Interpreter > + > From Docker > Docker Compose
- Configuration files > /docker/**{project}-name**-test/docker-compose.yml
- Service: app
- Lifecycle: Connect to existing container

### Composer
- File > Settings > PHP > Composer > Path to composer.json -> /var/www/html/composer.json
- navigate to / > composer.json > *mouse right click* > Init Composer
- Remote Interpreter
- Select PHP Interpreter
- navigate to / > composer.json > *mouse right click* > Install

### Environment
- navigate to /win/**{project-name}**/ > oswdocker-ssh-**{project-type}**-env.bat > *mouse right click* > Run

### PHPUnit
- File > Settings > PHP > Debug > Test Frameworks > Delete Local
- File > Settings > PHP > Debug > Test Frameworks > + > PHPUnit by Remote Interpreter
- Select PHP Interpreter


