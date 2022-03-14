<?php

/*
 * PHPStorm-Project-Builder with Docker + PHPUnit + Cypress | osWFrame
 * Juergen Schwind <juergen.schwind@jbs-newmedia.de>
 *
 * Select:
 * Debian: Buster with Apache/Bullseye | with Apache
 * PHP: 8.0/8.1 | with xDebug:
 * MariaDB: latest
 *
 * @version 1.0.2
 * @date 2022/03/14
 * @license MIT License
 */

namespace JBSNewMedia\DockerBuilder;

class Builder {

	/**
	 * @var string
	 */
	protected string $project_company='';

	/**
	 * @var string
	 */
	protected string $project_name='';

	/**
	 * @var string
	 */
	protected string $project_author='mr example';

	/**
	 * @var string
	 */
	protected string $project_email='mail@example.com';

	/**
	 * @var int
	 */
	protected int $project_port=1;

	/**
	 * @var string
	 */
	protected string $project_type='app';

	/**
	 * @var string
	 */
	protected string $project_debian='buster';

	/**
	 * @var string
	 */
	protected string $project_php='8.0';

	/**
	 * @var \ZipArchive|null
	 */
	protected ?\ZipArchive $za=null;

	/**
	 * @param string $project_name
	 */
	public function __construct(string $project_company, string $project_name) {
		$this->setProjectCompany($project_company);
		$this->setProjectName($project_name);
	}

	/**
	 * @param string $project_company
	 */
	public function setProjectCompany(string $project_company):void {
		$this->project_company=strtolower($project_company);
	}

	/**
	 * @return string
	 */
	public function getProjectCompany():string {
		return $this->project_company;
	}

	/**
	 * @param string $project_name
	 */
	public function setProjectName(string $project_name):void {
		$this->project_name=strtolower($project_name);
	}

	/**
	 * @return string
	 */
	public function getProjectName():string {
		return $this->project_name;
	}

	/**
	 * @param string $project_author
	 */
	public function setProjectAuthor(string $project_author):void {
		$this->project_author=$project_author;
	}

	/**
	 * @return string
	 */
	public function getProjectAuthor():string {
		return $this->project_author;
	}

	/**
	 * @param string $project_email
	 */
	public function setProjectEmail(string $project_email):void {
		$this->project_email=$project_email;
	}

	/**
	 * @return string
	 */
	public function getProjectEmail():string {
		return $this->project_email;
	}

	/**
	 * @param int $project_port
	 */
	public function setProjectPort(int $project_port):void {
		if (($project_port<0)||($project_port>99)) {
			$project_port=1;
		}
		$this->project_port=$project_port;
	}

	/**
	 * @return int
	 */
	public function getProjectPort():int {
		return $this->project_port;
	}

	/**
	 * @param string $project_type
	 */
	public function setProjectType(string $project_type):void {
		if (!in_array($project_type, ['app', 'lib', 'service', 'framework'])) {
			$project_type='app';
		}
		$this->project_type=$project_type;
	}

	/**
	 * @return string
	 */
	public function getProjectType():string {
		return $this->project_type;
	}

	/**
	 * @param string $project_debian
	 */
	public function setProjectDebian(string $project_debian):void {
		if (!in_array($project_debian, ['bullseye', 'buster'])) {
			$project_debian='buster';
		}
		$this->project_debian=$project_debian;
	}

	/**
	 * @return string
	 */
	public function getProjectDebian():string {
		return $this->project_debian;
	}

	/**
	 * @param string $project_php
	 */
	public function setProjectPhp(string $project_php):void {
		if (!in_array($project_php, ['8.0', '8.1'])) {
			$project_php='8.0';
		}
		$this->project_php=$project_php;
	}

	/**
	 * @return string
	 */
	public function getProjectPhp():string {
		return $this->project_php;
	}

	/**
	 * @param string $filename
	 * @return bool
	 */
	public function createZIP(string $filename=''):bool {
		if ($filename=='') {
			$filename=$this->getProjectCompany().'_'.$this->getProjectName().'.zip';
		}
		$this->za=new \ZipArchive;
		$this->za->open($filename, \ZipArchive::CREATE|\ZipArchive::OVERWRITE);
		$this->addPHPStorm();
		$this->addPHPCode();
		$this->addDocker();
		$this->addPHPConf();
		$this->addPHPUnit();
		$this->addCypress();
		$this->addWinShell();
		$this->addProject();
		$this->za->close();

		return true;
	}

