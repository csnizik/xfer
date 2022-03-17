

$> composer require pods/pods (load from a file)


* We want the repo to start at the `www` level.


Does the install process look like 

1. Install FarmOS
2. Install PODS

or

1. Install PODS and as a dependency, install FarmOS


1. Do git pull (grabs custom code and cfg )
2. Run composer workflow
	* Bring down third party code (is farmos third-party? Yes)
3. If needed, run `drush updb` to run SQL code in the modules.
   1. This is to update the schema of the existing Drupal instance.



Ex. Add column to Asset Type
1. We would write SQL statements in the cig_pods.install 
   1. These SQL statements are associated with some update ID.
   2. PHP procedural functions inside of the


"When you run drush updb, you are running the updates present in the .install file"


To ask FarmOS guys, why under `config/install` instead of just `config`?

Are the the `www/web/modules/<module_name>/config` folders managed by farmOS or managed by external 


* Need to schedule a meeting with the FarmOS guys to understand the structure of the FarmOS project? (With Peter) 



* If we want to roll back the database,  we could use Docker to roll back the database
^^^ as a fail safe method

* Rolling back the code is trivial.
* Rolling back the db can be done with Docker.

Use Docker container image of db to save "check-points"
* If it crashes, we can roll back to the check-point 