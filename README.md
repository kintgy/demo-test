## nginx 配置
```
server {
    listen 80;
    server_name demo.local.cg-dev.cn;
    root /var/www/demo/public;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        # fastcgi_pass unix:/var/run/php/php7.1-fpm.sock;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;

        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;
    }

    location ~ \.php$ {
        return 404;
    }

    error_log /var/log/nginx/demo_error.log;
    access_log /var/log/nginx/demo_access.log;
}
```
## 定时任务配置
```
*/60 * * * * /var/www/demo/bin/console log:upload-storage aliYun -vvv  每小时上传日志文件至阿里云
```
