# DockerコンテナからNvidiaのGPUを利用する。

## ホストマシンへNvidiaのドライバをインストールする
### Nouveauを無効化する
`/etc/modprobe.d/blacklist-nouveau.conf`に
```
blacklist nouveau
options nouveau modeset=0
```
と記載して
```
sudo update-initramfs -u
```
を実行する
### 既存のドライバを消す
```
sudo apt-get --purge remove nvidia-*
```
```
sudo apt-get --purge remove cuda-*
```

### GPUのドライバインストールする
リポジトリを追加
```
sudo add-apt-repository ppa:graphics-drivers/ppa
```
パッケージリストを更新
```
sudo apt update
```
インストールする
```
sudo apt install nvidia-driver-{ここにバージョン}
```
対応しているバージョンは[NVIDIA公式](https://www.nvidia.com/ja-jp/drivers/)を参照してください。


### CUDAをインストールする
[NVIDIAのCUDAのサイト](https://developer.nvidia.com/cuda-downloads)にアクセスして、表示されるコマンドをそのまま実行してください。

### cuDNNをインストールする
[NVIDIAのcuDNNのサイト]([https://developer.nvidia.com/cuda-downloads](https://developer.nvidia.com/cudnn-downloads)にアクセスして、表示されるコマンドをそのまま実行してください。


## NvidiaDockerをインストールする
[NVIDIADockerのサイト](https://docs.nvidia.com/datacenter/cloud-native/container-toolkit/latest/install-guide.html)を見てインストールしてください。


## CUDAコンテナを実行する
[DockerHUB](https://hub.docker.com/r/nvidia/cuda/tags)に公式のイメージがあるのでこれを利用します。  
ホストのバージョンに合わせて適切なイメージを選択してください。
