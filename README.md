
for parameter in minecraftname rconpassword server_version use_forge minecraft_folder minecraft_user openjdk_version mcron_version fs_ram_mb levelseed dropboxserver dropboxuser
do
  eval "echo \$$parameter"
done > response.txt
bash /tmp/install_minecraft_server.sh < response.txt


wget https://raw.githubusercontent.com/jimbodragon/minecraft/master/install_minecraft_server.sh
bash /tmp/install_minecraft_server.sh < response.txt
