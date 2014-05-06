
var type = function (value) {

	// Possible types
	var types = {
		'[object Function]': 'function',
		'[object Date]': 'date',
		'[object RegExp]': 'regexp',
		'[object Arguments]': 'arguments',
		'[object Array]': 'array',
		'[object String]': 'string',
		'[object Null]': 'null',
		'[object Undefined]': 'undefined',
		'[object Number]': 'number',
		'[object Boolean]': 'boolean',
		'[object Object]': 'object',
		'[object Text]': 'textnode',
		'[object Uint8Array]': '8bit-array',
		'[object Uint16Array]': '16bit-array',
		'[object Uint32Array]': '32bit-array',
		'[object Uint8ClampedArray]': '8bit-array',
		'[object Error]': 'error'
	};

	// Add DOM element
	if (typeof window != 'undefined') {
		for (var el in window) if (/^HTML\w+Element$/.test(el)) {
			types['[object ' + el + ']'] = 'element';
		}
	}

	// Comparison
	return typeof value === 'object' ? types[Object.prototype.toString.call(value)] : typeof value;
};
