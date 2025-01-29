# Tiny-Login-php

## 前置き
皆さんはSQLを知っているだろうか?    
リレーショナルデータベースと呼ばれているものです。    
システムやアプリケーションを構築上でなくてはならないと言ってよいほどSQLは重要になっています。   
このリポジトリ(?)はSQL第一歩を踏み出すための礎となるべく作成しました。
SQLの構文なんて何も知らなくても大丈夫。とりあえずこのリポジトリを動かしてみましょう。  
** ※セキュリティ対策は0に等しいので公開することは好ましくない  **
### SQLのが利用されている場面
- ユーザーアカウントのデータ管理
- ガチャの履歴(ゲーム)
- ランキングシステム
- チャット(SNS)
- SNSの友人情報(SNS)
- 分散型システムのタスク管理
- センサーなどのデータ管理(IoT)
- アプリケーションの設定を保存


## 前準備-1
ここではDockerとPortainerをセットアップします。 
すでにインストールされている場合は飛ばしてください。    
OS:Ubuntu24.04  
[インストール方法の参考](https://qiita.com/tf63/items/c21549ba44224722f301)

### 1.Dockerのインストール
必要なパッケージをインストール
```
sudo apt update
sudo apt install ca-certificates curl gnupg

```
Dockerのリポジトリを追加
```
sudo install -m 0755 -d /etc/apt/keyrings
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
sudo chmod a+r /etc/apt/keyrings/docker.asc

# Add the repository to Apt sources:
echo \
    "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu \
    $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
    sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt update

```
Dockerのインストール
```
sudo apt install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

```
### 2.Portainerのインストール
ボリューム作成
```
docker volume create portainer_data
```
インストール
```
docker run -d -p 9000:9000 -p 9443:9443 --name portainer \
--restart=always \
-v /var/run/docker.sock:/var/run/docker.sock \
-v portainer_data:/data \
portainer/portainer-ce
```
あとはマシンのIPアドレスの9000版にブラウザでアクセスし初期設定をしてください。

## 前準備-2
MariaDBとWebアプリケーション用のコンテナを作成します。
### MariaDB
MariaDBはMySQLと互換性のあるデータベースです。  
1.PortainerのTemplatesのタグからMariaDBを探す。    
2.データベースにおけるrootユーザーのパスワードを設定する。    
(3).ここでポートも固定にしておくと良い。  
4.コンテナをデプロイする。    

### Web
今回は通常のWebサーバーに加えphpの動作環境が必要になるので、我が部のDockerイメージを利用する。    
1.PortainerのContainersのタブの「+Add container」を押す。   
2.Imageの入力欄に`kgsuken/apache-php-suken`と入力。   
3.ホストマシンの適当なTCPポート(何でも良い)をコンテナ内の80番ポートに転送するように設定する。   
4.コンテナをデプロイする。

## 使い方
### MariaDBコンテナ
MariaDBコンテナでMariaDBにログインする  
このリポジトリに沿ってセットアップした人はユーザー名が「root」になります    
```
mariadb -u <ユーザー名> -p
```
パスワードの入力が求められるので先程のパスワードを入力

データベースを作る
```
CREATE DATABASE userdb;

USE userdb;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

```

### Webコンテナ
1.Webサーバーのドキュメントフォルダ(HTMLがおいてある場所)にassetsを配置する。(basicは自分でデザイン作る人向け,designedは最低限のデザインあり)   
2.db.phpのパスワードとユーザー名と接続先を変更する
```
パスワードはMariaDBコンテナを作るときに指定したパスワード(データベースのパスワード)
ユーザー名にかんしてはこのリポジトリに沿ってセットアップした人は「root」
接続先:MariaDBのIPアドレスとポート番号
例[192.168.1.10:3306]
```
3.ブラウザでWebコンテナのregister.phpにアクセスすればページが表示されます。

