FROM mysql:5.7
ENV MYSQL_DATABASE test
ENV MYSQL_USER guest
ENV MYSQL_ROOT_PASSWORD guest
ADD init.sql /docker-entrypoint-initdb.d
EXPOSE 4407
RUN ["/bin/bash", "-c", "mysql> SET GLOBAL explicit_defaults_for_timestamp = 1;"]