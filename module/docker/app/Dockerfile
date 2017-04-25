#---------------------------------
# イメージを選択
#---------------------------------
FROM centos:7
MAINTAINER eyemovic


#---------------------------------
# パッケージのインストール
#---------------------------------
RUN yum -y update && yum -y upgrade
RUN yum -y install epel-release
RUN yum -y install unzip
RUN rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
RUN yum -y install nginx
RUN yum -y install --enablerepo=remi,remi-php71 php php-fpm php-gd php-mbstring php-pdo php-mysqlnd php-xml php-xmlrpc
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/srv
#WORKDIR "/srv"
#RUN php composer.phar self-update
#RUN php composer.phar update
#RUN php vendor/kenjis/ci-phpunit-test/install.php


#---------------------------------
# 設定ファイルをコピー
#---------------------------------
COPY etc/nginx/conf.d/vhosts.conf /etc/nginx/conf.d/
COPY docker-entrypoint.sh /
RUN chmod +x /docker-entrypoint.sh


#---------------------------------
# サービス起動
#---------------------------------
EXPOSE 80 443
ENTRYPOINT /docker-entrypoint.sh
