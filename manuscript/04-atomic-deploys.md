# 4. Atomic deploys

Once upon a time there was a team of developers working on a service for sending e-mail campaigns. Their goal was that the system would allow users to manage subscribers and e-mail templates, so that the user could create e-mail campaigns and send them to their subscribers. The service got its first early adopters and began delivering successful campaigns with happy users. The focus for the team was feature development, adding features that was requested by customers or the ones they felt needed for the service.

The team had a simple infrastructure in place, and they had a plan for when the service (hopefully) had to scale up. They divided it in two logical components. The first component was the one presented to end users, a kind of a mishmash of CMS and CRM. This web component was responsible for creating the e-mails and sending them over to the mail server. The second component, the mail server, had a single responsibility of receiving pre-configured e-mails and sending them out. The components were deployed on two separate cloud instances and the plan was to scale horizontally if the service gained enough traction. When it came to deployment the lead developer would access the instances, pull down the latest changes and complete the steps necessary for a deploy. This was manual labor.

The service continued to grow, more and more users signed up for it and started using it as their main e-mail marketing channel. Not only did more users sign up but also users with a lot larger subscriber lists migrated over from other similar services. To import subscribers the user had to copy and paste their list(s) into a textarea with one e-mail address on each row. This was insufficient for many users and the most requested feature became an improved importer, being able to upload files with tens, or even hundreds, of thousands subscribers to be imported. The team got to work and once they had a completed feature they rolled it out to the public and immediately notified users about their grand new feature. It became a frequently used feature, especially for users migrating from other services.

A few weeks went by and suddenly the team started receiving e-mails and phone calls from annoyed users who had used their import feature. The users repeatedly claimed that when they had uploaded their file, they had to wait for a long time before anything happened and all of a sudden the import was canceled for no apparent reason. The team started a thorough investigation and they dove deep into the code and logs trying to reproduce the problem. They set disproportionate time and memory limits for code execution, but to no avail. Instead they tried looking at patterns for when in time this happened, could it be an issue when the load is high? They looked at different monitored values for their servers but couldn't find anything. But what they did find was that the e-mails and phone calls they received with complaints often happened every other week on Mondays. And usually continued to around Wednesday the same week.

It now occurred to the team that the pattern followed their release cycle. They worked in two week sprints and deployed every other Monday. It was these Monday's that the problem occurred. Upon this realisation it became quite obvious what caused the issue. When deploying their application they always restarted the *php-fpm* service just in case. When the service restarted, all users who was running an import (a long running PHP process) would get their import canceled because all PHP processes were killed. It now occurred to the team that their deploy process wasn't *atomic*.

## 4.1 What is atomicity?

What is an atomic deploy? It's all about hiding things from the rest of the world. Let's take a look at one definition:

> In concurrent programming, an operation (or set of operations) is atomic, linearizable, indivisible or uninterruptible if it appears to the rest of the system to occur instantaneously.[^atomicity]

