var scene = document.getElementById("scene");
var parallax = new Parallax(scene);

scene.style.pointerEvents = "initial";

// count effect when entering the stats section

var stats = document.getElementById("stats");
var counters = Array.from(stats.getElementsByClassName("counter"));

var statsY = stats.offsetTop;

function onScroll(){
	// check if the stats are visible
	if(window.scrollY + window.innerHeight > statsY + 100) // calculate with text padding
		countStats();
}

document.addEventListener("scroll", onScroll);

function countStats(){
	document.removeEventListener("scroll", onScroll);

	var values = [];
	var iterations = 0;

	counters.forEach(counter => values.push(+counter.getAttribute("data-value")));

	var countInterval = setInterval(() => {

		counters.forEach((counter, index) => counter.textContent = Math.round(values[index] * iterations / 40));

		if(iterations++ === 40)
			clearInterval(countInterval)
	}, 20);
}