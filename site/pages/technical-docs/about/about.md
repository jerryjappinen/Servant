
# Technical documentation

<big>This section includes technical resources relevant to maintenance and development of Servant. Visit [Get started](/get-started) for installation walkthrough and other instructions for users.</big>



### Basic concepts

You do not need to know about programming when working with Servant. In fact, you son't have to understand almost anything to get started.

We distinguish four groups of users that work with Servant. On each step you have to know a little bit more:

1. **Users** install Servant and publish sites.
2. **Web developers** create or customize themes and templates to create their customized web sites.
3. **Backend developers** write new backend actions for custom server-side functionality (e.g. accessing a database).
4. **Servant contributors** develop Servant and write new features for the core system.

*You can use Servant without understanding anything about web development*. And you can develop web sites without understanding anything about how the backend works.



### Source code

Servant is written in *PHP*, and currently supported on PHP 5.2 and up.

*Usability*, *convenience* and *redundancy* are emphasized. If you are contributing to Servant, make an attempt to write code that can recover even when the circumstances aren't perfectly in your control. Also bear in mind that even code has users and usability: for example, you should think about what kind of ways of giving input parameters you can provide to someone who uses your methods.

[Baseline PHP](http://eiskis.net/baseline-php/) is used as a set helper functions for low-level tasks, and several different parser utilities power the support for various template formats.
