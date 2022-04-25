# 5. Tools {#tools}

So far we have covered theory only but now it is time to get more into the technical aspects of the deployment process.

In chapter 2 about Goals we talked about the [maturity](#maturity) a deployment process usually goes through. Almost at the bottom (or top, depending on how you see it) of the list was a bullet on **tools**. The beauty is that it will replace or extend steps taken in the previous parts of the maturity process. This is the best place to start if you're setting up a sustainable deployment process for your application. If your current process are in the previous steps of maturity; replacing them with a tool is nothing major in most cases and something you gain a lot from.

What a tool provides for us is a way of gathering related parts of our deploy in one place. The core principle of a tool is **automation** which we've already covered in both chapter 1 and 2. It also provide a way of making your process readable and you will achieve a kind of self documentation for your process by using any of the tools. It could be considered a manifest for it.

Most tools do an excellent job in separating your environments. They make it easy to make every deploy to any environment repeatable. Perhaps you don't want to minify your Javascript and CSS files when you deploy to a testing environment? This makes debugging easier. But when you deploy to the staging or production environment you should do it. The tools will help you run the commands you want, when you want and where you want. Some of them also make sure that every person deploying have the correct endpoints to the environments for the deploys.

This book previously covered many tools , and those were *git hooks*, *Phing* and *Capistrano*. I've made the decision to remove those from the book, since it's my strong opinion that you shouldn't use them. We now have to amazing tools, Deployer [Deployer](#deployer) and [Magallanes][#magallanes] which are written in PHP and made for PHP, so use them. I've also removed *Rocketeer* since it's no longer maintained. If you really want the use the old tools, let me know and I can point you to the appropriate time in the git history for this books' repository.

{pagebreak}

## 5.1 Deployer {#deployer}

The flow and intuitive interface of [Deployer's](http://deployer.org/) makes me very excited about it. It's written in PHP and is sporting all the good stuff that you'd expect from a modern tool. This includes support for multiple servers and stages, deployment in parallel, atomic deployment and on top of that: it's fast. What I really enjoy about it is the fluent way you define your process in it, it provides a smooth and enjoyable experience. And in some cases everything works out of the box with their *recipes* available for a few frameworks and general use cases.

One could argue that the simplicity of the tool is what might make it suffer in some cases. It revolves around one file, a `deploy.php` file in your project's root directory. You could include other files in that, making your own folder structure for your process; and this could be seen as both a plus or a minus. It provides you with flexibility, but leaves a lack of structure and common practices. 

It has been around for a few years and have reached maturity and stability. As of writing this the maintainers are actively working on the tool and updating it on a regular basis, it also have a solid contributor foundation.

If you or your company is using Deployer, please consider to help out the developer by [becoming a sponsor](https://github.com/sponsors/antonmedv).

### Installation

Install it in your project by requiring the package. At the time of writing this the latest version is `6.8.0` so that is the one I'll be using.

{lang=bash}
~~~
composer require deployer/deployer:6.8.0 --dev
~~~

Then if you have your PATH configured correctly you can run the binary through:

{lang=bash}
~~~
dep
~~~

### Configuring

Start off by initializing it for your project, do this in the root folder of the project.

{lang=bash}
~~~
dep init
~~~

You will be prompted if you want to use one of the recipes provided by the tool, choose one if applicable or go with the *Common* one. I went with the *Common* recipe since I'll be deploying my [deploy test application](https://github.com/modess/deploy-test-application) which has some dependencies and a storage folder. You'll also be prompted for the location of the repository you wish to deploy, in my case `git@github.com:modess/deploy-test-application.git` which it automatically detected. Then you choose if you want to send anonymous statistics to help and aid the developers of the tool, if you don't have sensitive things you're deploying I suggest you answer *yes* here. Sharing is caring.

A `deploy.php` have now been generated in the root of your project, take a quick look at it. You can already tell the fluent and nice interface the tool have for structuring your deployment process. This is also one of the beautiful things with the tool, a single file is generated and that is all you need for now. When your application grows you might need to extends this and then you're able to do that.

I> ### Web server document root
I>
I> Deployer uses **current** as a symbolic link to the current deploy, we need our web server to use that as the document root.

#### Hosts

I'll start my cleaning up the host(s) I want to deploy to. I'll only have one since this is a simple test deployment environment, in a real world scenario you'll probably have multiple ones for staging, production, and so on.

{lang=php}
~~~
leanpub-start-delete
host('project.com')
    ->set('deploy_path', '~/{{application}}');
leanpub-end-delete
leanpub-start-insert
host('my-app.com')
    ->set('deploy_path', '/var/www/my-app.com');
leanpub-end-insert   
~~~

#### Shared and writable files/folders

We need to make sure that we have our `storage` folder. Setting up shared and writable folders and files comes right out of the box with Deployer, take a look at these configuration settings.

{lang=php}
~~~
set('shared_files', []);
set('shared_dirs', []);
set('writable_dirs', []);
~~~

We'll update this to:

{lang=php}
~~~
set('shared_dirs', ['storage']);
set('writable_dirs', ['storage']);
~~~

#### Restarting services

Then we have a task for restarting the PHP-FPM service, and in case you're running *nginx* as your web server this is something that should be done. I however don't suggest you restart it, but reload it. Depending on the linux distribution you're running on your server(s) you'll have to change this accordingly. This what I'm using:

{lang=php}
~~~
leanpub-start-insert
task('php-fpm:restart', function () {
    run('sudo service php-fpm reload');
});
after('deploy:symlink', 'php-fpm:restart');
leanpub-end-insert   
~~~

### Deploying

That's all! We can now deploy the application by running

~~~
dep deploy
~~~

A nice and simple list of tasks being executed by the tool is displayed.

### Tasks

Deployer comes with a set of predefined recipes as they're called, which consists of _tasks_. A task is a step performed in the deployment process necessary for your application to work properly once deployed. This can range from simple one line bash commands such as `php artisan migrate` to more advanced steps such as telling your load balancer that "I'm deploying, leave me out of rotation for now" and then telling it that you're back.

When I initiated my project the recipe I used generated the following tasks for deploying:

{lang=php}
~~~
desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);
~~~

All of these come out of the box with the tool because they are general tasks for deploying a PHP application. If you write your own tasks you can either add your task to this list where you see fit or use a hook, as can be seen in the task for restarting PHP-FPM:

{lang=php}
~~~
task('php-fpm:restart', function () {
    run('sudo service php-fpm reload');
});
after('deploy:symlink', 'php-fpm:restart');
~~~

Here we tell it that after the `deploy:symlink` task is executed, execute our task `php-fpm:restart`.

When your application have special requirements you can easily write your own tasks and make sure they are executed just where you need them. If needed you can also start splitting up your `deploy.php` into more parts, having one monolithic file might not be good if it grows too much.

{pagebreak}

## 5.2 Magallanes {#magallanes}

