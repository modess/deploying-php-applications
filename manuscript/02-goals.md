# 2. Goals {#chapter-goals}

I hope you have decided that you want to improve your deployment process. Well, that is probably why you're reading this book at this moment. You should at this point establish some markers. Ask yourself the questions of where you are, and where you would like to be. These are essential questions in reaching goals. Between here and there you should also set some milestones, since sensing that you achieve something is always important. This is starting to sound like a self help book, but I'm of course talking about where your deployment process is and where you would like it to be.

We have some topics we need to go through before we get to the actual list of goals. Understanding the background of the parts will help you better grok the big picture.

## 2.1 How it generally begins

Take a deep breath and imagine a peaceful lake in your mind. No just kidding, stop with that. This is still not a self help book.

Since the deployment process is like any other process in the software development cycle, it will mature in certain ways. It generally starts at a similar place. This place has seen a lot of developers and it will continue to see a lot of other developers pass by. What I want is to show you what kind of place that is and why you should try to avoid going there. The main reason that place is bad is that it's **at the end** of everything. I realize that I said that the beginning is at the end, but let me explain what I mean before you call me an idiot.

An application starts with an idea. Then some wireframes (either mental or actual ones). Then perhaps some design, and of course some code. The first beta is ready for release. Wow that was fast, but anyway. Success, get the minimum viable product out there! This is where people stop and think "Oh right, we need some kind of hosting". Someone with a credit cards pays for the hosting and a developer has to deploy the code to it. Of course that's not a problem for the developer. But there is a big risk of this part not receiving the tender loving care it deserves and needs. No deployment tools is used, no automation is put into place. I'm not saying this is the case for all project, but it's a generally good estimation for most projects.

Do you see the issue at hand here? **Deployment is in most cases an after thought**. It's something most feel they have to do, not want to do. Most wouldn't get out of bed earlier on a Monday because they are so eager to set up a sexy deployment process. This will often be a "fix things as you go" kind of process with quick and dirty fixes, duct taping that shit if it's necessary. This is something I refer to as *duct tape deployment*, and yes you can quote me on that. This is more than often a one man or one woman show. If someone else would like to deploy they wouldn't be able to. They would first have to ask the "one man/woman deploy army" how it's done, what the passwords are, which paths are used, where the database is and everything else that comes with it. Efficient? No. Sustainable? No.

What we need to start with is making the deployment an important part in our applications' lives. It should be there for our application as a supporting and nurturing parent. Would you let your kid go to the first day of school without you being there? Of course not. The school system in the United States is fond of their *No Child Left Behind Act* and I would like to propose a *No Application Left Behind Act*. Where no applications gets left behind because of a bad deployment process. It might be a bad analogy, since they have a crappy education system in the United States. But I hope I got my point across anyway.

## 2.2 Maturity {#maturity}

During your application's lifetime the deployment process will often mature. That it matures is a good thing, and sooner or later someone will probably realize that it needs to improve. In which ways it will mature is generally according to the following but not always in this particular order:

* Documentation
* Automation
* Verification
* Notification
* Tests
* Tools
* Monitoring

**Documentation**. This is what happens when the it transitions from one person deploying to multiple people deploying. It will be a natural step, since the deployment process will most often have manual steps, so you can't deploy without documentation. Somewhere a document or something similar will end up describing the different steps that describes the neccessary steps for deploying.

**Automation**. Multiple people are now deploying, but stuff breaks because of inconsistency. Even if all manual steps are well documented, unexpected errors occur that fall outside the scope of the documentation. In these cases it will end up on the one woman/man deploy army, since they will have the answers. The response and solution to this is automation. It should be a humble effort to, in the end, automate everything in the process.

**Verification**. The process is now automated enough to make anyone able to deploy in a consistent manner. But still it can break in numerous ways. The automation will handle errors so we can verify that the steps complete without errors. If any automation fails the deploy aborts, and displaying an appropriate message to the person deploying on what went wrong.

**Notification**. With verification in place, making it notify the right people when it fails should be trivial. Having a silent fail deploy could be catastrophic. Deploying the wrong changes could also be troublesome. A notification on what you are deploying (branch, commit, etc) and that it did so without errors can be as useful as for a failing one.

**Tools**. It seldom happens that a deployment process will use tools from the start. But a mature and stable enough process benefits from implementing a tool. That is becuase a tool is easier to extend, change or replace. Any previous scripting and documentation will become obsolete since the tools is providing that instead.

**Monitoring**. This is a step few will reach, and is more of a *nice to have* than a *need to have*. It's about monitoring how the application and its environments responds to a deploy. It could check CPU usage, memory usage, error responses, I/O-operations, or any other metric your application can produce. If a deploy increases memory usage by 30% you might have a big problem on your hands. The goal here is to monitor and notify on big deviations for certain key metrics.

