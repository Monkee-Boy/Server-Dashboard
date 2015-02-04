# config valid only for current version of Capistrano
lock '3.3.5'

# Load up the mBoy gem
Mboy.new() # Setting initial defaults.

set :application, 'mBoyDashboard' # no spaces or special characters
set :project_name, 'Server Dashboard' # pretty name that can have spaces

set :repo_url, 'git@github.com:Monkee-Boy/Server-Dashboard.git'

# Default value for :linked_files is []
# set :linked_files, fetch(:linked_files, []).push('config/database.yml')

# Default value for linked_dirs is []
set :linked_dirs, fetch(:linked_dirs, []).push('app/config/production', 'app/storage/cache', 'vendor', 'node_modules', 'public/bower_components')

namespace :deploy do
  STDOUT.sync

  desc 'Build'
  after :updated, :deploybuild do
    on roles(:web) do
      within release_path  do
        invoke 'build:composer'
        invoke 'build:npm'
        invoke 'build:bower'
        invoke 'build:artisan'
        invoke 'build:migratedb'
      end
    end
  end

  desc 'Tag this release in git.'
  Mboy.tag_release

  desc 'mBoy Deployment Initialized.'
  Mboy.deploy_starting_message

  desc 'mBoy Deployment Steps'
  Mboy.deploy_steps

  desc 'mBoy HipChat Notifications'
  Mboy.hipchat_notify

end

namespace :build do

  desc 'Install/update node packages.'
  task :npm do
    on roles(:web) do
      within release_path do
        execute :npm, 'install --silent --no-spin' # install packages
      end
    end
  end

  desc 'Install/update bower components.'
  task :bower do
    on roles(:web) do
      within release_path do
        execute :bower, 'install' # install components
      end
    end
  end

  desc "Database migrations."
  task :migratedb do
    on roles(:web) do
      within release_path do
        execute :php, "artisan migrate --force" # run migrations
      end
    end
  end

  desc 'Setup Linode Artisan.'
  task :artisan do
    on roles(:web) do
      within release_path do
        execute :chmod, "u+x artisan" # make artisan executable
      end
    end
  end

  desc "Install composer dependencies."
  task :composer do
    on roles(:web) do
      within release_path do
        execute :composer, "install --no-dev --quiet" # install dependencies
      end
    end
  end

  desc 'Additional deploy steps for :build'
  before :npm, :deploy_step_beforenpm do
    on roles(:all) do
      print 'Updating node modules......'
    end
  end

  after :npm, :deploy_step_afternpm do
    on roles(:all) do
      puts '✔'.green
    end
  end

  before :bower, :deploy_step_beforebower do
    on roles(:all) do
      print 'Updating bower components......'
    end
  end

  after :bower, :deploy_step_afterbower do
    on roles(:all) do
      puts '✔'.green
    end
  end

  before :composer, :deploy_step_beforecomposer do
    on roles(:all) do
      print 'Update composer components......'
    end
  end

  after :composer, :deploy_step_aftercomposer do
    on roles(:all) do
      puts '✔'.green
    end
  end

  before :artisan, :deploy_step_beforeartisan do
    on roles(:all) do
      print 'Making artisan executable......'
    end
  end

  after :artisan, :deploy_step_afterartisan do
    on roles(:all) do
      puts '✔'.green
    end
  end

  before :migratedb, :deploy_step_beforemigratedb do
    on roles(:all) do
      print 'Migrating database......'
    end
  end

  after :migratedb, :deploy_step_aftermigratedb do
    on roles(:all) do
      puts '✔'.green
    end
  end

end
