# Introduction

## Background

The PHP language is in an interesting phase right now. After lagging behind on the more traditional software development practices for too long. Practices that have been more or less considered as given in other communities. Continuous integration, package management, dependency injection and adopting object oriented programming to its full extent to name some. But as of PHP 5.3 (released on 30 June 2009) there are no more excuses to why you can't write modern and clean code. The community has risen up to the challenge. A great number of people have stepped forward, teaching and building tools for accomplishing these practices.

Yet we seem to forget deployment in this. It's time to bring it to the table for discussion. Great tools and services are out there. But few resources are available on how to set up, maintain and optimize your deployment process. In this book I hope to shed some light on what is important and how you achieve it.

Deploying is not about pushing changes to a production server, but an important part of the [software development process](http://en.wikipedia.org/wiki/Software_development_process). You need put thought in to each step. Applications are often unique little snowflakes and you need to tend to its special needs. When working in a team is when you most of all need a great deployment process. The team should work in certain ways to enable the process to be beneficial for all parties involved.

Every software application has a life cycle that the deployment process supports and is necessary for. You want to be able to maintain the application, roll out new features and do bug fixes without constant pain and headaches while doing it. You should be able to manage your branches, push your code, run the appropriate tests, migrate your database and deploy the changes in a fast and confident manner. With a good deployment process and a work flow that enables this, it will be a breeze. If you can do a fast and easy rollback too, your confidence in pushing code will benefit a lot.

In a more and more agile software development world, being able to deploy is important. Release cycles are getting shorter and shorter and some organisations even push it to the limit with [continuous delivery](http://en.wikipedia.org/wiki/Continuous_delivery). In an environment with short release cycles, the importance of the deployment process intensifies. Not being able to deploy or rollback fast enough could end up slowing down your entire development process.

## Who it's for

You are already familiar with PHP and you're not afraid of the command line. But you could also be a manager of a software development team that is a part of deployment on a regular basis.

Legacy is not solely a code issue but a process issue as well. Are you looking to streamline your deployment process or you want to scrap your current one and start of fresh? Then this book is for you.

## Outside its scope

I consider server provisioning an almost crucial part of deploying your application but it's too big of a topic for this book. It could without a doubt be a book on its own. I also don't want this book to focus on any framework or tool but keep the content and name broad instead of something like *Deploying PHP applications to Amazon Web Services with Ansible*.

The tools and commands used will be outside the scope unless it's a deployment tool (then it will have a dedicated section). I will make examples with Git, Composer, Grunt, PHPUnit and other tools. If you want to learn more about the tools there are extensive amount of books, screencasts and blog posts to find, Google is your friend.

## Assumptions

I know it's not nice to make assumptions about people, or software. I'm still going to do it to some extent in this book. I will make them when I approach examples, but I'll not judge you, your team, or your application in any way.

#### Where you deploy to

You will need a hosting environment that you are some what in control of. If you are not able to install software or run commands it will limit you in what you can achieve. Whether it's a hosted server, co-location server or a VPS does not matter. To use everything in this book you need control over it for installing software, changing configuration, etc.

#### Git

The base of some topics discussed will use Git as version control. Why? Because I think it enables work flows that is best suitable for a good deployment process. There's a chapter on the topic of Git for version control and branching strategy for a good deployment process. I could've named it *Git version control*. But I'll leave the name without Git in it since there are perhaps a lot in there that you can apply to other version control systems as well. Other than Git I have worked with Subversion and Perforce but when I found Git and started incorporating it in my work flow I have never looked back.

#### Both ends

There will also be an assumption about your application that it's not a pure backend application. If your application is a REST API for example with no frontend, it will not matter though. I will give some general examples on how to manage builds for your frontend as well, but all the commands used will be arbitrary.

## About the author

I have been developing PHP applications for over 15 years now. During this time I've developed and deployed a great variety of applications. The scale of these applications have been from a few hundred users to over 250 million users.

Oh, by the way I'm from Stockholm, Sweden. That means I'm writing a book in my second language, and I would appreciate all the help I can get when it comes to spelling and grammar. If you find anything, please create an issue in [this repository](https://github.com/modess/deploying-php-applications) or fork it, fix it and send me a pull request. Thank you!

## Code samples

In the [repository on github](https://github.com/modess/deploying-php-applications), you can find code samples structured by chapter. Any substantial amount of code used in the book is available there for reference and use.

## Thanks to

My friend and talented designer extraordinaire **Joakim Unge** for the awesome cover image. Look at it, it's a fucking rocket ship! Nowadays he's a developer and you can find him at [https://joakimunge.se/](https://joakimunge.se/).

---

My sister **Jenny Modess**, the talented copywriter, for proof reading from a non-technical perspective. Keeping my spelling, grammar and storytelling in check!