	/**
	 * @return bool
	 */
	protected function addPHPStorm():bool {
		$gitignore=[];
		$gitignore[]='# Default ignored files';
		$gitignore[]='/shelf/';
		$gitignore[]='/workspace.xml';
		$gitignore[]='# Editor-based HTTP Client requests';
		$gitignore[]='/httpRequests/';
		$gitignore[]='# Datasource local storage ignored files';
		$gitignore[]='/dataSources/';
		$gitignore[]='/dataSources.local.xml';
		$this->za->addFromString('.idea/.gitignore', implode("\n", $gitignore));

		$this->za->addFromString('.idea/.name', $this->getProjectName());

		$base_iml=[];
		$base_iml[]='<?xml version="1.0" encoding="UTF-8"?>';
		$base_iml[]='<project version="4">';
		$base_iml[]='  <component name="ProjectModuleManager">';
		$base_iml[]='    <modules>';
		$base_iml[]='      <module fileurl="file://$PROJECT_DIR$/.idea/'.$this->getProjectName().'.iml" filepath="$PROJECT_DIR$/.idea/'.$this->getProjectName().'.iml" />';
		$base_iml[]='    </modules>';
		$base_iml[]='  </component>';
		$base_iml[]='</project>';
		$this->za->addFromString('.idea/modules.xml', implode("\n", $base_iml));

		$base_iml=[];
		$base_iml[]='<?xml version="1.0" encoding="UTF-8"?>';
		$base_iml[]='<module type="WEB_MODULE" version="4">';
		$base_iml[]='  <component name="NewModuleRootManager">';
		$base_iml[]='    <content url="file://$MODULE_DIR$" />';
		$base_iml[]='    <orderEntry type="inheritedJdk" />';
		$base_iml[]='    <orderEntry type="sourceFolder" forTests="false" />';
		$base_iml[]='  </component>';
		$base_iml[]='</module>';
		$this->za->addFromString('.idea/'.$this->getProjectName().'.iml', implode("\n", $base_iml));

		$base_iml=[];
		$base_iml[]='<?xml version="1.0" encoding="UTF-8"?>';
		$base_iml[]='<project version="4">';
		#$base_iml[]='  <component name="PhpInterpreters">';
		#$base_iml[]='    <interpreters>';
		#$base_iml[]='      <interpreter id="e8b24920-f88f-4538-8d6f-ce185fdaa3db" name="'.$this->getProjectName().'-test_web:latest" home="docker://DATA" debugger_id="php.debugger.XDebug">';
		#$base_iml[]='        <remote_data INTERPRETER_PATH="php" HELPERS_PATH="/opt/.phpstorm_helpers" INITIALIZED="false" VALID="true" RUN_AS_ROOT_VIA_SUDO="false" DOCKER_ACCOUNT_NAME="Docker" DOCKER_IMAGE_NAME="'.$this->getProjectName().'-test_web:latest" DOCKER_REMOTE_PROJECT_PATH="/opt/project" />';
		#$base_iml[]='      </interpreter>';
		#$base_iml[]='    </interpreters>';
		#$base_iml[]='  </component>';
		#$base_iml[]='  <component name="PhpInterpretersPhpInfoCache">';
		#$base_iml[]='    <phpInfoCache>';
		#$base_iml[]='      <interpreter name="'.$this->getProjectName().'-test_web:latest">';
		#$base_iml[]='        <phpinfo binary_type="PHP" php_cli="/usr/bin/php'.$this->getProjectPhp().'" path_separator=":" version="'.$this->getProjectPhp().'">';
		#$base_iml[]='          <additional_php_ini>/etc/php/'.$this->getProjectPhp().'/cli/conf.d/10-mysqlnd.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/10-opcache.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/10-pdo.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/15-xml.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-bcmath.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-calendar.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-ctype.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-curl.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-dom.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-exif.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-ffi.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-fileinfo.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-ftp.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-gd.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-gettext.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-iconv.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-intl.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-mbstring.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-mysqli.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-pdo_mysql.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-pdo_sqlite.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-phar.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-posix.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-readline.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-shmop.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-simplexml.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-sockets.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-sqlite3.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-sysvmsg.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-sysvsem.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-sysvshm.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-tokenizer.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-xdebug.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-xmlreader.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-xmlwriter.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-xsl.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/20-zip.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/30-oswdocker.ini, /etc/php/'.$this->getProjectPhp().'/cli/conf.d/40-oswdocker-custom.ini</additional_php_ini>';
		#$base_iml[]='          <configuration_file>/etc/php/'.$this->getProjectPhp().'/cli/php.ini</configuration_file>';
		#$base_iml[]='          <configuration_options>';
		#$base_iml[]='            <configuration_option name="include_path" value=".:/usr/share/php" />';
		#$base_iml[]='          </configuration_options>';
		#$base_iml[]='          <debuggers>';
		#$base_iml[]='            <debugger_info debugger="xdebug" debugger_version="3.1.2">';
		#$base_iml[]='              <debug_extensions />';
		#$base_iml[]='            </debugger_info>';
		#$base_iml[]='          </debuggers>';
		#$base_iml[]='          <loaded_extensions>';
		#$base_iml[]='            <extension name="Core" />';
		#$base_iml[]='            <extension name="FFI" />';
		#$base_iml[]='            <extension name="PDO" />';
		#$base_iml[]='            <extension name="Phar" />';
		#$base_iml[]='            <extension name="Reflection" />';
		#$base_iml[]='            <extension name="SPL" />';
		#$base_iml[]='            <extension name="SimpleXML" />';
		#$base_iml[]='            <extension name="Zend OPcache" />';
		#$base_iml[]='            <extension name="bcmath" />';
		#$base_iml[]='            <extension name="calendar" />';
		#$base_iml[]='            <extension name="ctype" />';
		#$base_iml[]='            <extension name="curl" />';
		#$base_iml[]='            <extension name="date" />';
		#$base_iml[]='            <extension name="dom" />';
		#$base_iml[]='            <extension name="exif" />';
		#$base_iml[]='            <extension name="fileinfo" />';
		#$base_iml[]='            <extension name="filter" />';
		#$base_iml[]='            <extension name="ftp" />';
		#$base_iml[]='            <extension name="gd" />';
		#$base_iml[]='            <extension name="gettext" />';
		#$base_iml[]='            <extension name="hash" />';
		#$base_iml[]='            <extension name="iconv" />';
		#$base_iml[]='            <extension name="intl" />';
		#$base_iml[]='            <extension name="json" />';
		#$base_iml[]='            <extension name="libxml" />';
		#$base_iml[]='            <extension name="mbstring" />';
		#$base_iml[]='            <extension name="mysqli" />';
		#$base_iml[]='            <extension name="mysqlnd" />';
		#$base_iml[]='            <extension name="openssl" />';
		#$base_iml[]='            <extension name="pcntl" />';
		#$base_iml[]='            <extension name="pcre" />';
		#$base_iml[]='            <extension name="pdo_mysql" />';
		#$base_iml[]='            <extension name="pdo_sqlite" />';
		#$base_iml[]='            <extension name="posix" />';
		#$base_iml[]='            <extension name="readline" />';
		#$base_iml[]='            <extension name="session" />';
		#$base_iml[]='            <extension name="shmop" />';
		#$base_iml[]='            <extension name="sockets" />';
		#$base_iml[]='            <extension name="sodium" />';
		#$base_iml[]='            <extension name="sqlite3" />';
		#$base_iml[]='            <extension name="standard" />';
		#$base_iml[]='            <extension name="sysvmsg" />';
		#$base_iml[]='            <extension name="sysvsem" />';
		#$base_iml[]='            <extension name="sysvshm" />';
		#$base_iml[]='            <extension name="tokenizer" />';
		#$base_iml[]='            <extension name="xdebug" />';
		#$base_iml[]='            <extension name="xml" />';
		#$base_iml[]='            <extension name="xmlreader" />';
		#$base_iml[]='            <extension name="xmlwriter" />';
		#$base_iml[]='            <extension name="xsl" />';
		#$base_iml[]='            <extension name="zip" />';
		#$base_iml[]='            <extension name="zlib" />';
		#$base_iml[]='          </loaded_extensions>';
		#$base_iml[]='        </phpinfo>';
		#$base_iml[]='      </interpreter>';
		#$base_iml[]='    </phpInfoCache>';
		#$base_iml[]='  </component>';
		$base_iml[]='  <component name="PhpProjectSharedConfiguration" php_language_level="'.$this->getProjectPhp().'" />';
		#$base_iml[]='  <component name="PhpUnit">';
		#$base_iml[]='    <phpunit_settings>';
		#$base_iml[]='      <PhpUnitSettings configuration_file_path="$PROJECT_DIR$/phpunit.xml.dist" custom_loader_path="$PROJECT_DIR$/vendor/autoload.php" use_configuration_file="true" />';
		#$base_iml[]='      <phpunit_by_interpreter interpreter_id="e8b24920-f88f-4538-8d6f-ce185fdaa3db" configuration_file_path="/opt/project/phpunit.xml.dist" custom_loader_path="/opt/project/vendor/autoload.php" phpunit_phar_path="" use_configuration_file="true" />';
		#$base_iml[]='    </phpunit_settings>';
		#$base_iml[]='  </component>';
		$base_iml[]='</project>';
		$this->za->addFromString('.idea/php.xml', implode("\n", $base_iml));

		/*
		$base_iml=[];
		$base_iml[]='<?xml version="1.0" encoding="UTF-8"?>';
		$base_iml[]='<project version="4">';
		$base_iml[]='  <component name="PhpDockerContainerSettings">';
		$base_iml[]='    <list>';
		$base_iml[]='      <map>';
		$base_iml[]='        <entry key="e8b24920-f88f-4538-8d6f-ce185fdaa3db">';
		$base_iml[]='          <value>';
		$base_iml[]='            <DockerContainerSettings>';
		$base_iml[]='              <option name="version" value="1" />';
		$base_iml[]='              <option name="volumeBindings">';
		$base_iml[]='                <list>';
		$base_iml[]='                  <DockerVolumeBindingImpl>';
		$base_iml[]='                    <option name="containerPath" value="/opt/project" />';
		$base_iml[]='                    <option name="hostPath" value="$PROJECT_DIR$" />';
		$base_iml[]='                  </DockerVolumeBindingImpl>';
		$base_iml[]='                </list>';
		$base_iml[]='              </option>';
		$base_iml[]='            </DockerContainerSettings>';
		$base_iml[]='          </value>';
		$base_iml[]='        </entry>';
		$base_iml[]='      </map>';
		$base_iml[]='    </list>';
		$base_iml[]='  </component>';
		$base_iml[]='</project>';
		$this->za->addFromString('.idea/php-docker-settings.xml', implode("\n", $base_iml));
		*/

		/*
		$base_iml=[];
		$base_iml[]='<?xml version="1.0" encoding="UTF-8"?>';
		$base_iml[]='<project version="4">';
		$base_iml[]='  <component name="PhpTestFrameworkVersionCache">';
		$base_iml[]='    <tools_cache>';
		$base_iml[]='      <tool tool_name="PHPUnit">';
		$base_iml[]='        <cache>';
		$base_iml[]='          <versions>';
		$base_iml[]='            <info id="interpreter-e8b24920-f88f-4538-8d6f-ce185fdaa3db" version="9.5.13" />';
		$base_iml[]='          </versions>';
		$base_iml[]='        </cache>';
		$base_iml[]='      </tool>';
		$base_iml[]='    </tools_cache>';
		$base_iml[]='  </component>';
		$base_iml[]='</project>';
		$this->za->addFromString('.idea/php-test-framework.xml', implode("\n", $base_iml));
		*/

		return true;
	}