## 2.3 Agility

The world around us as software developers is getting more and more agile. I'm talking about the world of [agile software development](http://en.wikipedia.org/wiki/Agile_software_development), not the regular outside world where people drink lattes and worry about their mortgages. More and more companies are jumping on the agile bandwagon and for a good reason. Some practices in this that are great, such as behaviour-driven development (BDD), test-driven development (TDD), pair programming, continuous integration, sprints, user stories and cross-functional teams to name some.

> Agile: "Characterized by quickness, lightness, and ease of movement; nimble."

This sums it up pretty well. You want your software development cycle to be fast, easy and adaptable, where you work in small increments and respond fast to feedback through iteration. At the beginning of 2001, a bunch of smart people got together to talk about lightweight development methods, and what they ultimately came up with was the publication [Manifesto for Agile Software Development](http://agilemanifesto.org/). This is the main part of it:

> We are uncovering better ways of developing software by doing it and helping others do it. Through this work we have come to value:

> **Individuals and interactions** over Processes and tools
>
> **Working software** over Comprehensive documentation
>
> **Customer collaboration** over Contract negotiation
>
> **Responding to change** over Following a plan
>
> That is, while there is value in the items on the right, we value the items on the left more.
>
> *Kent Beck, James Grenning, Robert C. Martin, Mike Beedle, Jim Highsmith, Steve Mellor, Arie van Bennekum, Andrew Hunt, Ken Schwaber, Alistair Cockburn, Ron Jeffries, Jeff Sutherland, Ward Cunningham, Jon Kern, Dave Thomas, Martin Fowler, Brian Marick © 2001, the above authors. This declaration may be freely copied in any form, but only in its entirety through this notice.*

This is exciting stuff and if you take a look at what is valued in the items on the left, it's people, flexibility and speed. Flexibility and speed of the actual development and people is both about the people building the software and the people using the software.

Now you might say that I have been talking about the deployment **process** a lot. And the manifesto states that it favours individuals and interactions over processes and tools. Yes, this is true; it also states that there is value in the items on the right and I consider the deployment process to be essential in supporting a true agile development process. The core in agility for software development is iteration, an ability to adapt fast and respond to feedback from people. How will you be able to do this efficiently without a good deployment process? If you iterate over a feature and spend two hours on it and then have to spend two hours deploying it, is that agile? I would argue no. Even if the deploy takes "only" 30 minutes, you need confidence in it. If there are manual steps or you can't do a quick and easy rollback, what is agile about it then?

## 2.4 Plan for a marathon

What could be better in all this than doing a comparison with a marathon? In an agile world you work in sprints and if you plan your deployment process for the current sprint, your plan will be short sighted and situational. If you expect your application to live longer than for a few sprints, you need a plan for its future. Plan for a marathon, not a short sprint.

Making estimations is hard whether you make it for your development time or your application's future. This is especially true in the agile world where adapting fast to feedback could change the direction of that future in a heartbeat. But we should at least try to do this. Try to make some predictions about where your application could end up. Coming up with a some scenarios that could occur will not harm you.

Say you deploy your application to a VPS with limited resources and no real options to scale it. If you predict there is a possibility your application would get featured on Hacker News, Reddit, Product Hunt or Slashdot, and the traffic goes through the roof, now what? Do you have a deployment process that is good enough for quickly moving your application to a new hosting provider? Does your application and deployment process support running on multiple nodes? Just to be clear, I'm not saying you should make your deployment process deal with all the possible scenarios since that would be time inefficient. What I'm saying is that you should keep those scenarios in the back of your head, while you plan for and implement with a "if X happens, I can change this to fit it"-mentality. Your deployment process should be agile too and try to make it so you can change things fast and easy.

## 2.5 Release cycles

You can write how much code you like, but if you're not shipping any of it you're not contributing with any value. You and your team should have a goal of how often you want to release code. Whether it be once every month or four times a day, as long as you have a goal and a plan for it. If you do not have a goal for it you can't reach something. So set a goal if you do not have one and try to get there. Knowing your current and your wanted release cycle is important for planning your deployment process.

I> By "shipping code" I refer to deploying it, from finishing a feature in your local environment to getting it out to the production server(s).

There are a few ways in which we ship code and the different models of it can be summed up to:

* I'm done, ship it.
* The new version is done, ship it.
* X amount of time has passed, ship it.
* I pushed it, ship it.

This is of course simplified and there are many variants of these out there in the wild. But let's discuss these individually to see what they're about.

**I'm done, ship it**. We could call this *feature deploy* or *shotgun deploy* depending on how we feel about it. This is a typical model for when there is one or few developers for an application. It's an ad-hoc type of deploy and when you have finished and tested your feature you deploy it. I think this is a very underestimated type of deploy if you can make sure that you do not push changes someone else has made. Why should we wait for a ritualistic deploy? If you version control with a good branching strategy these types of ad-hoc deploys are definitely possible. For larger teams and applications this is something I wouldn't recommend though.

**The new version is done, ship it**. This would be when your software has reached a new version, either minor or major. The point here is that the deploy payload will probably be large and the development has been undergoing for a considerable amount of time. I would avoid this model like the plague when it comes to web applications. One of the great advantages of web applications is the ability to change the application quickly without having to go through build steps and knowing your users have updated their applications. On the web you ship your code and then your user doesn't have a choice. Why even deploy only after a minor version is completed? If you are doing this, one of the reasons could be that the deployment process is to complex. Fix it and stop pushing like this.

**X amount of time has passed, ship it**. If your team is working in sprints this is probably how you deploy. Whenever a sprint is complete you deploy. The amount of time between deploys can vary depending on how long sprints you have of course and the main take here is that deploys happen on a recurring time, for example every other week on Mondays. I do not mind this model for deployment at all if you have reasonable sprint durations. If you have three month sprints it could be bad and should take a look at why you're doing this. But if you have sprints of perhaps 1-2 weeks, go for it.

**I pushed it, ship it**. Also known as *continuous deployment*. Ah, the unicorn of deploys. Here everything is deployed when one or more commits are pushed to a location. This is done through (at least should be) a very complex system of automation with testing and monitoring. The topic of how you can accomplish this is not an easy one and I will not try to explain it here.

One important point to make here is that being able to continuously deploy is living the dream. Very few will have time, knowledge and patience to set up a complete process of continuous deploys. However it's something that we can strive for even if you do not want to continuous deploy. Building a culture, environment and process where it's possible will benefit you tremendously. Having the correct tools in place for automation, monitoring, etc will never be a bad thing. If you could have it all and just flip off the switch that deploys everything automatically, you should have a sense of bliss and serenity. You've done it.

## 2.6 Technical debt and rot

Technical debt and software rot is something well known and talked about in the developer community, but the concepts can be applied to more than code. We should take care of our deployment just as much as our code. When an ad-hoc deployment process is put in place we immediately start hoarding technical debt and when it starts to rot  we have **shipping rot**. The problem with this kind of debt and rot is that it's more than usually exponential and self enforcing. Have you heard of the [broken window theory](http://en.wikipedia.org/wiki/Broken_windows_theory)? The TL;DR version is: when people see that a building has a broken window, they stop caring less about maintaining it and into the spiral it goes. This applies perfectly to software development where legacy code stays legacy and bad code breeds more bad code. The same goes for our deployment process: a bad process will stay bad or get worse. If you can stop that first window from breaking or quickly repair it, everyone else will care for that it stays that way. Having goals set is a great tool for quickly dealing with that broken window and if you at some point realize that there are many broken windows, you should perhaps fix them all in one big swoop.

## 2.7 The list {#goals-list}

We can condense the goals in to a bullet list since everybody likes a clear and concise list, right? When reading the list, try to reflect on where you currently are at the different goals.

* Automated
* Responsive
* Atomic
* Reversible
* Simple
* Fast
* Agnostic

**Automated**. Have you read the first chapter? If not, do it. Not having an automated process is the root of all evil.

**Responsive**. A good deployment process responds to what is happening. If an error occurs somewhere it should abort and notify you or the appropriate people somehow. Having steps fail silently in a chain can be very destructive for your application.

**Atomic**. Nothing in your deploy should be able to break your application and you should comply with the concept of completing a build before serving it to any user. The transition between the previous and the new build should be as close to instant as possible.

**Reversible**. Because it sounds better than "rollbackable". Being able to roll back is just as important as deploying your changes and this reverse process should also follow the list of goals.

**Simple**. Everyone should understand it and use it. Everyone should feel confident and comfortable with it. This is true for deploying, making changes or extending the process.

**Fast**. You want it to be fast because speed is key in being able to deploy often. Fast rollbacks combined with fast deploys is another key which spells confidence. If something breaks you can easily go back and fix things without that stress knowing production is in a broken state.

**Agnostic**. Building a deployment process that is dependent on its environment could be devastating. Being able to only deploy to Amazon Web Services for example will be great until you want to switch provider. The application should happily be deployed anywhere and it should also be agnostic about who or what is deploying.
