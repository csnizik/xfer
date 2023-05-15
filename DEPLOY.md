PODS USDA Dev Deploy Guide

This guide is strictly purposed for upgrading the current deployed version of PODS to the new version.

Build PODS tarball.
1.  If docker desktop is not running start it and start the cig_pods containers.	
2.  Open the project in VS Code
3.	Open git bash terminal in VS Code
4.	Switch to the develop branch, i.e. 
    $ git checkout develop=
5.	$ docker exec cig_pods-www-1 bash -c 'cd scripts; ./build_usda_tarball'
	The build will take a while. When it is finished an up-to-date pods.tar.gz file will exist in the scripts folder.

Prepare the 2nd server node before updating the 1st server node:
1.	Open a terminal in VS Code
2.	$ cd scripts
3.	$ scp pods.tar.gz <paccount>@10.203.24.63:pods_deploy.tar.gz
4.	$ ssh <paccount>@10.203.24.63
5.	$ sudo su root
    Enter paccount password when prompted.
7.	$ mv pods_deploy.tar.gz /app/upload
8.	$ cd /app/httpd/htdocs
9.	$ mv Alive1.html Alive1.out
	leave this terminal open for interactions on the 2nd server node coming later in this instruction set.

Deploy PODS on the first server node
1.	Open an additional terminal in VS Code
2.	$ cd scripts
3.	$ scp pods.tar.gz <paccount>@10.203.24.62:pods_deploy.tar.gz
4.	$ ssh <paccount>@10.203.24.62
5.	$ sudo su root
    Enter paccount password when prompted.
6.	$ mv pods_deploy.tar.gz /app/upload
7.  $ chown appadmin:appadmin pods_deploy.tar.gz
8.	$ cd /app/upload
9.	$ ./pods_1.1_deploy_init.sh deploy dev admin
	This completes the installation on the first server. 
    Check it out: https://pods-dev.dev.sc.egov.usda.gov 

Deploy PODS on the 2nd server node
1.	Return to the terminal that is logged into the 2nd server node.
2.	cd /app/upload
3.	./pods_1.1_deploy_second_server.sh deploy dev
4.	when prompted to put the server back in tier:
5.	Open another bash terminal
6.	ssh <paccount>@10.203.24.63
7.	sudo su root
    Enter paccount password when prompted
8.	cd /app/httpd/htdocs
9.	mv Alive1.out Alive1.html
10.	return to the previous terminal and press enter in response to the prompt allowing the script running there to continue.

This completes installation to the second server. 

Test by browsing to http://10.203.24.63 noting you will not be able to eauth, we just want to see that login screen to confirm success.

This completes the deployment, you may close the terminals opened for this deployment if desired
