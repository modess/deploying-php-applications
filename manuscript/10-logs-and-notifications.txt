# 10. Logs and notifications

In the [list in the goals chapter](#goals-list) we discussed that we want our deploys to be *responsive*. We want it to be aware of what is happening and respond to that in certain ways, for example when there an error.

Since we're always dealing with running certain commands in our deploy process, through tools or running arbitrary commands, we will have console output. Output can be verbose sometimes and that is more than often a good thing and something we can use to our advantage. Capturing output to log files or some other storage can be crucial in troubleshooting deploys that goes sideways. A silent failure in your deploy can take a lot of time and resources in debugging and fixing. If you capture output and store it somewhere where you or others can find it will make it easier.

But we don't just want to just capture the output. We also want to know when something goes the way it should or when something goes wrong. We can notify people about this through various channels. Getting a notification when a deploy is successful or when an error occurs and it is aborted gives peace of mind. A good example can be that after a successful deploy you get a notification somehow saying that "Deploy successful: [11c287] Fixed bug #215", here you can easily see that the deploy was successful and that the latest commit deployed is *11c287*. The same applies (even more so) for "Deploy failed: could not migrate database". In that event you hopefully have a log somewhere where you can go and find out exactly what made the database migration fail, maybe it could not connect to the database or it tried to perform an operation that was not supported.

## 10.1 Saving logs

There are two kinds of deploys and they need to be handled according to how they operate. The first kind is running a tool on your local computer that sends commands to your server(s) for deploy, this is true for tools such as Capistrano and Rocketeer. Then there is the other kind which runs on the server side under certain conditions, for example when using Git hooks or Phing. So a deploy could in a sense happen on the server side or through the client side.

When performing a deploy through the client side you most likely won't have to save your logs, since you will get the full output in your terminal. Exporting this to some central log storage would probably be a complex and unnecessary procedure. You will most likely get a clear idea of what happened through the output of your tool. But when a deploy runs server side it becomes important to save the log(s). Where you save them is not really important as long as everybody can find them easily, a timestamped log file on each separate server is probably sufficient.

When running a bash script, like demonstrated with Git hooks in the tools chapter, you can easily capture all output to a timestamped log file using this:

{lang=bash}
~~~
#!/bin/bash

NOW=$(date +"%Y%m%d-%H%M%S")
deploy_log="/var/logs/deploy/deploy-$NOW.log"
touch $deploy_log
exec > >(tee $deploy_log)
exec 2>&1

# The rest of your deploy commands...
~~~

Any commands that is executed after that will be saved in that log file.

If you don't have a bash script and you instead send a remote command to your server over SSH for example, there's a simple trick to achieve this also. Say you have a server running Jenkins that you deploy through, it runs all the tests and then send a remote command to your server(s) telling it to make a Phing build. You could then send the command:

    phing build > /var/logs/deploy/deploy-`date +"%Y%m%d-%H%M%S"`.log 2>&1

This is possible with any kind of command you can run on your server to capture all output into a log file.

## 10.2 Types of notification

There are a few ways of notifying people, whether that is when things run smoothly or when things go awry. Which type of notification you'll use will likely depend on the current technology your company or teams use for communication. Here are a few examples of channels you can use for notifications.

{pagebreak}

### E-mail

The most conventional way of sending out notifications is with a good old fashioned e-mail. There are a few upsides to sending notifications through e-mails, one being that you aren't limited to a certain number of characters or lines. You can append full log output in your e-mails for example. One other upside is that everyone has an e-mail address, creating lists and sending notifications to those is a great way of ensuring that people receive and read them. Just make sure that you keep your lists up to date, adding new and removing old people as they come and go.

| Tool       | Plugin                                                                           |
| ---------- | -------------------------------------------------------------------------------- |
| Git hooks  | [mail][email-1], [sendmail][email-2]                                             |
| Phing      | [MailTask][email-3]                                                              |
| Capistrano | [gofullstack/capistrano-notifier][email-4], [pboling/capistrano_mailer][email-5] |
| Rocketeer  | [Write custom task][email-6]                                                     |

[email-1]: http://linux.die.net/man/1/mail
[email-2]: http://www.sendmail.org/~ca/email/man/sendmail.html
[email-3]: https://www.phing.info/docs/guide/trunk/MailTask.html
[email-4]: https://github.com/gofullstack/capistrano-notifier
[email-5]: https://github.com/pboling/capistrano_mailer
[email-6]: http://rocketeer.autopergamene.eu/#/docs/docs/II-Concepts/Tasks

{pagebreak}

### Slack

Slack is the rising star of group communication for companies and their teams, for good reasons. Having a channel for your deploys can be a good way of the right people getting notified of relevant information. They have an API which makes it really easy to send notifications, and there are many plugins that has been built to use it. If a plugin does not exists for your deploy tool you can always use cURL or something similar for sending requests to it.

| Tool       | Plugin                                                                    |
| ---------- | ------------------------------------------------------------------------- |
| Git hooks  | [API][slack-1]                                                            |
| Phing      | [API][slack-1]                                                            |
| Capistrano | [j-mcnally/capistrano-slack][slack-2], [phallstrom/slackistrano][slack-3] |
| Rocketeer  | [rocketeers/rocketeer-slack][slack-4]                                     |

[slack-1]: https://api.slack.com/
[slack-2]: https://github.com/j-mcnally/capistrano-slack
[slack-3]: https://github.com/phallstrom/slackistrano
[slack-4]: https://github.com/rocketeers/rocketeer-slack

{pagebreak}

### HipChat

Still a popular alternative even though Slack seems to be taking more and more of the market. It is also a great tool for communication for companies and teams. Many plugins exists here as well since they have an API for sending notifications, which also always leave you the option of sending a cURL request or similar for interacting with it.

| Tool       | Plugin                                                          |
| ---------- | ------------------------------------ |
| Git hooks  | [API][hipchat-1]
| Phing      | [rcrowe/phing-hipchat][hipchat-2] |
| Capistrano | [hipchat/hipchat-rb][hipchat-3], [restorando/capistrano-hipchat][hipchat-4]                                                            |
| Rocketeer  | [rocketeers/rocketeer-hipchat][hipchat-5]                                                             |

[hipchat-1]: https://www.hipchat.com/docs/apiv2
[hipchat-2]: https://github.com/rcrowe/phing-hipchat
[hipchat-3]: https://github.com/hipchat/hipchat-rb
[hipchat-4]: https://github.com/restorando/capistrano-hipchat
[hipchat-5]: https://github.com/rocketeers/rocketeer-hipchat

{pagebreak}

### IRC

The technology that never seems to go out of fashion. It's commonly used and have an impressive track record, it has been around since 1988! The amount of characters you can include in your messages might be a bit limiting though, full log outputs is not to consider here. But a "Deploy successful: [11c287] Fixed bug #215" is often enough as a message.

| Tool       | Plugin                                                                     |
| ---------- | -------------------------------------------------------------------------- |
| Git hooks  | [Send message to IRC channel from bash][irc-1]                             |
| Phing      | [Send message to IRC channel from bash][irc-1]                             |
| Capistrano | [ursm/capistrano-notification][irc-2], [linyows/capistrano-ikachan][irc-3] |
| Rocketeer  | [Mochaka/rocketeer-irc][irc-4]                                             |

[irc-1]: http://serverfault.com/questions/183157/send-message-to-irc-channel-from-bash
[irc-2]: https://github.com/ursm/capistrano-notification
[irc-3]: https://github.com/linyows/capistrano-ikachan
[irc-4]: https://github.com/Mochaka/rocketeer-irc

{pagebreak}

## 10.3 Useful git commands

If you're following the git-flow branching model in the [version control chapter](#chapter-version-control), there are some useful git commands you can use. You can leverage the git binary and capture output which you then can include in your notifications. Of course you can capture output for any CLI command that you use to include in your notifications, git is just one example and these are a handful of useful ones.

**Current branch**

Displaying the current branch can be good to make sure that the right branch is deployed.

    git rev-parse --abbrev-ref HEAD

**Commit at HEAD**

It's never a bad idea to show where your HEAD pointer is. This tells you exactly where your deploy is.

    git --no-pager log --abbrev-commit --pretty=oneline -1

**Latest tag**

Including your latest tag in a notification is great since it will tell which version that was just deployed.

    # For current branch
    git describe --abbrev=0 --tags

    # Across all branches
    git describe --tags $(git rev-list --tags --max-count=1)

**Commits between latest tag and previous tag**

This command allows you to generate a simple changelog with all commits that was deployed. Excellent for appending as complementary information to your notification.

    git --no-pager log --abbrev-commit --pretty=oneline $(git rev-list --tags --max-count=1)...$(git describe --abbrev=0 $(git describe --tags $(git rev-list --tags --max-count=1)^))
