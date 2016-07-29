# GameAnalytics 游戏统计分析系统

##安装方法
  *搭建apache/nginx + php5，mongodb-server安装，安装php的mongo扩展。
  ###centos:
   php环境安装:yum install php
    *mongodb安装:yum install mongodb-server
php的mongo扩展安装:yum install php-mongo

启动apache:apachectl start
启动mongo-server:service mongod start

ubuntu:
apache安装:apt-get install apache2 
php安装:apt-get install php5
apache的php模块安装:apt-get install libapache2-mod-php5
mongodb:apt-get install mongo
php扩展安装:apt-get install php-mongo

安装搞不定，自己百度吧

*代码布署
git clone将代码放到web目录中，如/var/www/html
生成测试数据:http://192.168.0.6/GameAnalytics/Web/Api/api.php?method=Install.run
登录管理后台:http://192.168.0.6/GameAnalytics/Web/Admin/admin.php
登录帐号和密码都是:admin
在线demo地址:http://47.90.45.126:81/GameAnalytics/Web/Admin/admin.php

