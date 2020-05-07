FROM mysql:8.0.19
ENV MYSQL_DATABASE test
ENV MYSQL_USER guest
ENV MYSQL_ROOT_PASSWORD guest
ADD init.sql /docker-entrypoint-initdb.d
EXPOSE 4407