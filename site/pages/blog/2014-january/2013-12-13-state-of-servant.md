
## Workshop

### Layers CSS principles & concepts

- CSS is declarative, static, lacks logic
- Layers is unsemantic, developed to solve practical use cases
- Layers includes the boilerplate you always need, bot no extras
- Style-agnosticism



### Resources & examples

- [eiskis.net/layers](http://eiskis.net/layers/)
	- Docs
	- Source
	- Samples
- OP-asiakas
- Inversion
- Servant



### Layers workflow

Don't go box by box, page by page or header to footer. Think *coats of paint*:

1. Go as far as possible with HTML only.
2. Go as far as possible with HTML+CSS only.
3. Go as far as possible with HTML+CSS+JS only.

- Throw in HTML fast
- Don't try to control everything
- Stick to defaults for as long as you can

"Call the cops" workflow. Layers does not worry as much about semantics and ideals as it does about practicality and workflows.



### CSS is about restrictions

Every line of CSS is a restriction you impose. For example, the following...

	.row {
		width: 600px;
	}

really means *`.row` must never be anything below or above 600px in width*. Most of the time, this isn't what you want to say.

Think about it:

	############################
	#                          #
	#    ##################    #
	#    #                #    #
	#    #                #    #
	#    #                #    #
	#    #                #    #
	#    #                #    #
	#    ##################    #
	#                          #
	############################

What you really want is that a container for content should not become too wide to become unreadable. I.e. something like...

	.row-content {
		max-width: 40em;
	}

Notice how `box-sizing: border-box;` would not make sense here. We want to control available for the content, regardless of paddings. We can combine this with paddings:

	.row-content {
		max-width: 40em;
		padding: 5%;
	}

This is a great default for any container that directly contains content.
