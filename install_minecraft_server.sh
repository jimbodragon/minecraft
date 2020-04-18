#/bin/bash

minecraftname=$1
rconpassword=$2
dropboxserver=$3
dropboxuser=$4
levelseed=$5

apt-get install openjdk-11-jre-headless htop sudo
useradd -r -m -U -d /opt/minecraft -s /bin/bash minecraft
echo "tmpfs       /opt/minecraft/server/ tmpfs   nodev,nosuid,noexec,nodiratime,size=2048M   0 0" >> /etc/fstab

mkdir -p /opt/minecraft/{backups,tools,server}
cd tools && wget https://github.com/Tiiffi/mcrcon/releases/download/v0.7.1/mcrcon-0.7.1-linux-x86-64.tar.gz && tar -xzf mcrcon-0.7.1-linux-x86-64.tar.gz && rm mcrcon-0.7.1-linux-x86-64.tar.gz && find -name mcrcon -exec cp {} /opt/minecraft/tools/ \; && find -type d -name "mcrcon*" -exec rm -rf {} \;
wget https://launcher.mojang.com/v1/objects/bb2b6b1aefcd70dfd1892149ac3a215f6c636b07/server.jar && cp server.jar /opt/minecraft/server

'

<a href="https://launcher.mojang.com/v1/objects/bb2b6b1aefcd70dfd1892149ac3a215f6c636b07/server.jar" aria-label="mincraft version" data-bi-id="n1c1c1c2c1c1m1r1a3" data-bi-name="minecraft_server.1.15.2.jar" data-bi-type="text">minecraft_server.1.15.2.jar</a>

cat <<EOF > /opt/minecraft/tools/start.sh
#!/bin/bash
function check_version_and_download()
{
	if [ $1 > $2 ]
	then
		wget -O /opt/minecraft/server/server.jar $3
	fi
}

