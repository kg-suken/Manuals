# DockerをWindowsにインストールする方法
## DockerDesktopをダウンロード
[ダウンロードサイト](https://docs.docker.com/desktop/setup/install/windows-install/)
インストーラーをダウンロードして実行する
基本的にこれで完了


## エラー時の対処法

### WSLが更新されていないパターン
```
wsl.exe update
```
### Linux用Windowsサブシステムが有効になっていない場合
コントロールパネルを開き、`コントロールパネル>プログラム>Windowsの機能の有効化または無効化`を選択  
項目の中から「Linux用Windowsサブシステム」と「VirtualMachinePlatform」を有効にする。

### CPUの仮想化が有効になっていない
タスクマネージャーのCPUのタブで「仮想化」が有効か確認できる。
BIOS(UEFI)を開き
**intelの場合**  
vt-d　と vt-x  
を有効化する

**AMDの場合**  
SMVモードを有効にする