	/**
	 * @return bool
	 */
	protected function addPHPCode():bool {
		if ($this->getProjectType()=='app') {
			$this->za->addFromString($this->getProjectType().'/index.php', file_get_contents('http://oswframe.com/installer'));
		}

		#https://github.com/vrana/adminer/releases/download/v4.8.1/adminer-4.8.1.php

		$base_file=[];
		$base_file[]='<?php';
		$base_file[]='';
		$base_file[]='phpinfo();';
		$base_file[]='';
		$base_file[]='?>';
		$this->za->addFromString($this->getProjectType().'/phpinfo.php', implode("\n", $base_file));

		$base_file=[];
		$base_file[]='<?php';
		$base_file[]='';
		$base_file[]='xdebug_info();';
		$base_file[]='';
		$base_file[]='?>';
		$this->za->addFromString($this->getProjectType().'/xdebuginfo.php', implode("\n", $base_file));

		return true;
	}

	/**
	 * @return bool
	 */
	protected function addDocker():bool {
		for ($i=$this->getProjectPort(); $i<=$this->getProjectPort()+1; $i++) {
			if ($i==$this->getProjectPort()) {
				$addon='';
			}
			if ($i==($this->getProjectPort()+1)) {
				$addon='-test';
			}
			$base_file=[];
			$base_file[]='version: \'3.8\'';
			$base_file[]='services:';
			$base_file[]='  '.$this->getProjectType().':';
			$base_file[]='    container_name: '.$this->getProjectName().'-'.$this->getProjectType().$addon;
			$base_file[]='    build: .';
			$base_file[]='    ports:';
			$base_file[]='      - "89'.sprintf('%02d', $i).':80"';
			$base_file[]='    volumes:';
			$base_file[]='      - type: bind';
			$base_file[]='        source: ./../..';
			$base_file[]='        target: /var/www/html';
			$base_file[]='    tty: true';
			#if ($i==$this->getProjectPort()) {
			$base_file[]='  database:';
			$base_file[]='    container_name: '.$this->getProjectName().'-database'.$addon;
			$base_file[]='    image: mariadb';
			$base_file[]='    ports:';
			$base_file[]='      - "87'.sprintf('%02d', $i).':3306"';
			$base_file[]='    environment:';
			$base_file[]='      MYSQL_ROOT_PASSWORD: mypassword';
			$base_file[]='      MYSQL_DATABASE: mydatabase';
			$base_file[]='      MYSQL_USER: myuser';
			$base_file[]='      MYSQL_PASSWORD: mypassword';
			$base_file[]='      MYSQL_INITDB_SKIP_TZINFO: 1';
			$base_file[]='    volumes:';
			$base_file[]='      - type: bind';
			$base_file[]='        source: ./../../backup/'.$this->getProjectName().$addon;
			$base_file[]='        target: /backup';
			$base_file[]='    command: --sql_mode=ONLY_FULL_GROUP_BY,NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
			$base_file[]='  adminer:';
			$base_file[]='    container_name: '.$this->getProjectName().'-adminer'.$addon;
			$base_file[]='    image: adminer';
			$base_file[]='    ports:';
			$base_file[]='      - 88'.sprintf('%02d', $i).':8080';
			#}
			$this->za->addFromString('docker/'.$this->getProjectName().$addon.'/docker-compose.yml', implode("\n", $base_file));

			$base_file=[];
			$base_file[]='FROM debian:'.$this->getProjectDebian();
			$base_file[]='';
			$base_file[]='LABEL maintainer="js@jbs-newmedia.de"';
			$base_file[]='LABEL description="Debian / Apache / PHP / Xdebug"';
			$base_file[]='';
			$base_file[]='#apt';
			$base_file[]='RUN apt update';
			$base_file[]='RUN apt -y upgrade';
			$base_file[]='';
			$base_file[]='#composer';
			$base_file[]='RUN apt -y install composer';
			$base_file[]='';
			$base_file[]='#apache2';
			$base_file[]='RUN apt -y install apache2';
			$base_file[]='RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf';
			$base_file[]='ENV APACHE_DOCUMENT_ROOT=/var/www/html/'.$this->getProjectType();
			$base_file[]='RUN sed -ri -e \'s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g\' /etc/apache2/sites-available/*.conf';
			$base_file[]='';
			$base_file[]='#php'.$this->getProjectPhp();
			$base_file[]='RUN apt -y install wget lsb-release apt-transport-https ca-certificates';
			$base_file[]='RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg';
			$base_file[]='RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list';
			$base_file[]='RUN apt update';
			$base_file[]='RUN apt -y upgrade';
			$base_file[]='RUN apt -y install php'.$this->getProjectPhp().'';
			$base_file[]='RUN apt -y install libapache2-mod-php'.$this->getProjectPhp().' php'.$this->getProjectPhp().'-bcmath php'.$this->getProjectPhp().'-gd php'.$this->getProjectPhp().'-sqlite3 php'.$this->getProjectPhp().'-mysqli php'.$this->getProjectPhp().'-curl php'.$this->getProjectPhp().'-xml php'.$this->getProjectPhp().'-mbstring php'.$this->getProjectPhp().'-zip php'.$this->getProjectPhp().'-intl php'.$this->getProjectPhp().'-xdebug mcrypt nano';
			$base_file[]='RUN apt -y autoremove';
			$base_file[]='RUN cd /etc/php/'.$this->getProjectPhp().'/apache2/conf.d/; ln -s /var/www/html/php-conf/'.$this->getProjectName().$addon.'/30-oswdocker.ini 30-oswdocker.ini';
			$base_file[]='RUN cd /etc/php/'.$this->getProjectPhp().'/apache2/conf.d/; ln -s /var/www/html/php-conf/'.$this->getProjectName().$addon.'/40-oswdocker-custom.ini 40-oswdocker-custom.ini';
			$base_file[]='RUN cd /etc/php/'.$this->getProjectPhp().'/cli/conf.d/; ln -s /var/www/html/php-conf/'.$this->getProjectName().$addon.'/30-oswdocker.ini 30-oswdocker.ini';
			$base_file[]='RUN cd /etc/php/'.$this->getProjectPhp().'/cli/conf.d/; ln -s /var/www/html/php-conf/'.$this->getProjectName().$addon.'/40-oswdocker-custom.ini 40-oswdocker-custom.ini';
			$base_file[]='';

			#if ($i==($this->getProjectPort()+1)) {
			#$base_file[]='#mariadb';
			#$base_file[]='RUN apt -y install mariadb-server';
			#$base_file[]='RUN service mariadb start \\';
			#$base_file[]='&& mariadb -u root -e "CREATE DATABASE mydatabase" \\';
			#$base_file[]='&& mariadb -u root -e "CREATE USER \'myuser\'@localhost IDENTIFIED BY \'mypassword\'" \\';
			#$base_file[]='&& mariadb -u root -e "GRANT ALL PRIVILEGES ON mydatabase.* TO \'myuser\'@localhost" \\';
			#$base_file[]='&& mariadb -u root -e "ALTER USER \'root\'@localhost IDENTIFIED BY \'mypassword\'" \\';
			#$base_file[]='&& mariadb -u root -pmypassword -e "FLUSH PRIVILEGES"';
			#$base_file[]='';
			#}

			$base_file[]='#environment';
			$base_file[]='RUN cd /; ln -s /var/www/html/backup/'.$this->getProjectName().$addon.'/ backup';
			$base_file[]='RUN cd /var/www/html/'.$this->getProjectType().'/; ln -s /var/www/html/vendor/ vendor';
			$base_file[]='RUN apt -y install locales';
			$base_file[]='RUN sed -i \'/en_US.UTF-8/s/^# //g\' /etc/locale.gen';
			$base_file[]='RUN sed -i \'/de_DE.UTF-8/s/^# //g\' /etc/locale.gen';
			$base_file[]='RUN locale-gen';
			$base_file[]='RUN a2enmod rewrite';
			$base_file[]='RUN sed -i \'/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/\' /etc/apache2/apache2.conf';
			$base_file[]='RUN chown -R www-data:www-data /var/www ';
			$base_file[]='RUN find /var/www -type d -exec chmod 775 {} +';
			$base_file[]='RUN find /var/www -type f -exec chmod 664 {} +';
			$base_file[]='EXPOSE 80';
			$base_file[]='CMD ["/usr/sbin/apache2ctl","-DFOREGROUND"]';
			$base_file[]='';
			$base_file[]='#restart apache';
			$base_file[]='RUN apache2ctl restart';
			$this->za->addFromString('docker/'.$this->getProjectName().$addon.'/Dockerfile', implode("\n", $base_file));
		}

		return true;
	}

