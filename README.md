# PHP Example

```
require './JBSNewMedia/DockerBuilder/Builder.php';

$Builder=new \JBSNewMedia\DockerBuilder\Builder('company', 'project');
$Builder->setProjectPort(1);
$Builder->setProjectDebian('bullseye'); /* bullseye|buster */
$Builder->setProjectAuthor('mr example');
$Builder->setProjectEmail('send@example.com');
$Builder->setProjectPhp('8.1');  /* 8.0|8.1 */
$Builder->setProjectType('app');  /* app|lib */
$Builder->createZIP();
```

# PHPStorm settings

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

### PHPUnit
- File > Settings > PHP > Debug > Test Frameworks > Delete Local
- File > Settings > PHP > Debug > Test Frameworks > + > PHPUnit by Remote Interpreter
- Select PHP Interpreter


