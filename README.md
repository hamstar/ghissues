ghissues
========

A collection of PHP scripts which fetch your [correctly labeled] issues from github and formats them into HTML for a Requirements Specification document.


# How to use

## Issue labeling

These scripts work on the following heirarchy: Feature -> Use Case -> Requirements

That is, a feature is broken down into use cases, and use cases are broken down into requirements.

So your issues must be labeled **feature**, **use-case** or **requirement** to indicate their heirarchy.  See [Braincase Issues](http://github.com/hamstar/Braincase/issues) for an example.

Adding any two of those labels to one issue will produce unpredicatable results.

### Priorities

If you want priorities to be show on the titles then add one of the following labels; **prio:high**, **prio:medium** or **prio:low**.

## Issue referencing

Because I couldn't work out how to get referenced issues from an issue using the Github API, you will need to reference the parent on the first line in the issue description.  Anything else on that line will disappear.

So the following should do the trick:

```From feature #2, #3, #7```

So on requirement #3, you would reference use-case #2 and on use-case #2 you would reference feature #1.

## Your repo

Edit index.php and change:

```$builder = new ReqsBuilder( new Github( "hamstar:Braincase", new Curl ) );```

to:

```$builder = new ReqsBuilder( new Github( "yourname:yourrepo", new Curl ) );```

Then navigate to the index page to get the formatted text.  Copying and pasting this in to a modern word processor should keep the formatting intact.