	/**
	 * @return bool
	 */
	protected function addPHPConf():bool {
		$base_file=[];
		$base_file[]=';;;;;;;;;;;;;;;;;;;;;;';
		$base_file[]='; osWDocker settings ;';
		$base_file[]=';;;;;;;;;;;;;;;;;;;;;;';
		$base_file[]='';
		$base_file[]='error_reporting = E_ALL';
		$base_file[]='display_errors = On';
		$base_file[]='zlib.output_compression = Off';
		$base_file[]='memory_limit = 256M';
		$base_file[]='max_execution_time = 120';
		$base_file[]='max_input_time = 120';
		$base_file[]='post_max_size = 32M';
		$base_file[]='upload_max_filesize = 8M';
		$base_file[]='opcache.revalidate_freq = 0';
		$base_file[]='xdebug.mode=debug';
		$base_file[]='xdebug.client_host=host.docker.internal';
		$base_file[]='xdebug.client_port=9003';
		$base_file[]='xdebug.idekey=docker';
		$this->za->addFromString('php-conf/'.$this->getProjectName().'/30-oswdocker.ini', implode("\n", $base_file));
		$this->za->addFromString('php-conf/'.$this->getProjectName().'-test/30-oswdocker.ini', implode("\n", $base_file));

		$base_file=[];
		$base_file[]=';;;;;;;;;;;;;;;;;;;';
		$base_file[]='; Custom settings ;';
		$base_file[]=';;;;;;;;;;;;;;;;;;;';
		$base_file[]='';
		$this->za->addFromString('php-conf/'.$this->getProjectName().'/40-oswdocker-custom.ini', implode("\n", $base_file));
		$this->za->addFromString('php-conf/'.$this->getProjectName().'-test/40-oswdocker-custom.ini', implode("\n", $base_file));

		return true;
	}

