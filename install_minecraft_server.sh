#/bin/bash

minecraft_home="$minecraft_folder"

function create_start()
{
	minecraftname="$1"
	rconpassword="$2"
	server_version="$3"
	use_forge="$4"
	minecraft_folder="$5"
	minecraft_user="$6"
	levelseed="$7"
	fs_ram_mb="$8"

	cat <<EOS > $minecraft_folder/tools/start.sh
#!/bin/bash
function check_version_and_download()
{
	server_version="$server_version"
	use_forge="$use_forge"
	minecraft_folder="$minecraft_folder"

	if [ "\$2" == ""]
	then
		if [ \$1 > \$2 ]
		then
			wget -O $minecraft_folder/server/server.jar \$3
			echo "\$4" > $minecraft_folder/server/server.version
		fi
	fi
}

function check_update()
{
	server_version="$server_version"
	use_forge="$use_forge"
	minecraft_folder="$minecraft_folder"

	major_version=\$(cut -d '.' -f 1 \$minecraft_folder/server/server.version 2>&1)
	minor_version=\$(cut -d '.' -f 2 \$minecraft_folder/server/server.version 2>&1)
	bugfix_version=\$(cut -d '.' -f 3 \$minecraft_folder/server/server.version 2>&1)

	rm "\$minecraft_folder/version_manifest.json"
	wget -O "\$minecraft_folder/version_manifest.json" https://launchermeta.mojang.com/mc/game/version_manifest.json

	if [ "\$server_version" == "latest" ] && [ "\$use_forge" != "true" ]
	then
		latest_version=\$(jq .latest.release "\$minecraft_folder/version_manifest.json")
	elif [ "\$server_version" != "latest" ]
		latest_version=\$server_version
	fi

	downloadlink="\$(wget -O - "$(jq ".versions[] | select(.id == \\\"\$latest_version\\\")" "\$minecraft_folder/version_manifest.json" | jq .url | cut -d '\"' -f 2)" | jq .downloads.server.url | cut -d '\"' -f 2)"

	latest_major_version=\$(echo "\$latest_version" | cut -d '.' -f 1)
	latest_minor_version=\$(echo "\$latest_version" | cut -d '.' -f 2)
	latest_bugfix_version=\$(echo "\$latest_version" | cut -d '.' -f 3)

	if [ "\$latest_major_version" -eq "\$major_version" ]
	then
		if [ "\$latest_minor_version" -eq "\$minor_version" ]
		then
			check_version_and_download "\$latest_bugfix_version" "\$bugfix_version" "\$downloadlink" "\$latest_version"
		else
			check_version_and_download "\$latest_minor_version" "\$minor_version" "\$downloadlink" "\$latest_version"
		fi
	else
		check_version_and_download "\$latest_major_version" "\$major_version" "\$downloadlink" "\$latest_version"
	fi
}

function check_server()
{
	server_version="$server_version"
	use_forge="$use_forge"
	minecraft_folder="$minecraft_folder"

	if [ ! -f \$minecraft_folder/server/eula.txt ]
	then
		echo -e "#By changing the setting below to TRUE you are indicating your agreement to our EULA (https://account.mojang.com/documents/minecraft_eula).\neula=true" > \$minecraft_folder/server/eula.txt

	fi

	if [ ! -f \$minecraft_folder/server/server.properties ]
	then
		cat <<EOF > \$minecraft_folder/server/server.properties
#Minecraft server properties for \$minecraftname
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
rcon.password=\$rconpassword
generate-structures=true
max-build-height=256
online-mode=false
use-native-transport=true
prevent-proxy-connections=false
enable-rcon=true
motd=La Communaute de \$minecraftname
#level-seed=
EOF
		if [ "\$1" == ""]
		then
			echo "\$1" > \$minecraft_folder/server/server.version
		else
			echo "0.0.0" > \$minecraft_folder/server/server.version
		fi
	fi
}

function check_mount()
{
	minecraft_folder="$minecraft_folder"
	num_mount=\$(df -h | grep $minecraft_folder/server | wc -l)
	if [ \$num_mount -eq 0 ]
	then
	        sudo /usr/bin/mount $minecraft_folder/server
	        return 0
	fi

	return 1
}

function revert_backup()
{
	minecraft_folder="$minecraft_folder"
  cd $minecraft_folder/server
  backup_file="$minecraft_folder/backups/server-$1-minecraft.tar.gz"
	if [ -f \$backup_file ]
	then
		tar -xzf \$backup_file
		mv $minecraft_folder/server$minecraft_folder/server/* $minecraft_folder/server/
		rm -rf $minecraft_folder/server/opt
	fi
}

check_mount
check_server
check_update
revert_backup

/usr/bin/java -server -XX:ParallelGCThreads=2 -XX:InitiatingHeapOccupancyPercent=35 -XX:G1ReservePercent=15 -XX:+UseCompressedOops -XX:+UseG1GC -XX:+AlwaysPreTouch -XX:+UnlockExperimentalVMOptions -XX:MaxGCPauseMillis=100 -XX:+DisableExplicitGC -XX:TargetSurvivorRatio=90 -XX:G1NewSizePercent=50 -XX:G1MaxNewSizePercent=80 -XX:InitiatingHeapOccupancyPercent=10 -XX:G1MixedGCLiveThresholdPercent=50 -Xmx8192M -Xms256M -Djava.net.preferIPv4Stack=true -jar $minecraft_folder/server/server.jar nogui
EOS

	if [ "$levelseed" != "" ]
	then
		sed -i "s|#level-seed=|level-seed=$levelseed|g" $minecraft_folder/tools/start.sh
	fi
	let "maxmemory = $(free -m | grep Mem | awk '{print $2}') - $fs_ram_mb"
	sed -i "s|-Xmx8192M|-Xmx$(echo $maxmemory)M|g" $minecraft_folder/tools/start.sh
}

function create_backup_tool()
{
	$minecraftname="$1"
	rconpassword="$2"
	dropboxserver="$3"
	dropboxuser="$4"
	cat <<EOS > $minecraft_folder/tools/backup.sh
#!/bin/bash

function rcon {
  $minecraft_folder/tools/mcrcon -H 127.0.0.1 -P 23888 -p $rconpassword "\$1"
}

if [ \$(ps aux | grep $minecraft_folder/server/server.jar | grep -v grep | wc -l) -eq 1 ]
then

        backup_file="$minecraft_folder/backups/server-$minecraftname-minecraft.tar.gz"

        rcon "save-off"
        rcon "save-all"
        tar -cvpzf \$backup_file $minecraft_folder/server
        rcon "save-on"

        scp \$backup_file $dropboxuser@$dropboxserver:/home/$dropboxuser/Dropbox/minecraftBackup/$minecraftname/

        ## Delete older backups
        ##find $minecraft_folder/backups/ -type f -mtime +7 -name '*.gz' -delete
fi
EOS
}

function create_service()
{
	minecraftname="$1"
	rconpassword="$2"
	cat <<EOS > /etc/systemd/system/$minecraftname.service
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
WorkingDirectory=$minecraft_folder/server
ExecStart=$minecraft_folder/tools/start.sh
ExecStop=$minecraft_folder/tools/mcrcon -H 127.0.0.1 -P 23888 -p $rconpassword stop

[Install]
WantedBy=multi-user.target
EOS

	systemctl enable $minecraftname.service
}

function validate_parameter()
{
	parameter_name="$1"
	default_value="$2"
	message="$3"
	require="$4"

	if [ "$require" == "true" ]
	then
		minecraftname="$default_value"
	fi

	read -p "$minecraftname -> $message: " $parameter_name
	parameter_value="$(eval "echo \$$parameter_name")"

	if [ "$require" == "true" ] && [ "$parameter_value" == "" ]
	then
		if [ "$default_value" == "" ]
		then
			echo "parameter $parameter_name is require"
			echo "Exiting"
			exit 1
		fi
		$parameter_name=$default_value
	fi
}

function create_response()
{
	minecraftname="$1"
	rconpassword="$2"
	server_version="$3"
	use_forge="$4"
	minecraft_folder="$5"
	minecraft_user="$6"
	openjdk_version="$7"
	mcron_version="$8"
	fs_ram_mb="$9"
	levelseed="$10"
	dropboxserver="$11"
	dropboxuser="$12"


	cat <<EOS > $minecraft_folder/tools/response
$minecraftname
$rconpassword
$server_version
$use_forge
$minecraft_folder
$minecraft_user
$openjdk_version
$mcron_version
$fs_ram_mb
$levelseed
$dropboxserver
$dropboxuser
EOS

}

default_server_version="latest"
default_use_forge="false"
default_mcron_version="0.7.2"
default_minecraft_folder="/opt/minecraft"
default_openjdk_version="18"
default_minecraft_user="minecraft"
default_fs_ram_mb="4096"

validate_parameter minecraftname "Minecraft" "Minecraft Instance name" true
validate_parameter rconpassword "$minecraftname" "Minecraft mcrcon password" true
validate_parameter server_version "$default_server_version" "server version (empty for $default_server_version)"
validate_parameter use_forge "$default_use_forge" "use forge server (true to activate)"
validate_parameter minecraft_folder "$default_minecraft_folder" "install folder (empty for $default_minecraft_folder)"
validate_parameter minecraft_user "$default_minecraft_user" "user (empty for $default_minecraft_user)"

validate_parameter openjdk_version "$default_openjdk_version" "openjdk version (empty for $default_openjdk_version)"
validate_parameter mcron_version "$default_mcron_version" "mcron_version (empty for $default_mcron_version)"

validate_parameter fs_ram_mb "$default_fs_ram_mb" "size of ram fs for minecraft folder (empty for $default_fs_ram_mb)"

validate_parameter levelseed "" "level seed"

validate_parameter dropboxserver "" "Dropbox server for backup"
validate_parameter dropboxuser "" "Dropbox user for backup"

backuplog="$minecraft_folder/log/$minecraftname.backup.log"

echo -e "minecraftname = $minecraftname\nrconpassword = $rconpassword\ndropboxserver = $dropboxserver\ndropboxuser = $dropboxuser\nlevelseed = $levelseed\nserver_version = $server_version\nuse_forge = $use_forge\nmcron_version = $mcron_version\nminecraft_folder = $minecraft_folder\nopenjdk_version = $openjdk_version\nbackuplog = $backuplog"

apt-get install "openjdk-$openjdk_version-jre-headless" "htop" "sudo" "net-tools"
useradd -r -m -U -d $minecraft_folder -s /bin/bash $minecraft_user
echo -e "tmpfs       $minecraft_folder/server/ tmpfs   nodev,nosuid,noexec,nodiratime,size=$default_fs_ram_mb\M   0 0" >> /etc/fstab

mkdir -p $minecraft_folder/{backups,tools,server,logs}
wget -O "$minecraft_folder/tools/mcrcon-$mcron_version-linux-x86-64.tar.gz" "https://github.com/Tiiffi/mcrcon/releases/download/v$mcron_version/mcrcon-$mcron_version-linux-x86-64.tar.gz"
tar -xzf "$minecraft_folder/tools/mcrcon-$mcron_version-linux-x86-64.tar.gz"
rm "$minecraft_folder/tools/mcrcon-$mcron_version-linux-x86-64.tar.gz"
find -name mcrcon -exec cp {} $minecraft_folder/tools/ \;
find / -type d -name "mcrcon*" -exec rm -rf {} \;

echo "4,9,14,19,24,29,34,39,44,49,54,59 * * * * $minecraft_user $minecraft_folder/tools/backup.sh > \"$backuplog\"" > /etc/cron.d/$minecraftname_backup
echo > "$backuplog"
chown $minecraft_user:$minecraft_user "$backuplog"
echo "$minecraft_user ALL=(root) NOPASSWD: /usr/bin/mount $minecraft_folder/server" > /etc/sudoers.d/$minecraft_user

create_start "$minecraftname" "$rconpassword" "$server_version" "$use_forge" "$minecraft_folder" "$minecraft_user" "$levelseed" "$fs_ram_mb"
create_backup_tool "$minecraftname" "$rconpassword" "$dropboxserver" "$dropboxuser"
create_service "$minecraftname" "$rconpassword"
create_response "$minecraftname" "$rconpassword" "$server_version" "$use_forge" "$minecraft_folder" "$minecraft_user" "$openjdk_version" "$mcron_version" "$fs_ram_mb" "$levelseed" "$dropboxserver" "$dropboxuser"

chmod -R ug+x $minecraft_folder/tools/*.sh
chown -R $minecraft_user:$minecraft_user $minecraft_folder/

echo "Enter password for $dropboxuser@$dropboxserver"
su - $minecraft_user -c "/usr/bin/ssh-keygen -q -t rsa -f $minecraft_folder/.ssh/id_rsa -P ''"
sshkey=$(cat $minecraft_folder/.ssh/id_rsa.pub)
ssh $dropboxuser@$dropboxserver "echo '$sshkey' >> .ssh/authorized_keys"

systemctl daemon-reload
systemctl start $minecraftname.service
