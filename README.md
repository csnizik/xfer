

# Installation Instructions

## Clone the Repo
`git clone `

(If you have **not** yet installed farmOS via docker, follow this [guide](https://docs.google.com/document/d/14vZQA70orWKNrOkioLLsBnJoZ5Ck9-LloS5TbXQpDw4/edit)
## Copy over the folder.

`cp -r hook_test \<YourFarmOSRepo\>/www/web/modules`

## Open farmOS

If farmOS is not yet booted, run `sudo docker-compose up`

## Navigate to Settings > Extend in farmOS
## Type "hook" into the filter
## Click the box next to "Hook Test"
## Hit the install button

# Uninstallation Instructions

## Navigate to Settings > Extend in farmOS
## Hit the uninstall tab
## Type "hook" into the filter
## Click the box next to "Hook Test"
## Hit the install button
 
# Development instructions

It is recommended to use a symlink so that there are not two competing instances of git

`ln -s <path>/<to>/<repo>/cig-farmos-data-automation/hook_test/ <path>/<to>/<farmOS>/www/web/modules`

## Installing and Uninstalling
Use the installation and uninstallation as above.
You will only need to run installation/uninstallation steps if you make changes to the .yml files. PHP code changes are reflected immediately without the need to install/uninstall