	/**
	 * @return bool
	 */
	protected function addPHPUnit():bool {
		$base_file=[];
		$base_file[]='<?xml version="1.0" encoding="UTF-8"?>';
		$base_file[]='';
		$base_file[]='<!-- https://phpunit.readthedocs.io/en/latest/configuration.html -->';
		$base_file[]='<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
		$base_file[]='         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"';
		$base_file[]='         colors="true"';
		$base_file[]='         bootstrap="/var/www/html/tests/bootstrap.php"';
		$base_file[]='>';
		$base_file[]='    <php>';
		$base_file[]='        <ini name="error_reporting" value="-1"/>';
		$base_file[]='    </php>';
		$base_file[]='    <testsuites>';
		$base_file[]='        <testsuite name="Project Test Suite">';
		$base_file[]='            <directory>tests</directory>';
		$base_file[]='        </testsuite>';
		$base_file[]='    </testsuites>';
		$base_file[]='</phpunit>';
		$this->za->addFromString('phpunit.xml.dist', implode("\n", $base_file));

		$base_file=[];
		$base_file[]='<?php';
		$base_file[]='declare(strict_types=1);';
		$base_file[]='require dirname(__DIR__).\'/vendor/autoload.php\';';
		if ($this->getProjectType()=='app') {
			$base_file[]='require dirname(__DIR__) . \'/app/frame/namespaces/osWFrame/Autoload.php\';';
			$base_file[]='require dirname(__DIR__) . \'/app/frame/namespaces/osWFrame/Functions.php\';';
		}
		$base_file[]='?>';
		$this->za->addFromString('tests/bootstrap.php', implode("\n", $base_file));

		$this->za->addFromString('tests/Tests/dummy.txt', 'Place for phpunit tests');

		return true;
	}

