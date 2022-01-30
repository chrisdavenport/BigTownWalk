# BigTownWalk
The next Shrewsbury Big Town Walk will take place this year on Friday 15th to Monday 18th April 2022.

This code is the public repository for the custom Joomla
component that will support the event.  The public website is here: http://bigtownwalk.org.uk/ 

Most of the code is written in PHP with JavaScript used to power the Google Maps.
Feel free to look at the code, play with the website and report issues.

Code and/or documentation contributions are welcome.
Please submit pull requests against the staging branch. 

# Essentials

## Build
To build the package from source you need to clone this repository then run the build script.
Use the following commands:
```
git clone https://github.com/chrisdavenport/BigTownWalk.git
cd BigTownWalk
composer install
vendor/bin/robo build
```

Once built, the completed package file will be in
```
dist/pkg-bigtownwalk-[version].zip
```

where **[version]** is the package version number.
The following symbolic link will always point to the current build package.
```
dist/pkg-bigtownwalk-current.zip
```

## Installation
* Build the package (see above).
* Install into Joomla using the regular extension installer (Extensions -> Manage -> Install).

## Updates
The Joomla auto-update mechanism is used.  See _update.xml_ for details.

# Development

## Version numbering
Version numbers should follow the [Semantic Versioning](https://semver.org/) scheme whenever possible.

## Branch organisation and policy

* The __main__ branch always contains the current stable release code.
* The __staging__ branch contains the currently accepted code for the next release.
  All pull requests should be made against this branch.  All automated tests must pass
  before a branch will be merged into __staging__ (see later).
* All bug fix or feature branches should, as a matter of convenience and convention, include
  an issue number where one is available.  For example, __fantastic-new-feature-issue-123__.
* Release branches begin with the __release__ prefix.  For example, __release-1.2.3__.
  Only release branches are ever merged into the __master__ branch.

## Repository organisation
All source code can be found in the __/src__ directory.  The organisation of directories within
the __/src__ directory is similar to the final organisation of the installed package and is that
required by the __jorobo__ package for build operations.

## Help files
Note that the local help files (in /administrator/components/com_bigtownwalk/help/en-GB)
are auto-generated from the source files in /docs during the package build process.
To make changes to the help files, edit the source files in /docs then rebuild the package.

The reason for doing this is that the build process supports transclusion using the
following syntax:

* **{{filename}}** will transclude the file /docs/filename into the current file.
* **{{EXCLUDE}}** will exclude the current file from being copied into the package file.

# Testing
Code style and unit tests may be run on your local machine.
The same tests are executed automatically on each and every commit to the repository using Github Actions.

## Code style
At the present time the code style tests must be run in a Docker container (see below).

## Unit tests
To run the unit tests you will need a local MySQL (or compatible) database with a database called "_bigtownwalk-test_".
A user with the following credentials must have CREATE TABLE and DROP TABLE permissions as well as the usual CRUD operations.

* Username: test_user
* Password: test_user_password

Run the unit tests with the following command:
```
vendor/bin/robo run:unit
```

## Unit test code coverage
A code coverage report showing the extent to which code has been covered by unit tests, can be obtained
with the following command:
```
vendor/bin/robo run:coverage
```
To view the report, point your web browser at:
```
code_coverage/index.html
```

## Joomla namespace checker
This is a script which scans the project directory recursively looking for PHP files
containing calls to the old pre-namespaced classes (eg. JFactory).
It currently generates quite a long list, so it hasn't been included in the CI pipeline yet.
Run it with the following command:
```
vendor/bin/robo run:namespace
```

# Continuous integration
Every commit to the repository triggers the code style tests using Github Actions.
As a matter of policy pull requests will only be accepted if they pass all these tests.
Note that the unit tests are not currently run automatically on commit because I can't
get the MySQL server to work in Github Actions.  A fix for this would be welcome.

## Github Actions
The environment used for the pipeline can also be created locally in Docker as follows:

```
git clone https://github.com/chrisdavenport/BigTownWalk.git
cd BigTownWalk
composer install
docker run -it \
 --volume=$(pwd):/BigTownWalk \
 --workdir="/BigTownWalk" \
 --memory=2048m chrisdavenport/joomla-code-style /bin/bash
```

Then run the following command:

```
vendor/bin/phpcs --report=full \
 --extensions=php -p \
 --standard=$(pear config-get php_dir)/PHP/CodeSniffer/Standards/Joomla \
 --ignore=*/tmpl/*,*/layouts/*,*/vendor/* src
```

The Dockerfile used to create the chrisdavenport/joomla-code-style image is included in case the source ever goes missing.

# Release procedure
* Make sure that the __staging__ branch is up-to-date with all the code to be included in the release.

* Create a new release branch from the staging branch, called __release-x.y.z__ where __x.y.z__ is the version number.
```
	gl switch staging
	gl branch -c release-[x.y.z]
	gl switch release-[x.y.z]
```

* Run composer to get the code required for building the package.
```
	composer install
```

* Update overview.html with the release notes and changelog.

* Upload overview.html to the update server.

* Update the version number in __jorobo.dist.ini__ then copy __jorobo.dist.ini__ to __jorobo.ini__

* Replace the __DEPLOY_VERSION__ tags with the release version number.
```
	vendor/bin/robo bump
```

* Update the copyright headers.
```
	vendor/bin/robo headers
```

* Build the release package.
```
	vendor/bin/robo build
```

* Upload the package file to the update server.

* Update __update.xml__ (in the root directory) with the release version number and the correct name of the release package file.

* Calculate new checksums using sha256sum, sha384sum and sha512sum and enter them in the appropriate
  elements in the __update.xml__ file.

* Upload __update.xml__ to the update server.
Once this file has been uploaded the new release will be publicly available as an auto-installable release.

* Commit changes made in the release branch.
```
	gl commit -m 'Release [x.y.z]'
	git push origin release-[x.y.z]
```

* Check that the Github Actions build and test process completes without error.

* Merge the release branch into the __master__ branch.
```
	gl switch master
	git pull origin master
	gl merge release-[x.y.z]
	git push origin master
```

* Tag the release in BitBucket by going to the commit and clicking on the __+__ sign on the right-hand side.
  Tags are purely numeric (eg. '2.3.0').

* Update the __staging__ branch to bring it up-to-date with the latest __master__
```
	gl switch staging
	gl merge master
	git push origin staging
```

* All done.  You can now resume merging pull releases into the __staging__ branch, ready for the next release.
