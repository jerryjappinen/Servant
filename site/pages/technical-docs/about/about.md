
# Technical documentation

<big>This section includes technical resources relevant to maintenance and development of Servant. Visit [Get started](/get-started) for installation walkthrough and other instructions for users.</big>

### Basic concepts

You do not need to know everything about web development when working with Servant. In fact, you can use Servant without understanding just about anything about web development, unlike with many other systems.

We distinguish five groups of people that can work with Servant, and on each step you have to know a little bit more:

1. **Users** install Servant and publish sites with it.
2. **Theme authors** create new themes for existing templates.
3. **Template authors** write new templates for new kinds of sites.
4. **Action authors** write new backend actions. Sitemap page and minified stylesheets are examples of actions.
5. **Core contributors** write new features in Servant's core.

This means that *you can use Servant without understanding anything about themes or templates*. And you can create themes or templates without understanding anything about how the core system works.



### Source code

Servant is written in PHP. It is currently supported on PHP 5.2 and up. [Baseline PHP](http://eiskis.net/baseline-php/) is used as a set helper functions for low-level tasks (it is included in the source package), but there are no other external dependencies.

*Usability*, *convenience* and *redundancy* are emphasized. If you are contributing to Servant, make an attempt to write code that can recover even when the circumstances aren't perfectly in your control. Also bear in mind that even code has users and usability: for example, you should think about what kind of ways of giving input parameters you can provide to someone who uses your methods.