	/**
	 * @return bool
	 */
	protected function addCypress():bool {
		$base_file=[];
		$base_file[]='{';
		$base_file[]='  "viewportWidth": 1920,';
		$base_file[]='  "viewportHeight": 1024,';
		$base_file[]='  "videosFolder": "../../var/e2e/videos",';
		$base_file[]='  "screenshotsFolder": "../../var/e2e/screenshots"';
		$base_file[]='}';
		$this->za->addFromString('tests/e2e/cypress/cypress.json', implode("\n", $base_file));

		$this->za->addFromString('tests/e2e/cypress/integration/dummy.txt', 'Place for cypress tests');

		return true;
	}

	/**
	 * @return bool
	 */
	protected function addWinShell():bool {
		for ($i=$this->getProjectPort(); $i<=$this->getProjectPort()+1; $i++) {
			if ($i==$this->getProjectPort()) {
				$addon='';
				$services=[$this->getProjectType(), 'database', 'adminer'];
				$db_service='database';
			}
			if ($i==($this->getProjectPort()+1)) {
				$addon='-test';
				#$services=[$this->getProjectType()];
				#$db_service=$this->getProjectType();
				$services=[$this->getProjectType(), 'database', 'adminer'];
				$db_service='database';
			}
			foreach ($services as $service) {
				$base_file=[];
				$base_file[]='@echo off';
				$base_file[]='setlocal';
				$base_file[]=':PROMPT';
				$base_file[]='SET /P AREYOUSURE=Rebuild '.$service.' container. Are you sure (y/[n])?';
				$base_file[]='IF /I "%AREYOUSURE%" NEQ "y" GOTO END';
				$base_file[]='';
				$base_file[]='docker-compose -f ../../docker/'.$this->getProjectName().$addon.'/docker-compose.yml -p "'.$this->getProjectName().$addon.'" up -d --no-deps --build '.$service;
				$base_file[]='';
				$base_file[]=':END';
				$base_file[]='endlocal';
				$this->za->addFromString('win/'.$this->getProjectName().$addon.'/oswdocker-build-'.$service.'.bat', implode("\n", $base_file));

				$base_file=[];
				$base_file[]='docker-compose -f ../../docker/'.$this->getProjectName().$addon.'/docker-compose.yml -p "'.$this->getProjectName().$addon.'" exec '.$service.' /bin/bash';
				$this->za->addFromString('win/'.$this->getProjectName().$addon.'/oswdocker-ssh-'.$service.'.bat', implode("\n", $base_file));
			}

			$base_file=[];
			$base_file[]='@echo off';
			$base_file[]='setlocal';
			$base_file[]=':PROMPT';
			$base_file[]='SET /P AREYOUSURE=Export database. Are you sure (y/[n])?';
			$base_file[]='IF /I "%AREYOUSURE%" NEQ "y" GOTO END';
			$base_file[]='';
			$base_file[]='docker-compose -f ../../docker/'.$this->getProjectName().$addon.'/docker-compose.yml -p "'.$this->getProjectName().$addon.'" exec '.$db_service.' bash -c "mysqldump -u myuser -pmypassword mydatabase > /backup/mysql.sql"';
			$base_file[]='';
			$base_file[]=':END';
			$base_file[]='endlocal';
			$this->za->addFromString('win/'.$this->getProjectName().$addon.'/oswdocker-ssh-database-export.bat', implode("\n", $base_file));

			$base_file=[];
			$base_file[]='@echo off';
			$base_file[]='setlocal';
			$base_file[]=':PROMPT';
			$base_file[]='SET /P AREYOUSURE=Import database. Are you sure (y/[n])?';
			$base_file[]='IF /I "%AREYOUSURE%" NEQ "y" GOTO END';
			$base_file[]='';
			$base_file[]='docker-compose -f ../../docker/'.$this->getProjectName().$addon.'/docker-compose.yml -p "'.$this->getProjectName().$addon.'" exec '.$db_service.' bash -c "mysql -u myuser -pmypassword mydatabase < /backup/mysql.sql"';
			$base_file[]='';
			$base_file[]=':END';
			$base_file[]='endlocal';
			$this->za->addFromString('win/'.$this->getProjectName().$addon.'/oswdocker-ssh-database-import.bat', implode("\n", $base_file));

			$base_file=[];
			$base_file[]='@echo off';
			$base_file[]='setlocal';
			$base_file[]=':PROMPT';
			$base_file[]='SET /P AREYOUSURE=Export files. Are you sure (y/[n])?';
			$base_file[]='IF /I "%AREYOUSURE%" NEQ "y" GOTO END';
			$base_file[]='';
			$base_file[]='docker-compose -f ../../docker/'.$this->getProjectName().$addon.'/docker-compose.yml -p "'.$this->getProjectName().$addon.'" exec '.$this->getProjectType().' bash -c "cd /var/www/html/; tar --exclude=\'./vendor\' -czf /backup/files.tar.gz '.$this->getProjectType().'"';
			$base_file[]='';
			$base_file[]=':END';
			$base_file[]='endlocal';
			$this->za->addFromString('win/'.$this->getProjectName().$addon.'/oswdocker-ssh-'.$this->getProjectType().'-export.bat', implode("\n", $base_file));

			$base_file=[];
			$base_file[]='@echo off';
			$base_file[]='setlocal';
			$base_file[]=':PROMPT';
			$base_file[]='SET /P AREYOUSURE=Import files. Are you sure (y/[n])?';
			$base_file[]='IF /I "%AREYOUSURE%" NEQ "y" GOTO END';
			$base_file[]='';
			$base_file[]='docker-compose -f ../../docker/'.$this->getProjectName().$addon.'/docker-compose.yml -p "'.$this->getProjectName().$addon.'" exec '.$this->getProjectType().' bash -c "cd /var/www/; tar -xf /backup/files.tar.gz"';
			$base_file[]='';
			$base_file[]=':END';
			$base_file[]='endlocal';
			$this->za->addFromString('win/'.$this->getProjectName().$addon.'/oswdocker-ssh-'.$this->getProjectType().'-import.bat', implode("\n", $base_file));

			$base_file=[];
			$base_file[]='docker-compose -f ../../docker/'.$this->getProjectName().$addon.'/docker-compose.yml -p "'.$this->getProjectName().$addon.'" exec '.$this->getProjectType().' bash -c "cd /var/www/html/'.$this->getProjectType().'/; ln -s /var/www/html/vendor/ vendor"';
			$this->za->addFromString('win/'.$this->getProjectName().$addon.'/oswdocker-ssh-'.$this->getProjectType().'-vendor.bat', implode("\n", $base_file));

			$base_file=[];
			$base_file[]='docker-compose -f ../../docker/'.$this->getProjectName().$addon.'/docker-compose.yml -p "'.$this->getProjectName().$addon.'" up -d';
			$this->za->addFromString('win/'.$this->getProjectName().$addon.'/oswdocker-start.bat', implode("\n", $base_file));

			$base_file=[];
			$base_file[]='docker-compose -f ../../docker/'.$this->getProjectName().$addon.'/docker-compose.yml -p "'.$this->getProjectName().$addon.'" down';
			$this->za->addFromString('win/'.$this->getProjectName().$addon.'/oswdocker-stop.bat', implode("\n", $base_file));
		}

		return true;
	}

