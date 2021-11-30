#/bin/bash

minecraft_home="/opt/minecraft"

function create_start()
{
	cat <<EOS > /opt/minecraft/tools/start.sh
#!/bin/bash
function check_version_and_download()
{
	if [ "\$2" == ""]
	then
		if [ \$1 > \$2 ]
		then
			wget -O /opt/minecraft/server/server.jar \$3
			echo "\$4" > /opt/minecraft/server/server.version
		fi
	fi
}

function check_update()
{
	major_version=\$(cut -d '.' -f 1 /opt/minecraft/server/server.version 2>&1)
	minor_version=\$(cut -d '.' -f 2 /opt/minecraft/server/server.version 2>&1)
	bugfix_version=\$(cut -d '.' -f 3 /opt/minecraft/server/server.version 2>&1)

	rm version_manifest.json
	wget https://launchermeta.mojang.com/mc/game/version_manifest.json
	latest_version=\$(jq .latest.release version_manifest.json)
	wget \$(jq ".versions[] | select(.id == \"\$latest_version\")" version_manifest.json | jq .url | cut -d '"' -f 2)
	downloadlink=\$(jq .downloads.server.url \$latest_version.json)

	latest_major_version=\$(echo "\$latest_version" | cut -d '.' -f 1)
	latest_minor_version=\$(echo "\$latest_version" | cut -d '.' -f 2)
	latest_bugfix_version=\$(echo "\$latest_version" | cut -d '.' -f 3)

	if [ "\$latest_major_version" -eq \$major_version" ]
	then
		if [ "\$latest_minor_version" -eq \$minor_version" ]
		then
			check_version_and_download "\$latest_bugfix_version" \$bugfix_version" \$downloadlink" "\$latest_version"
		else
			check_version_and_download "\$latest_minor_version" \$minor_version" \$downloadlink" "\$latest_version"
		fi
	else
		check_version_and_download "\$latest_major_version" "\$major_version" "\$downloadlink" "\$latest_version"
	fi
}

function check_server()
{
	if [ ! -f /opt/minecraft/server/eula.txt ]
	then
		echo -e "#By changing the setting below to TRUE you are indicating your agreement to our EULA (https://account.mojang.com/documents/minecraft_eula).\neula=true" > /opt/minecraft/server/eula.txt

	fi

	if [ ! -f /opt/minecraft/server/server.properties ]
	then
		cat <<EOF > /opt/minecraft/server/server.properties
#Minecraft server properties for $1
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
rcon.password=$2
generate-structures=true
max-build-height=256
online-mode=false
use-native-transport=true
prevent-proxy-connections=false
enable-rcon=true
motd=La Communaute de $1
#level-seed=
EOF
		if [ "\$1" == ""]
		then
			echo "\$1" > /opt/minecraft/server/server.version
		else
			echo "0.0.0" > /opt/minecraft/server/server.version
		fi
	fi
}

function check_mount()
{
        num_mount=\$(df -h | grep /opt/minecraft/server | wc -l)
        if [ \$num_mount -eq 0 ]
        then
                sudo /usr/bin/mount /opt/minecraft/server
                return 0
        fi

        return 1
}

function revert_backup()
{
    cd /opt/minecraft/server
    backup_file="/opt/minecraft/backups/server-$1-minecraft.tar.gz"
		if [ -f \$backup_file ]
		then
			tar -xzf \$backup_file
			mv /opt/minecraft/server/opt/minecraft/server/* /opt/minecraft/server/
			rm -rf /opt/minecraft/server/opt
		fi
}

check_mount
check_server
check_update
revert_backup

/usr/bin/java -server -XX:ParallelGCThreads=2 -XX:InitiatingHeapOccupancyPercent=35 -XX:G1ReservePercent=15 -XX:+UseCompressedOops -XX:+UseG1GC -XX:+AlwaysPreTouch -XX:+UnlockExperimentalVMOptions -XX:MaxGCPauseMillis=100 -XX:+DisableExplicitGC -XX:TargetSurvivorRatio=90 -XX:G1NewSizePercent=50 -XX:G1MaxNewSizePercent=80 -XX:InitiatingHeapOccupancyPercent=10 -XX:G1MixedGCLiveThresholdPercent=50 -Xmx8192M -Xms256M -Djava.net.preferIPv4Stack=true -jar /opt/minecraft/server/server.jar nogui
EOS

	if [ "$1" != "" ]
	then
		sed -i "s|#level-seed=|level-seed=$3|g" /opt/minecraft/tools/start.sh
	fi
	let "maxmemory = $(free -m | grep Mem | awk '{print $2}') - 3072"
	sed -i "s|-Xmx8192M|-Xmx$(echo $maxmemory)M|g" /opt/minecraft/tools/start.sh
}

function create_backup_tool()
{
	cat <<EOS > /opt/minecraft/tools/backup.sh
#!/bin/bash

function rcon {
  /opt/minecraft/tools/mcrcon -H 127.0.0.1 -P 23888 -p $2 "\$1"
}

if [ \$(ps aux | grep /opt/minecraft/server/server.jar | grep -v grep | wc -l) -eq 1 ]
then

        backup_file="/opt/minecraft/backups/server-$1-minecraft.tar.gz"

        rcon "save-off"
        rcon "save-all"
        tar -cvpzf \$backup_file /opt/minecraft/server
        rcon "save-on"

        scp \$backup_file $4@$3:/home/$dropboxuser/Dropbox/minecraftBackup/$1/

        ## Delete older backups
        ##find /opt/minecraft/backups/ -type f -mtime +7 -name '*.gz' -delete
fi
EOS
}

function create_service()
{
	cat <<EOS > /etc/systemd/system/$1.service
[Unit]
Description=Minecraft Server for $1
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
ExecStop=/opt/minecraft/tools/mcrcon -H 127.0.0.1 -P 23888 -p $2 stop

[Install]
WantedBy=multi-user.target
EOS

	systemctl enable $1.service
}

read -p 'Minecraft Instance name: ' minecraftname
read -p 'Minecraft mcrcon password: ' rconpassword
read -p 'Minecraft Dropbox server for backup: ' dropboxserver
read -p 'Minecraft Dropbox user for backup: ' dropboxuser
read -p 'Minecraft level seed: ' levelseed
read -p 'Minecraft server version (empty for latest): ' server_version
read -p 'Minecraft server forge version if any: ' forge_server_version
read -p 'Minecraft use latest version if any: ' use_latest
read -p 'Minecraft use latest version if any: ' mcron_version
backuplog=/var/log/$minecraftname.backup.log

echo -e "minecraftname = $minecraftname\nrconpassword = $rconpassword\ndropboxserver = $dropboxserver\ndropboxuser = $dropboxuser\nlevelseed = $levelseed\nserver_version = $server_version\nforge_server_version = $forge_server_version\nmcron_version = $mcron_version"

apt-get install openjdk-17-jre-headless htop sudo  net-tools
useradd -r -m -U -d /opt/minecraft -s /bin/bash minecraft
echo "tmpfs       /opt/minecraft/server/ tmpfs   nodev,nosuid,noexec,nodiratime,size=4096M   0 0" >> /etc/fstab

mkdir -p /opt/minecraft/{backups,tools,server}
wget -O "/opt/minecraft/tools/mcrcon-$mcron_version-linux-x86-64.tar.gz" "https://github.com/Tiiffi/mcrcon/releases/download/v$mcron_version/mcrcon-$mcron_version-linux-x86-64.tar.gz" && tar -xzf "/opt/minecraft/tools/mcrcon-$mcron_version-linux-x86-64.tar.gz" && rm "/opt/minecraft/tools/mcrcon-$mcron_version-linux-x86-64.tar.gz" && find -name mcrcon -exec cp {} /opt/minecraft/tools/ \; && find / -type d -name "mcrcon*" -exec rm -rf {} \;

echo "4,9,14,19,24,29,34,39,44,49,54,59 * * * * minecraft /opt/minecraft/tools/backup.sh > \"$backuplog\"" > /etc/cron.d/minecraft_backup
echo > "$backuplog"
chown minecraft:minecraft "$backuplog"
echo "minecraft ALL=(root) NOPASSWD: /usr/bin/mount /opt/minecraft/server" > /etc/sudoers.d/minecraft

create_start "$minecraftname" "$rconpassword" "$levelseed" "$server_version" "$forge_server_version" "$use_latest"
create_backup_tool "$minecraftname" "$rconpassword" "$dropboxserver" "$dropboxuser"
create_service "$minecraftname" "$rconpassword"

chmod -R ug+x /opt/minecraft/tools/*.sh
chown -R minecraft:minecraft /opt/minecraft/
echo "Enter password for $dropboxuser@$dropboxserver"
su - minecraft -c "/usr/bin/ssh-keygen -q -t rsa -f /opt/minecraft/.ssh/id_rsa -P ''" && sshkey=$(cat /opt/minecraft/.ssh/id_rsa.pub) && ssh $dropboxuser@$dropboxserver "echo '$sshkey' >> .ssh/authorized_keys"

systemctl daemon-reload
systemctl start $minecraftname.service