function check_update()
{
	major_version=$(cut -d '.' -f 1 /opt/minecraft/server/server.version)
	minor_version=$(cut -d '.' -f 2 /opt/minecraft/server/server.version)
	bugfix_version=$(cut -d '.' -f 3 /opt/minecraft/server/server.version)
	
	webinfo=$(wget -q -O /dev/stdout https://www.minecraft.net/fr-ca/download/server/ | grep 'minecraft_server.' > webinfo)
	webversion=$(cat webinfo | tail -n 1 | awk -F 'minecraft_server.' '{print $2}' | awk -F '.jar' '{print $1}')
	downloadlink=$(cat webinfo | awk -F 'href="' '{print $2}' | cut -d '"' -f 1)
	
	latest_major_version=$(echo "$webversion" | cut -d '.' -f 1)
	latest_minor_version=$(echo "$webversion" | cut -d '.' -f 2)
	latest_bugfix_version=$(echo "$webversion" | cut -d '.' -f 3)
	
	if [ $latest_major_version -eq $major_version ]
	then
		if [ $latest_minor_version -eq $minor_version ]
		then
			check_version_and_download $latest_bugfix_version $bugfix_version $downloadlink
		else
			check_version_and_download $latest_minor_version $minor_version $downloadlink
		fi
	else
		check_version_and_download $latest_major_version $major_version $downloadlink
	fi
}

function check_properties()
{
	#https://launcher.mojang.com/v1/objects/d0d0fe2b1dc6ab4c65554cb734270872b72dadd6/server.jar
	#1.15.2: https://launcher.mojang.com/v1/objects/bb2b6b1aefcd70dfd1892149ac3a215f6c636b07/server.jar
	
	if [ ! -f /opt/minecraft/server/eula.txt ]
	then
		echo -e "#By changing the setting below to TRUE you are indicating your agreement to our EULA (https://account.mojang.com/documents/minecraft_eula).\neula=true" > /opt/minecraft/server/eula.txt

	fi
	
	if [ ! -f /opt/minecraft/server/server.properties ]
	then
		cat <<EOF > /opt/minecraft/server/server.properties
#Minecraft server properties for $minecraftname
spawn-protection=16
max-tick-time=60000
query.port=25565
generator-settings=
force-gamemode=false
allow-nether=true
enforce-whitelist=false
gamemode=survival
broadcast-console-to-ops=true
enable-query=false
player-idle-timeout=0
difficulty=hard
spawn-monsters=true
broadcast-rcon-to-ops=true
op-permission-level=4
pvp=true
snooper-enabled=true
level-type=default
hardcore=false
enable-command-block=false
max-players=20
network-compression-threshold=256
resource-pack-sha1=
max-world-size=29999984
rcon.port=23888
server-port=25565
server-ip=
spawn-npcs=true
allow-flight=false
level-name=world
view-distance=10
resource-pack=
spawn-animals=true
white-list=false
rcon.password=$rconpassword
generate-structures=true
max-build-height=256
online-mode=false
use-native-transport=true
prevent-proxy-connections=false
enable-rcon=true
motd=La Communaute de $minecraftname
EOF

	echo "0.0.0" > /opt/minecraft/server/server.version
	fi
	
	if [ "$levelseed" != "" ]
	then
		echo "level-seed=$levelseed" > /opt/minecraft/server/server.properties
	fi
}

function check_mount()
{
        num_mount=$(df -h | grep /opt/minecraft/server | wc -l)
        if [ $num_mount -eq 0 ]
        then
                sudo /usr/bin/mount /opt/minecraft/server
                return 0
        fi

        return 1
}

function revert_backup()
{
        cd /opt/minecraft/server
        backup_file="/opt/minecraft/backups/server-$minecraftname-minecraft.tar.gz"
		if [ -f \$backup_file ]
			tar -xzf $backup_file
			mv /opt/minecraft/server/opt/minecraft/server/* /opt/minecraft/server/
			rm -rf /opt/minecraft/server/opt
		fi
}

check_mount
check_properties
check_update
revert_backup

/usr/bin/java -d64 -Xmx8192M -Xms2048M -jar /opt/minecraft/server/server.jar nogui
EOF

cat <<EOF > /opt/minecraft/tools/backup.sh
#!/bin/bash

function rcon {
  /opt/minecraft/tools/mcrcon -H 127.0.0.1 -P 23888 -p $rconpassword "\$1"
}

if [ $(ps aux | grep /opt/minecraft/server/server.jar | grep -v grep | wc -l) -eq 1 ]
then

        backup_file="/opt/minecraft/backups/server-$minecraftname-minecraft.tar.gz"

        rcon "save-off"
        rcon "save-all"
        tar -cvpzf $backup_file /opt/minecraft/server
        rcon "save-on"

        scp $backup_file $dropboxuser@$dropboxserver:/home/$dropboxuser/Dropbox/minecraftBackup/$minecraftname/

        ## Delete older backups
        ##find /opt/minecraft/backups/ -type f -mtime +7 -name '*.gz' -delete
fi
EOF

chmod ug+x /opt/minecraft/tools/*.sh
ssh-keygen -q -t rsa -f /opt/minecraft/.ssh/id_rsa -P "" && sshkey=$(cat /opt/minecraft/.ssh/id_rsa.pub) && ssh $dropboxuser@$dropboxserver "echo '$sshkey' >> .ssh/authorized_keys"

chown minecraft:minecraft /opt/minecraft/

echo "4,9,14,19,24,29,34,39,44,49,54,59 * * * * minecraft /opt/minecraft/tools/backup.sh" > /etc/cron.d/minecraft_backup
echo -e "minecraft ALL=(root) NOPASSWD: /usr/bin/mount /opt/minecraft/server" > /etc/sudoers.d/minecraft

cat <<EOF > /etc/systemd/system/minecraft.service
[Unit]
Description=Minecraft Server for $minecraftname
After=network.target

[Service]
User=minecraft
Nice=1
KillMode=none
SuccessExitStatus=0 1
ProtectHome=true
ProtectSystem=full
PrivateDevices=true
NoNewPrivileges=true
WorkingDirectory=/opt/minecraft/server
ExecStart=/opt/minecraft/tools/start.sh
ExecStop=/opt/minecraft/tools/mcrcon -H 127.0.0.1 -P 23888 -p $rconpassword stop

[Install]
WantedBy=multi-user.target
EOF

systemctl daemon-reload
