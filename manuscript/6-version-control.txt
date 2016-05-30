# 6. Version control {#chapter-version-control}

This is not a book about version control, neither will this chapter go into how and why you should version control. This chapter will discuss a workflow that is suitable for optimizing your deployment process. You do this through a *branching strategy*.

As said before, Git will be used as a reference in this chapter. But the concepts can probably be applied to most version control systems if you're able to create branches. Why Git is so beneficial in a branching strategy is because of its low overhead for those operations. Branching and merging is mostly fast and simple. Conflicts will arise in all systems.

The overhead in Git when you create a new branch is minimal, we're talking around **4 kilobytes**! And it only takes a few milliseconds of your life. I'm pretty sure no other major version control system can contest that. If you are interested in how this is possible, I'll provide some resources for further learning on Git.

And why is version control important for your deployment process? You might ask. It comes down to being able to get code to its proper places. With a good version control system and a workflow, you can choose what you push code to the different environments. And for this you use a branching strategy. I will propose one in this chapter and I'm not saying that's the one you have to use. There are many strategies out there and you should try to find one that fits you and your team.

The term branching strategy is just a fancy way of explaining when, how and where you branch from and merge to. Think of it as a schematic for your code flow between branches and environments.

## 6.1 Git-flow

I'm proposing the git-flow branching strategy that is widely used and have a proven record. It probably is the most used branching strategy for Git. It started with a guy named Vincent Driessen who published a blog article called [A successful Git branching model](http://nvie.com/posts/a-successful-git-branching-model/). He wanted to share his workflow he had introduced for both private and work related projects. Maybe he wasn't the first, but his article is used as the main reference for git-flow. It has since then been praised and adopted by many people who work with software development. Many companies and open source projects have introduced it as their branching strategy.

There is nothing magical about it though, no unicorns or any of that shit. It's just a branching **strategy**. You can work according to it just using the git binary, or you can use a [git extension](https://github.com/nvie/gitflow) for it. But all in all it's a way of which branches you have, how you name them and how you merge them.

Some tools have adopted it too. One of the most popular Git clients, [SourceTree](http://www.sourcetreeapp.com/) by Atlassian, have support for it out of the box. You don't even have to install git-flow on your system to work with it in the application. It can convert a current repository to support the workflow and then it helps you with all the steps. I recommend you use a Git client. It makes things a lot simpler and you get a better overview of your repository. Only when I need to do more advanced operations like going through the reflog or such, I will resort to the command line.

## 6.2 Main branches

There are two branches that have to exist in order for git-flow to work. These are your main branches and serves two different purposes. They are called *master* and *develop*. When converting a repository for git-flow you will end up with these branches. You can name them whatever you want, but I will refer to them as their original names.

### Master branch

This is the branch you have in your production environment. It should always be stable and deployable. I'm trying to come up with some other things to say about it, but there is nothing more to it actually. The code here is the face of your application that its users see.

### Develop branch

Your develop branch is where all deployable code should be merged to. This is a branch for testing your code before it gets merged into *master*. Once code is in the *develop* it can be pushed to different environments so it can be tested thoroughly. Perhaps you have a QA-person/team that can test your features with manual and/or automated regression tests. But it should also pass some sort of automated testing (continuos integration) like Jenkins or Travis. And your fellow developer colleagues could test it in a shared environment.

The code here should be stable enough to be merged in to master at any point. But of course bugs will be spotted and dealt with and that's the whole point of this branch. You want your code as tested as possible so you can merge it into master and deploy it into production. You want to be able to do this with as little uncertainty as possible.

## 6.3 Feature branches

All features you are working on should have its own branch. These branches are prefixed with *feature/*. If for example you're working on a OAuth implementation, it be called *feature/oauth-login*. How you name the branches after the prefix is completely up to you though. If you use a service for issue tracking it can be good to have the issue number in the name as well for traceability.

They all start out from develop, and up there as they are done. So a feature branch will branch out from develop and when the feature is complete it goes back there. This is true for all feature branches.

## 6.4 Release branches

Before a deploy you will create a release branch. It branches off from *develop* and will get a *release/* prefix. Depending on how and what you deploy, the name will differ. If you're working in sprints it could be the sprint number, eg. *release/sprint-39*.

When you have your release branch it can be pushed to the different environments and tested. One of the most important places to do this is in your staging environment. You want to make sure it works in an as close to production environment as possible. Because when it has been tested it will be deployed.

So you have a release branch you want to deploy, then what? Then it will be merged into **both** develop and master. It will also be tagged with an appropriate tag, such as a version number. This ensures that all your deploys correspond to a tag. It's an important concept when you want to roll back, because you then want to roll back to the previously deployed tag.

## 6.5 Hotfix branches

These are the branches you hope to never use. But you will. Why? Because these are the branches you use when something went wrong and needs to be fixed asap. If you discover some nasty bug in production that requires a quick response, a hotfix branch is your go to guy. Can you guess the prefix for it? Yes, it is *hotfix/*.

When you create one it will branch off from *master*. Since you might already have new changes in *develop*, you do not want those to end up in production yet. You will then fix your bug in this branch. If you have time this should be tested as much as possible too, but sometimes you have to deploy it ten minutes ago.

They are treated just as release branches when merging. Shove it into *develop* and *master*, create a tag and off it goes to production.

## 6.6 Labs branches

This is outside the scope of git-flow and is only a personal preference I have. Often I end up with things I want to play around with that is not tied to actual deliverables. Then I want to separate my playground from my work stuff, so I prefix them with *labs/*. Often these branches will be played around with and then thrown away. If they do end up being complete I merge them in to *develop* like a regular feature branch.
