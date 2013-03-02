
# Hacking

Let's face it, if you have a reason to go mess with the core codebase, you are going to implement a dirty hack to get the program to do what you want. Proot doesn't stand in the way of doing something you need to do to make a specific installation serve its purpose better for some ideological reasons. Proot is not there to make your life harder. I'll also try to keep Proot running on PHP 5.2, which means I can't use all the new bells and whistles.

Logic is not buried under complex class hierarchies, and you won't see any structures you haven't seen in your most basic PHP tutorials. For experienced programmers this might seem ugly, but for us normal people the code is less frightening and more predictable. Some actions will implement more complex logic in more mature ways, but those are not part of the core.

There is a predefined, clean way to make customizations. *Hacks* are a good way to make installation-specific overrides to system settings or introduce some esoteric tweaks for some esoteric installations. Hacks are kept in one place and are easy to test and maintain when updating Proot. And if you find yourself changing the same things over and over again, messaging Proot developers might result in the improvements being included in core behavior.

The most common use case for hacks is custom overrides to system settings, for correct database credentials for example. Use hacks if possible, but if it's not, just tweak the code.
