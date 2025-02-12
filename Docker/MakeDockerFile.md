# DockerFileを書く


## 1.新しいディレクトリを作る
```
mkdir myImage
```
```
cd myImage
```
## 2.DockerFileを書く
`DockerFile`を作成し
```
# ベースイメージを指定
FROM ubuntu:latest
WORKDIR /var/www/html
VOLUME ["/var/www/html"]
VOLUME ["/etc/apache2"]
VOLUME ["/etc/letsencrypt2"]

# 必要なパッケージをインストール
RUN apt-get update && apt-get install -y \
    apache2 \
    php libapache2-mod-php \
    php-fpm php-common php-mbstring php-xmlrpc php-gd php-xml php-mysql \
    php-cli php-zip php-curl php-imagick php-intl

# Apacheの設定
EXPOSE 80

# コンテナ実行時にApacheをスタートさせる
CMD ["apachectl", "-D", "FOREGROUND"]
```

のような内容を書き込む

## 3.DockerFileをビルド
```
docker build -t <DockerHubユーザー名>/<イメージ名>:<タグ> .
# 例:docker build -t myusername/myapp:latest .
```

## 4.DockerHubにアップロードする
ログインする
```
docker login
```
Pushする
```
docker push <DockerHubユーザー名>/<イメージ名>:<タグ>
# 例:docker push myusername/myapp:latest
```
