#! /usr/bin/python3
import sys
import os
import re
import json
import crypt
import hashlib
import datetime

# Arguments
action		=	str(sys.argv[1])
serverid	=	int(sys.argv[2])
game		=	str(sys.argv[3])
ip			=	str(sys.argv[4])
port		=	int(sys.argv[5])
slots		=	int(sys.argv[6])
password	=	str(sys.argv[7])

salt = 'tlas'
username = 'gs' + str(serverid)

gameConfig = None

def md5file(filePath):
	f = open(filePath, 'rb')
	md5 = hashlib.md5()
	while True:
		data = f.read(8192)
		if not data:
			break
		md5.update(data)
	f.close()
	return md5.hexdigest()

def loadGameConfig():
	global gameConfig
	f = open('/home/cp/gameservers/configs/' + game + '.cfg', 'r')
	data = f.read()
	gameConfig = json.loads(data)
	f.close()

def serverStatus():
	p = os.popen('su -lc "screen -ls | grep -c gameserver" ' + username)
	count = int(p.readline())
	p.close()
	if count > 0:
		return True
	else:
		return False

def serverCheckFiles():
	for file in gameConfig['Files']:
		if not os.path.isfile('/home/' + username + file['File']):
			if file['Required'] == 1:
				return False
			else:
				continue
		
		fileHash = md5file('/home/' + username + file['File'])
		if not fileHash in file['Hashes']:
			return False
	return True

def serverConfigure():
	configs = gameConfig['Configs']
	for config in configs:
		# Check config file
		if not os.path.isfile('/home/' + username + config['File']):
			if config['Required'] == 1:
				return False
			else:
				continue
		
		# Read config
		f = open('/home/' + username + config['File'], 'r')
		data = f.read()
		f.close()
		
		# Append exec configs
		if(config['ExecPattern']):
			execPattern = config['ExecPattern'].replace("<value>", "(.*)");
			execConfigs = re.findall(execPattern, data)
			for execConfig in execConfigs:
				configPath = os.path.dirname(config['File']) + '/' + execConfig
				configs.append({
					"File": configPath,
					"Required": 0,
					"ExecPattern": config['ExecPattern'],
					"Values": [dict(i, Required = 0) for i in config['Values']]
				})
		
		# Check configs values
		for value in config['Values']:
			pattern = value['Pattern'].replace('<value>', '(.*)')
			
			if value['Value'] == '__ip__':
				replace = value['Pattern'].replace('<value>', ip)
			elif value['Value'] == '__port__':
				replace = value['Pattern'].replace('<value>', str(port))
			elif value['Value'] == '__port2__':
				replace = value['Pattern'].replace('<value>', str(port + 1))
			elif value['Value'] == '__port3__':
				replace = value['Pattern'].replace('<value>', str(port + 1000))
			elif value['Value'] == '__slots__':
				replace = value['Pattern'].replace('<value>', str(slots))
			else:
				replace = value['Pattern'].replace('<value>', value['Value'])
			
			data = re.sub(pattern, replace, data)
			
			# Required, but not found
			if value['Required'] == 1 and not re.search(pattern, data):
				return False
			# Not required, but found
			elif value['Required'] == -1 and re.search(pattern, data):
				return False
		
		# Rewrite config
		f = open('/home/' + username + config['File'], 'w')
		f.write(data)
		f.close()
	return True

def serverInstall():
	os.system('useradd -m -g gameservers -p ' + crypt.crypt(password, salt) + ' ' + username)
	for archive in gameConfig['Archives']:
		os.system('tar -xf /home/cp/gameservers/files/' + archive + '.tar -C /home/' + username + '/')
	os.system('chown ' + username + ' -Rf /home/' + username)
	os.system('chmod 755 /home/' + username)
	return True

def serverReinstall():
	os.system('rm -Rf /home/' + username + '/*')
	for archive in gameConfig['Archives']:
		os.system('tar -xf /home/cp/gameservers/files/' + archive + '.tar -C /home/' + username + '/')
	os.system('chown ' + username + ' -Rf /home/' + username)
	return True

