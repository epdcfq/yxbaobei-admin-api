<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, yet powerful, providing tools needed for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of any modern web application framework, making it a breeze to get started learning the framework.

If you're not in the mood to read, [Laracasts](https://laracasts.com) contains over 1100 video tutorials on a range of topics including Laravel, modern PHP, unit testing, JavaScript, and more. Boost the skill level of yourself and your entire team by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for helping fund on-going Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell):

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[British Software Development](https://www.britishsoftware.co)**
- [Fragrantica](https://www.fragrantica.com)
- [SOFTonSOFA](https://softonsofa.com/)
- [User10](https://user10.com)
- [Soumettre.fr](https://soumettre.fr/)
- [CodeBrisk](https://codebrisk.com)
- [1Forge](https://1forge.com)
- [TECPRESSO](https://tecpresso.co.jp/)
- [Pulse Storm](http://www.pulsestorm.net/)
- [Runtime Converter](http://runtimeconverter.com/)
- [WebL'Agence](https://weblagence.com/)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# PHP安装

## 升级软件及系统内核
	yum update -y
## 安装必要安装包
	yum -y install gcc && yum -y install gcc-c++ && yum -y install make
## 更新yum源地址，否则无法安装php72
		rpm -Uvh https://dl.fedoraproject.org/pub/epel/7/x86_64/Packages/e/epel-release-7-12.noarch.rpm
		rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm
	或者
		yum install epel-release -y
		rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm
## 清除已安装php
	yum -y remove php*

##　查找插件
	yum list|grep php|grep xml
## 安装 PHP 7.2 所需要的包
	yum -y install mod_php72w.x86_64 php72w-cli.x86_64 php72w-common.x86_64 php72w-mysqlnd php72w-fpm.x86_64 php72w-xml.x86_64 php72w-mbstring.x86_64 


## 启动php
	systemctl enable php-fpm.service
	systemctl start php-fpm

# NGINX安装

## 安装nginx
	yum install nginx
## 启动nginx常用命令
	systemctl start nginx
	systemctl stop nginx
	systemctl restart nginx
	systemctl status nginx
# Composer安装
## 安装命令
	curl -sS https://getcomposer.org/installer | php
	sudo mv composer.phar /usr/local/bin/composer
## 查看版本
	composer --version
## 更新composer
	composer selfupdate

# laravel安装
## composer安装laravel
	composer global require "laravel/installer"
## 创建laravel项目
	laravel new blog
# MYSQL安装
## 下载mysql的repo源
	cd /usr/local/src
	wget http://repo.mysql.com/mysql-community-release-el7-5.noarch.rpm
## 安装repo包
	rpm -ivh mysql-community-release-el7-5.noarch.rpm
	yum repolist all
## 安装Mysql
	yum install -y mysql-server
## 启动mysql
	systemctl start mysql
	# 或者
	service mysqld restart
	ss -lntp

	# 设置开机启动
	systemctl enable mysqld

	# MySQL 安全设置（密码）
	mysql_secure_installation
## 创建账号
	# 假设数据库密码为123456
	mysql -uroot -p123456
	# 实施环境用户
	mysql > CREATE USER 'dbuser'@'%' IDENTIFIED BY '123456';
	mysql > GRANT SELECT,INSERT,UPDATE,DELETE ON *.* TO 'dbuser'@'%';

	# 管理员用户
	mysql > CREATE USER 'admin'@'%' IDENTIFIED BY '123456';
	mysql > GRANT ALL ON *.* TO 'admin'@'%';
	mysql > flush privileges;

	mysql > exit;