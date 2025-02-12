# DockerをWebから管理できるPortainerをインストールする

### ボリュームを作る
```
docker volume create portainer_data
```
### インストール
```
docker run -d -p 9000:9000 -p 9443:9443 --name portainer \
--restart=always \
-v /var/run/docker.sock:/var/run/docker.sock \
-v portainer_data:/data \
portainer/portainer-ce
```
ブラウザで対象のPCの9000番にアクセスすればOK
