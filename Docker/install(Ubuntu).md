# DockerをUbuntuにインストールする方法

## 前準備
```
sudo apt update && apt upgrade -y
```
```
sudo apt install ca-certificates curl gnupg
```

## リポジトリを追加する
```
sudo install -m 0755 -d /etc/apt/keyrings
```
```
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
```
```
sudo chmod a+r /etc/apt/keyrings/docker.asc
```
```
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
```
```
sudo apt update
```

## Dockerエンジンをインストールする
```
sudo apt install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```
```
docker --version
```
インストールできたことを確認する。
