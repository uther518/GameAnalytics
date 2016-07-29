# GameAnalytics 游戏统计分析系统

##安装方法
###搭建apache/nginx + php5，mongodb-server安装，安装php的mongo扩展。<br>
centos:<br>
php环境安装:yum install php<br>
mongodb安装:yum install mongodb-server<br>
php的mongo扩展安装:yum install php-mongo<br>

启动apache:apachectl start<br>
启动mongo-server:service mongod start<br>

ubuntu:<br>
apache安装:apt-get install apache2 <br>
php安装:apt-get install php5<br>
apache的php模块安装:apt-get install libapache2-mod-php5<br>
mongodb:apt-get install mongo<br>
php扩展安装:apt-get install php-mongo<br>

安装搞不定，自己百度吧<br>

###代码布署<br>
git clone将代码放到web目录中，如/var/www/html<br>
生成测试数据:http://192.168.0.6/GameAnalytics/Web/Api/api.php?method=Install.run<br>
登录管理后台:http://192.168.0.6/GameAnalytics/Web/Admin/admin.php<br>
登录帐号和密码都是:admin<br>
在线demo地址:http://47.90.45.126:81/GameAnalytics/Web/Admin/admin.php<br>

