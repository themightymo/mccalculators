var select = document.getElementById('input_3_2');

var html5Slider = document.getElementById('slider-container');
noUiSlider.create(html5Slider, {
	start: [1],
	range: {
		'min': [ 1 ],
		'max': [ 10 ],
	}
});
var inputNumber = document.getElementById('input-number');
html5Slider.noUiSlider.on('update', function(values, handle) {
	var value = values[handle];
	if (handle) {
		inputNumber.value = value;
	} else {
		select.value = Math.round(value);
	}
});
select.addEventListener('change', function() {
	html5Slider.noUiSlider.set([this.value, null]);
});
inputNumber.addEventListener('change', function() {
	html5Slider.noUiSlider.set([null, this.value]);
});