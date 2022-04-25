# 1. Automate, automate, automate

I want to get this off the bat right away. The most crucial ingredient in your deployment process should be to **automate everything** to **minimize human errors**. If your deployment process involves manual steps, you're going to have a bad time and shit will hit the inevitable fan. Nobody is perfect. People can, and will, forget if they need to remember.

An automated deployment process will boost the confidence for the person deploying. Knowing that it will take care of everything of is a major contributor to feeling safe during a deploy. Of course other unexpected issues can arise but with a flexible process with logging and notifications you can relax and figure out what went wrong.

## 1.1 One button to rule them all

You should have **one** button to push or **one** command to run, to deploy your application. If you don't, something is wrong and you should automate all steps. Perhaps the single manual intervention I would consider okay is *"Are you sure? This will push stuff to production. [N/y]"*. This might be a bold statement but I truly believe in it.

Even if you're the sole developer on a project and you're deploying the application each time, I still say it's bad practice having manual steps. When it comes to teams it becomes a lot worse. If not all team members are familiar with the deployment steps, they won't be able to deploy. Team members come and go. Whenever a new team member arrive they will need to learn how to deploy in the correct manner. Sure there can be documentation for it. But whenever someone is familiar enough with it they will start to deploy without it.

## 1.2 Example of a manual step

Let's take an example where you have a revision number of your assets in code. I would say something along these lines are fairly common practice. I sure have seen something like this way to often. This revision number handles cache busting, so browsers do not keep serving old assets after a deploy.

This is a constant in a class somewhere for managing static assets versions.

{lang=php}
~~~~~~~~
class Assets
{
    const REVISION = 14;

    // [...]
}
~~~~~~~~

Then it's applied to serving the static assets.

{lang=html}
~~~~~~~~
<link rel="stylesheet" type="text/css" href="style.css?v=<?=Assets::REVISION?>">
~~~~~~~~

This is truly as bad of a manual step you could have. It's easy to forget since it requires a code change. If it's forgotten it might break the users' experience serving cached and outdated assets. A manual step where you have to run a command in connection to the deploy would be better since it would be easier to remember. When you are already have the command line in front of you will be more prone to remember it.

Since it requires a code change another issue will occur. That is when you remember the step in the middle of the deploy before pushing changes to production. You stop yourself before pressing the button, screaming "Shit, the assets revision number!". Now you have to deal with changing the code, committing it and push the code through a new deploy. In a perfect world you would've already merged a release branch and tagged it (discussed in chapter 2), so how would you approach that now? Reset your repository, remove the tag, commit and create the release branch again? Or would you commit and push, knowing that it will break [traceability](http://en.wikipedia.org/wiki/Traceability#Software_development) for this specific deploy? This is a problem that goes away with automation.

## 1.3 Time is always a factor

You might be prone to say that you don't have time automating all the trivial steps or commands. If you spend one hour automating a command that takes one second to run you would have to run that command 3601 times before you have saved time on it. Yes, I did the math. While this is true I would say that it isn't the whole truth.

We need to take into account the time spent on dealing with issues arising when forgetting it. Time spent on cleaning up. Can you measure bad user experience when your application breaks or behaves incorrect? You can't. In the previous example you can't tell how much time that you will spend on correcting that error. Neither can you tell the impact on the users. If the problem isn't caught in time it could persist for a long time.
