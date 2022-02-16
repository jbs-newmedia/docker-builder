# example

```
require './JBSNewMedia/DockerBuilder/Builder.php';

$Builder=new \JBSNewMedia\DockerBuilder\Builder('company', 'project');
$Builder->setProjectPort(1);
$Builder->setProjectDebian('bullseye');
$Builder->setProjectAuthor('mr example');
$Builder->setProjectEmail('send@example.com');
$Builder->setProjectPhp('8.1');
$Builder->setProjectType('app');
$Builder->createZIP();
```