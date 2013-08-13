set :application, "test"
set :repository,  "git://github.com/jaguerra/bootstrap_package.git"

set :scm, :git 

# Don't forget to add SSH alias config:
# $ vagrant ssh-config --host typo3deploy >> ~/.ssh/config
#
# Otherwise you should change the hostname to something different

role :web, "typo3deploy" 
role :db, "typo3deploy"

# if you want to clean up old releases on each deploy uncomment this:
# after "deploy:restart", "deploy:cleanup"

set :git_enable_submodules, 1
set :deploy_to, "/var/www"
set :use_sudo, false
set :user, "vagrant"

set :shared_children,   ['htdocs/fileadmin', 'htdocs/uploads', 'private']
set :deploy_via, :remote_cache

# if you want to deploy via SCP copy instead of remote git checkout uncomment this:
# set :deploy_via, :copy

namespace :db do
    desc "Upload and import SQL dump"
    task :push, :roles => :db, :except => { :no_release => true } do

	run "#{try_sudo} mkdir -p #{shared_path}/_tmp"
        
	script_file = "scripts/DbImport.php"
	script_dest_file = "#{shared_path}/_tmp/DbImport.php"
	top.upload(script_file, script_dest_file)

	file = "config/media/dump.sql"
	dest_file = "#{shared_path}/_tmp/dump.sql"
      	top.upload(file, dest_file)

	run "#{try_sudo} php #{script_dest_file} #{dest_file}"

    end
end

namespace :media do
    desc "Upload and import media files"
    task :push, :roles => :web, :except => { :no_release => true } do

				['fileadmin.zip', 'uploads.zip'].each do |filename|
      			file = "config/media/#{filename}"
						dest_file = "#{shared_path}/#{filename}"
      			top.upload(file, dest_file)
						run "#{try_sudo} cd #{shared_path} && #{try_sudo} unzip -o #{filename} && #{try_sudo} rm #{filename}"
				end

    end
end

namespace :typo3 do
		desc <<-DESC

        $ cap typo3:config FILE=configuration.php

    DESC
    desc "Upload local configuration file"
    task :pushconfig, :roles => :web, :except => { :no_release => true } do

				file = ENV["FILE"]
      	abort "Please specify at least one file (via the FILE environment variable)" if file.empty?

				dest_file = "#{shared_path}/private/Configuration.php"
      	top.upload(file, dest_file)

    end
end