There are two parts here that is interesting. First of all, atomicity is achieved when one or more operations **appear** to happen instantaneously, and also that a synonym for it is **uninterruptible**. Some call this *Zero Downtime Deployment*, like [Envoyer](https://envoyer.io/) does. Let's go back to the teams issue. When deploying, restarting the *php-fpm* service seemed instantaneous to the person deploying, it was a matter of milliseconds. But in this case it proved to interrupt other parts of the system which broke the atomicity of the deploy.

What we see here is that when an application reaches a critical mass of users and/or a certain complexity, you need to deploy without any interruptions to the service. Striving for your deployment to be fast and responsive just won't cut it; it needs to be atomic. In the previous example, it's not a matter of speed, but of reliability. Even a nanosecond interruption in the *php-fpm* service will result in breaking behaviour for the end user.

Perhaps you could ask yourself the question, or at least argue for, that it isn't that big of a deal if the user notices or something unintended happens. I could go along with that to a certain extent. However if you're able to solve it, why not keep your users as happy as possible? Interruptions or unintended behaviour could also cause data corruption that you would have to deal with. If we take a look what the other side of the fence might look like, I would say it's quite bad. That would encompass scheduling, notifying users in advance and take your application offline while deploying. In the long run this will most likely not work out. The obvious example is when you need to deploy an urgent hotfix or when the service scales up.

## 4.2 Achieving atomicity

Atomic deploy is when you switch between the previously deployed version and the new one as quick as possible. I'm talking milliseconds or even less, anything slower than that can't be considered atomic. Doing this without your users even noticing is key. Remember the part about making it **appear** instantaneous to the rest of the system? Your application does not need to be very complex or be deployed on an advanced infrastructure, it's enough that somewhere in your process the different parts can come out of sync from each other. Dependencies on packages could get out of sync with your code if the code is updated before dependencies. You also never want to interrupt any current running processes as shown by the example.

There is a few things that usually need to be in order for achieving atomic deploys and I will go through them below. The next chapter will cover deploy tools and there I'll show you how to do this with the various tools.

## 4.3 Concept of builds

Even though we as PHP developers seldom had to consider the concept of builds of our application it's getting more frequent and we must internalize this for achieving atomic deploys. If we can not create a separate build while deploying, it's not possible. Or actually we could do that with a smart infrastructure, shutting down traffic to servers that are in "deploy mode". But for the sake of argument and simplicity we won't go down that route. There is a simple way of doing this on one server; all we need to do is create a build in a folder that is not being served to our users. Then we can do the old switcheroo on the currently deployed build and the *to be* deployed build without the user noticing.

Having an appropriate folder structure for allowing this is simple. I will later in this chapter propose a structure, but there are other components to this that I want to discuss prior to it. For now just consider a folder with your application with all dependencies and configuration complete as a build. I would consider a build complete if you can put it anywhere on your server and serve it to your users.

When switching builds you should also save a number of older builds, perhaps the five or ten previous builds. This allows for quick rollbacks and can be crucial if a deploy needs to be reversed. How many you should save is impossible to answer so just go with a number you feel comfortable with, it also depends on your release cycle. If you deploy once a week or once a day you can probably without any discomfort save your five or ten last builds. If you're deploying continuously on every commit then you should probably save a lot more than five.

## 4.4 File persisted data (shared data)

When switching out a previously deployed build for the new one you must assure that no file persisted data is lost. One example of this is if your application stores session data or user uploaded files inside your application folder. If you switch out the old build for a clean new one, users might be logged out or data could be lost. And this is never an ideal scenario.

On the other hand there could be file persisted data that you do not care if it gets lost, maybe you would even prefer that it does. If your application has a cache for rendered templates you'd probably prefer if that cache is wiped so your underlying logic and presented views won't get out of sync. In these cases just make sure that your new build replaces or wipes these folders.

What is important here is to identify the files and/or folders that needs to remain persistent between builds. I refer to these files or folders as **shared data** and I will show how to deal with this in the proposed folder structure.

## 4.5 Symbolic folder links

Although it's not really necessary, but to grok symbolic folder links (*symlinks*) is strongly advised. What we want to do with symlinks is also possible by just copying or moving directories. But to make things atomic we should leverage symlinks since they are pretty much instant.

A symlink looks like a folder or a file that appear to exist in a location. But the symlink is a reference to a file or a folder in another location. It allows us to instantly switch out which folder or file a symlink is pointing to. When we deploy and want to switch out the old build, we just update a symlink to point to our new build instead. Likewise we will do this for shared data. In that way we make the switch extremely fast and can make sure that our shared data is there when we need it and is stored in one location only.

For Linux users, it's the [`ln`](http://www.unix.com/man-page/posix/1posix/ln/) command. It's possible on Windows but it's complicated and I suggest doing a Google search for it.

## 4.6 Proposed folder structure {#atomic-folder-structure}

The proposed folder structure needs to reside inside a root directory somewhere. Where that is doesn't really matter, it just needs to contain the following structure. How you develop your application is not of importance and this example assumes that the folder structure is on the server serving the application.

The names of the folders inside the root are arbitrary, name them as you please. My examples that follow will use this structure and it looks like this:

~~~
├── builds/
│   ├── 20141227-095321/
│   ├── 20141229-151010/
│   ├── 20150114-160247/
│   ├── 20150129-083519/
│   └── 20150129-142832/
├── latest/ <- symlink to builds/20150129-142832/
├── repository/
│   ├── composer.lock
│   ├── composer.json
│   ├── index.php
│   └── sessions/ <- symlink to shared/sessions/
└── shared/
    └── sessions/
~~~

**builds/** - this folder contains the X number of builds that I discussed earlier. Perhaps the current one deployed and the four previous ones. I suggest naming all builds with a timestamp with date and time down to seconds, you never want a build to overwrite a previous build by accident.

**latest/** - symlink to the current build that is deployed. Your web server will use this folder as its document root for your site. Unless there is a subfolder that should be served, depending on your set up and framework.

**repository/** - the folder where the repository resides and this is what we will make builds from.

**shared/** - any shared data that needs to be persisted between builds will reside here. It can be both files and folders.

## 4.7 Pseudo deploy process

The process for making an atomic deploy is then straight forward:

* Update the code in *repository/*, probably through pulling the latest changes from your remote.
* Update dependencies and configuration, this is also done in *repository/*.
* Make a copy of *repository/* to a folder in *builds/*.
* Update the symlink *latest/* to point to the new build that was copied.
* Remove excessive builds in *builds/*.

Need to perform a rollback? Update the symlink *latest/* to point to the previous build you want deployed.

[^atomicity]: [http://en.wikipedia.org/wiki/Linearizability](http://en.wikipedia.org/wiki/Linearizability)
