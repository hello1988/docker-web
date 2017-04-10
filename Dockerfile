FROM centos

RUN yum update -y
RUN yum install -y httpd vim net-tools
RUN yum install -y php-mysql php 
#RUN yum install -y mariadb-server mariadb telnet

COPY index.php /var/www/html/
COPY lib/* /var/www/html/lib/
COPY cmd/* /var/www/html/cmd/
COPY sqlData/* /var/www/html/sqlData/

RUN chmod 777 -R /var/log/httpd/