	/**
	 * @return bool
	 */
	protected function addProject():bool {
		$base_file=[];
		$base_file[]='*.css text eol=lf';
		$base_file[]='*.scss text eol=lf';
		$base_file[]='*.htaccess text eol=lf';
		$base_file[]='*.htm text eol=lf';
		$base_file[]='*.html text eol=lf';
		$base_file[]='*.js text eol=lf';
		$base_file[]='*.json text eol=lf';
		$base_file[]='*.map text eol=lf';
		$base_file[]='*.md text eol=lf';
		$base_file[]='*.php text eol=lf';
		$base_file[]='*.profile text eol=lf';
		$base_file[]='*.script text eol=lf';
		$base_file[]='*.sh text eol=lf';
		$base_file[]='*.svg text eol=lf';
		$base_file[]='*.txt text eol=lf';
		$base_file[]='*.xml text eol=lf';
		$base_file[]='*.yml text eol=lf';
		$this->za->addFromString('.gitattributes', implode("\n", $base_file));

		$base_file=[];
		if ($this->getProjectType()=='app') {
			$base_file[]='/app/.caches/';
			$base_file[]='/app/.locks/';
			$base_file[]='/app/.logs/';
			$base_file[]='/app/.sessions/';
			$base_file[]='/app/data/.tmp/';
			$base_file[]='/app/data/resources/';
			$base_file[]='/app/oswtools/.caches/';
			$base_file[]='/app/oswtools/.locks/';
			$base_file[]='/app/oswtools/.logs/';
			$base_file[]='/app/oswtools/.sessions/';
			$base_file[]='/app/oswtools/data/.tmp/';
		}
		$base_file[]='/backup/';
		$base_file[]='/var/';
		$base_file[]='/vendor/';
		$this->za->addFromString('.gitignore', implode("\n", $base_file));

		$base_file=[];
		$base_file[]='{';
		$base_file[]='"name": "'.$this->getProjectCompany().'/'.$this->getProjectName().'",';
		$base_file[]='  "type": "'.$this->getProjectType().'",';
		$base_file[]='  "license": "MIT",';
		$base_file[]='  "authors": [';
		$base_file[]='    {';
		$base_file[]='      "name": "'.$this->getProjectAuthor().'",';
		$base_file[]='      "email": "'.$this->getProjectEmail().'"';
		$base_file[]='    }';
		$base_file[]='  ],';
		$base_file[]='  "minimum-stability": "dev",';
		$base_file[]='  "require-dev": {';
		$base_file[]='    "phpunit/phpunit": "^9"';
		$base_file[]='  }';
		$base_file[]='}';
		$this->za->addFromString('composer.json', implode("\n", $base_file));

		$this->za->addFromString('vendor/dummy.txt', 'Place for vendors');

		return true;
	}

}

?>