def serverStart():
	execCmd = gameConfig['ExecCmd']
	execCmd = execCmd.replace('@ip@', ip)
	execCmd = execCmd.replace('@port@', str(port))
	execCmd = execCmd.replace('@port2@', str(port+1))
	execCmd = execCmd.replace('@port3@', str(port+1000))
	execCmd = execCmd.replace('@slots@', str(slots))
	os.system('su -lc "screen -AmdS gameserver ' + execCmd + '" ' + username)
	return True

def serverStop():
	os.system('su -lc "screen -r gameserver -X quit " ' + username)
	return True

def serverUpdatePassword():
	os.system('usermod -p ' + crypt.crypt(password, salt) + ' ' + username)
	return True

def serverDelete():
	os.system('userdel -rf ' + username)
	return True

def serverSysLoad():
	cpu = 0.0
	ram = 0.0
	
	p = os.popen("ps u -U gs" + str(serverid) + " | awk '{print $3\"\t\"$4}'")
	line = p.readline()
	
	while line:
		result = re.match("([0-9]+\.[0-9]+)\t([0-9]+\.[0-9]+)", line)
		if(result):
			cpu += float(result.group(1))
			ram += float(result.group(2))
		line = p.readline()
	
	p.close()
	print('[[' + str(cpu) + '::' + str(ram) + ']]')

def returnResult(status, description):
	date = datetime.datetime.today()
	filename = date.strftime('%d-%m-%y') + '.xls'
	if os.path.isfile('/home/cp/logs/' + filename):
		# Open file
		f = open('/home/cp/logs/' + filename, 'a')
	else:
		# Create file
		f = open('/home/cp/logs/' + filename, 'w')
		f.write('TIME,SERVERID,STATUS,DESCRIPTION\n')
	
	f.write(date.strftime('%H:%M:%S') + ',' + str(serverid) + ',' + status + ',' + description + '\n')
	f.close()
	
	# Print result and exit
	print('[[' + status + '::' + description + ']]')
	exit(0)

loadGameConfig()

if action == 'install':
	if not serverInstall():
		returnResult('ERROR', 'InstallError')
	elif not serverConfigure():
		returnResult('ERROR', 'ConfigError')
	else:
		returnResult('OK', '')

if action == 'reinstall':
	if serverStatus():
		if not serverStop():
			returnResult('ERROR', 'StopError')
	
	if not serverReinstall():
		returnResult('ERROR', 'ReinstallError')
	elif not serverConfigure():
		returnResult('ERROR', 'ConfigError')
	else:
		returnResult('OK', '')

if action == 'start':
	if serverStatus():
		if not serverStop():
			returnResult('ERROR', 'StopError')
	
	if not serverCheckFiles():
		returnResult('ERROR', 'FilesError')
	elif not serverConfigure():
		returnResult('ERROR', 'ConfigError')
	elif not serverStart():
		returnResult('ERROR', 'StartError')
	else:
		returnResult('OK', '')

if action == 'stop':
	if not serverStop():
		returnResult('ERROR', 'StopError')
	else:
		returnResult('OK', '')

if action == 'restart':
	if not serverStop():
		returnResult('ERROR', 'StopError')
	elif not serverCheckFiles():
		returnResult('ERROR', 'FilesError')
	elif not serverConfigure():
		returnResult('ERROR', 'ConfigError')
	elif not serverStart():
		returnResult('ERROR', 'StartError')
	else:
		returnResult('OK', '')

if action == 'delete':
	if serverStatus():
		serverStop()
	
	if not serverDelete():
		returnResult('ERROR', 'DeleteError')
	else:
		returnResult('OK', '')

if action == 'updatepassword':
	if serverStatus():
		if not serverStop():
			returnResult('ERROR', 'StopError')
	
	if not serverUpdatePassword():
		returnResult('ERROR', 'UpdatePasswordError')
	else:
		returnResult('OK', '')


if action == 'sysload':
	serverSysLoad()